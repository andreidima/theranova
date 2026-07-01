<?php

namespace App\Http\Controllers;

use App\Mail\OfertaProspectareAdminNotification;
use App\Mail\OfertaProspectareClient;
use App\Models\Cerinta;
use App\Models\ClientProspectare;
use App\Models\DataMedicala;
use App\Models\FisaCaz;
use App\Models\OfertaProspectare;
use App\Models\OfertaProspectareAdaosInterval;
use App\Models\OfertaProspectareAmputatie;
use App\Models\OfertaProspectareLinie;
use App\Models\OfertaProspectareVarianta;
use App\Models\Pacient;
use App\Models\ProdusProspectare;
use App\Models\ProspectareConfigurator;
use App\Models\ProspectareConfiguratorComponenta;
use App\Models\ProspectareConfiguratorGrup;
use App\Models\User;
use App\Services\BonusCalculatorService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class OfertaProspectareController extends Controller
{
    public function index(Request $request): View
    {
        $request->session()->forget('ofertaProspectareReturnUrl');

        $query = OfertaProspectare::with(['emitent:id,name', 'aprobator:id,name', 'linii'])
            ->when(!$this->canViewAll($request->user()), function ($query) use ($request) {
                $query->where('user_emitent_id', $request->user()->id);
            })
            ->when($request->search, function ($query, $search) {
                foreach (explode(' ', (string) $search) as $cuvant) {
                    $query->where(function ($query) use ($cuvant) {
                        $query->where('nume_client', 'like', '%' . $cuvant . '%')
                            ->orWhere('telefon', 'like', '%' . $cuvant . '%')
                            ->orWhere('email', 'like', '%' . $cuvant . '%');
                    });
                }
            })
            ->when($request->judet, fn ($query, $judet) => $query->where('judet', 'like', '%' . $judet . '%'))
            ->when($request->status_aprobare, fn ($query, $status) => $query->where('status_aprobare', $status))
            ->when($request->status_client, fn ($query, $status) => $query->where('status_client', $status))
            ->when($request->user_emitent_id, fn ($query, $userId) => $query->where('user_emitent_id', $userId))
            ->latest();

        $oferte = $query->paginate(25)->withQueryString();
        $useri = User::select('id', 'name', 'role')->orderBy('name')->get();

        return view('oferteProspectare.index', [
            'oferte' => $oferte,
            'useri' => $useri,
            'search' => $request->search,
            'judet' => $request->judet,
            'statusAprobare' => $request->status_aprobare,
            'statusClient' => $request->status_client,
            'userEmitentId' => $request->user_emitent_id,
        ]);
    }

    public function create(Request $request): View
    {
        $request->session()->get('ofertaProspectareReturnUrl') ?? $request->session()->put('ofertaProspectareReturnUrl', url()->previous());

        $oferta = new OfertaProspectare([
            'data_ofertei' => Carbon::today(),
            'valabila_pana_la' => Carbon::today()->addDays(14),
            'user_emitent_id' => $request->user()->id,
            'status_aprobare' => OfertaProspectare::APROBARE_DRAFT,
            'status_client' => OfertaProspectare::CLIENT_NESTRIMISA,
        ]);

        return view('oferteProspectare.create', $this->formData($oferta));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateOffer($request);
        $action = $request->input('action', 'save');

        $oferta = OfertaProspectare::create(array_merge($this->offerPayload($validated), [
            'user_emitent_id' => $request->user()->id,
            'status_aprobare' => $action === 'submit'
                ? OfertaProspectare::APROBARE_IN_ASTEPTARE
                : OfertaProspectare::APROBARE_DRAFT,
            'status_client' => OfertaProspectare::CLIENT_NESTRIMISA,
            'data_ofertei' => $validated['data_ofertei'] ?? Carbon::today()->toDateString(),
            'valabila_pana_la' => $validated['valabila_pana_la'] ?? Carbon::today()->addDays(14)->toDateString(),
        ]));
        $this->syncClientProspectareDinOferta($oferta);

        $this->syncAmputatii($oferta, $request->input('amputatii', []));
        if ($request->has('linii')) {
            $this->syncLinii($oferta, $request->input('linii', []));
        }
        $this->syncVariante($oferta, $request->input('variante', []));
        $oferta->recalculeazaTotaluri();

        if ($action === 'submit') {
            $this->notifyAdmins($oferta, 'Oferta noua in asteptare aprobare');
        }

        return redirect($oferta->path())->with('status', 'Oferta de prospectare a fost salvata.');
    }

    public function show(Request $request, OfertaProspectare $ofertaProspectare): View
    {
        $this->authorizeOfferAccess($request, $ofertaProspectare);
        $request->session()->get('ofertaProspectareReturnUrl') ?? $request->session()->put('ofertaProspectareReturnUrl', url()->previous());

        $ofertaProspectare->load(['emitent', 'aprobator', 'amputatii', 'linii.produs', 'variante.componente', 'trimiteri.user', 'pacient', 'fisaCaz', 'clientProspectare']);

        return view('oferteProspectare.show', [
            'oferta' => $ofertaProspectare,
            'canApprove' => $this->canApprove($request->user()),
        ]);
    }

    public function edit(Request $request, OfertaProspectare $ofertaProspectare): View
    {
        $this->authorizeOfferAccess($request, $ofertaProspectare);
        $request->session()->get('ofertaProspectareReturnUrl') ?? $request->session()->put('ofertaProspectareReturnUrl', url()->previous());

        return view('oferteProspectare.edit', $this->formData($ofertaProspectare));
    }

    public function update(Request $request, OfertaProspectare $ofertaProspectare): RedirectResponse
    {
        $this->authorizeOfferAccess($request, $ofertaProspectare);

        if ($ofertaProspectare->status_client === OfertaProspectare::CLIENT_CONVERTITA) {
            return back()->with('error', 'Oferta convertita nu mai poate fi modificata.');
        }

        $validated = $this->validateOffer($request);
        $action = $request->input('action', 'save');

        $validated['status_aprobare'] = $action === 'submit'
            ? OfertaProspectare::APROBARE_IN_ASTEPTARE
            : OfertaProspectare::APROBARE_DRAFT;
        $validated['user_aprobator_id'] = null;
        $validated['aprobata_la'] = null;

        $ofertaProspectare->update($this->offerPayload($validated));
        $this->syncClientProspectareDinOferta($ofertaProspectare);
        $this->syncAmputatii($ofertaProspectare, $request->input('amputatii', []));
        if ($request->has('linii')) {
            $this->syncLinii($ofertaProspectare, $request->input('linii', []));
        }
        $this->syncVariante($ofertaProspectare, $request->input('variante', []));
        $ofertaProspectare->recalculeazaTotaluri();

        if ($action === 'submit') {
            $this->notifyAdmins($ofertaProspectare, 'Oferta retrimisa pentru aprobare');
        }

        return redirect($ofertaProspectare->path())->with('status', 'Oferta de prospectare a fost modificata.');
    }

    public function destroy(Request $request, OfertaProspectare $ofertaProspectare): RedirectResponse
    {
        $this->authorizeOfferAccess($request, $ofertaProspectare);

        if (!$request->user()->hasRole('stergere') && !$this->canApprove($request->user())) {
            return back()->with('error', 'Nu ai drepturi de stergere.');
        }

        $ofertaProspectare->linii()->delete();
        $ofertaProspectare->variante()->with('componente')->get()->each(function (OfertaProspectareVarianta $varianta) {
            $varianta->componente()->delete();
            $varianta->delete();
        });
        $ofertaProspectare->amputatii()->delete();
        $ofertaProspectare->trimiteri()->delete();
        $ofertaProspectare->delete();

        return redirect('/oferte-prospectare')->with('status', 'Oferta de prospectare a fost stearsa.');
    }

    public function submitForApproval(Request $request, OfertaProspectare $ofertaProspectare): RedirectResponse
    {
        $this->authorizeOfferAccess($request, $ofertaProspectare);

        if (!$ofertaProspectare->linii()->exists() && !$ofertaProspectare->variante()->exists()) {
            return back()->with('error', 'Adauga cel putin o varianta de oferta inainte de trimiterea la aprobare.');
        }

        $ofertaProspectare->update([
            'status_aprobare' => OfertaProspectare::APROBARE_IN_ASTEPTARE,
            'user_aprobator_id' => null,
            'aprobata_la' => null,
        ]);

        $this->notifyAdmins($ofertaProspectare, 'Oferta in asteptare aprobare');

        return back()->with('status', 'Oferta a fost trimisa catre aprobare.');
    }

    public function approve(Request $request, OfertaProspectare $ofertaProspectare): RedirectResponse
    {
        $this->authorizeApproval($request);

        $ofertaProspectare->update([
            'status_aprobare' => OfertaProspectare::APROBARE_APROBATA,
            'user_aprobator_id' => $request->user()->id,
            'aprobata_la' => now(),
            'observatii_admin' => $request->input('observatii_admin'),
        ]);

        return back()->with('status', 'Oferta a fost aprobata intern.');
    }

    public function requestChanges(Request $request, OfertaProspectare $ofertaProspectare): RedirectResponse
    {
        $this->authorizeApproval($request);

        $request->validate(['observatii_admin' => 'nullable|max:5000']);
        $ofertaProspectare->update([
            'status_aprobare' => OfertaProspectare::APROBARE_MODIFICARI,
            'user_aprobator_id' => $request->user()->id,
            'observatii_admin' => $request->input('observatii_admin'),
        ]);

        return back()->with('status', 'Oferta a fost marcata pentru modificari.');
    }

    public function reject(Request $request, OfertaProspectare $ofertaProspectare): RedirectResponse
    {
        $this->authorizeApproval($request);

        $request->validate(['observatii_admin' => 'nullable|max:5000']);
        $ofertaProspectare->update([
            'status_aprobare' => OfertaProspectare::APROBARE_RESPINSA,
            'user_aprobator_id' => $request->user()->id,
            'observatii_admin' => $request->input('observatii_admin'),
        ]);

        return back()->with('status', 'Oferta a fost respinsa intern.');
    }

    public function updateClientStatus(Request $request, OfertaProspectare $ofertaProspectare): RedirectResponse
    {
        $this->authorizeOfferAccess($request, $ofertaProspectare);

        $validated = $request->validate([
            'status_client' => 'required|in:' . implode(',', array_keys(OfertaProspectare::statusuriClient())),
        ]);

        $ofertaProspectare->update([
            'status_client' => $validated['status_client'],
            'raspuns_client_la' => in_array($validated['status_client'], [
                OfertaProspectare::CLIENT_APROBATA,
                OfertaProspectare::CLIENT_RESPINSA,
                OfertaProspectare::CLIENT_EXPIRATA,
            ], true) ? now() : $ofertaProspectare->raspuns_client_la,
        ]);

        return back()->with('status', 'Statusul clientului a fost actualizat.');
    }

    public function pdf(Request $request, OfertaProspectare $ofertaProspectare)
    {
        $this->authorizeOfferAccess($request, $ofertaProspectare);
        $ofertaProspectare->load(['emitent', 'aprobator', 'amputatii', 'linii', 'variante.componente', 'variante.configurator']);

        $pdf = \PDF::loadView('oferteProspectare.export.pdf', ['oferta' => $ofertaProspectare])
            ->setPaper('a4', 'portrait');
        $pdf->getDomPDF()->set_option('enable_php', true);

        return $pdf->stream('oferta-prospectare-' . $ofertaProspectare->id . '.pdf');
    }

    public function sendEmail(Request $request, OfertaProspectare $ofertaProspectare): RedirectResponse
    {
        $this->authorizeOfferAccess($request, $ofertaProspectare);
        $this->ensureApproved($ofertaProspectare);

        $request->validate(['mesaj' => 'nullable|max:2000']);

        if (!$ofertaProspectare->email) {
            return back()->with('error', 'Oferta nu are email client.');
        }

        Mail::to($ofertaProspectare->email)->send(new OfertaProspectareClient($ofertaProspectare, $request->input('mesaj')));
        $this->markSent($ofertaProspectare, 'email', $ofertaProspectare->email, $request->input('mesaj'));

        return back()->with('status', 'Oferta a fost trimisa prin email.');
    }

    public function whatsapp(Request $request, OfertaProspectare $ofertaProspectare): RedirectResponse
    {
        $this->authorizeOfferAccess($request, $ofertaProspectare);
        $this->ensureApproved($ofertaProspectare);

        if (!$ofertaProspectare->telefon) {
            return back()->with('error', 'Oferta nu are telefon client.');
        }

        $mesaj = $this->defaultClientMessage($ofertaProspectare);
        $this->markSent($ofertaProspectare, 'whatsapp', $ofertaProspectare->telefon, $mesaj);

        return redirect()->away('https://wa.me/' . $this->normalizePhoneForWhatsapp($ofertaProspectare->telefon) . '?text=' . urlencode($mesaj));
    }

    public function convert(Request $request, OfertaProspectare $ofertaProspectare): RedirectResponse
    {
        $this->authorizeOfferAccess($request, $ofertaProspectare);

        if ($ofertaProspectare->fisa_caz_id) {
            return back()->with('error', 'Oferta este deja convertita.');
        }

        $validated = $request->validate([
            'pacient_nume' => 'required|max:200',
            'pacient_prenume' => 'required|max:200',
            'tip_lucrare_solicitata' => 'required|max:200',
        ]);

        $pacient = Pacient::create([
            'user_responsabil' => $ofertaProspectare->user_emitent_id ?: $request->user()->id,
            'nume' => $validated['pacient_nume'],
            'prenume' => $validated['pacient_prenume'],
            'telefon' => $ofertaProspectare->telefon,
            'email' => $ofertaProspectare->email,
            'localitate' => $ofertaProspectare->localitate,
            'judet' => $ofertaProspectare->judet,
            'cum_a_aflat_de_theranova' => $ofertaProspectare->sursa,
            'observatii' => 'Creat din oferta prospectare #' . $ofertaProspectare->id,
        ]);

        $fisaCaz = FisaCaz::create([
            'data' => Carbon::today()->toDateString(),
            'tip_lucrare_solicitata' => $validated['tip_lucrare_solicitata'],
            'user_vanzari' => $ofertaProspectare->user_emitent_id ?: $request->user()->id,
            'pacient_id' => $pacient->id,
            'observatii' => 'Conversie din oferta prospectare #' . $ofertaProspectare->id,
            'stare' => 1,
        ]);
        $this->syncTipLucrareSolicitataId($fisaCaz);

        $primaAmputatie = $ofertaProspectare->amputatii()->first();

        if ($this->hasMedicalData($ofertaProspectare)) {
            $fisaCaz->dateMedicale()->save(DataMedicala::make([
                'greutate' => $ofertaProspectare->greutate,
                'parte_amputata' => $primaAmputatie->parte_amputata ?? $ofertaProspectare->parte_amputata,
                'amputatie' => $primaAmputatie->amputatie ?? $ofertaProspectare->amputatie,
                'nivel_de_activitate' => $ofertaProspectare->nivel_de_activitate,
                'a_mai_purtat_proteza' => $ofertaProspectare->a_mai_purtat_proteza,
            ]));
        }

        $fisaCaz->cerinte()->save(Cerinta::make([
            'decizie_cas' => $ofertaProspectare->decontare_cas ? 1 : 0,
            'buget_disponibil' => $ofertaProspectare->buget_disponibil,
            'observatii' => 'Oferta prospectare #' . $ofertaProspectare->id . ', valoare totala: ' . $ofertaProspectare->valoare_totala,
        ]));

        $ofertaProspectare->update([
            'pacient_id' => $pacient->id,
            'fisa_caz_id' => $fisaCaz->id,
            'status_client' => OfertaProspectare::CLIENT_CONVERTITA,
            'convertita_la' => now(),
        ]);

        return redirect($fisaCaz->path())->with('status', 'Oferta a fost convertita in pacient si fisa caz.');
    }

    public function clientiIndex(Request $request): View
    {
        $clienti = ClientProspectare::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nume', 'like', '%' . $search . '%')
                        ->orWhere('telefon', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('activ')
            ->orderBy('nume')
            ->paginate(50)
            ->withQueryString();

        return view('oferteProspectare.clienti', [
            'clienti' => $clienti,
            'search' => $request->search,
            'surseProspectare' => $this->surseProspectare(),
            'judeteRomania' => $this->judeteRomania(),
        ]);
    }

    public function clientiStore(Request $request): RedirectResponse
    {
        ClientProspectare::create($this->validateClientProspectare($request));

        return back()->with('status', 'Clientul a fost adaugat.');
    }

    public function clientiUpdate(Request $request, ClientProspectare $client): RedirectResponse
    {
        $client->update($this->validateClientProspectare($request));

        return back()->with('status', 'Clientul a fost modificat.');
    }

    public function clientiDestroy(Request $request, ClientProspectare $client): RedirectResponse
    {
        $client->update(['activ' => false]);

        return back()->with('status', 'Clientul a fost dezactivat.');
    }

    public function configuratoareIndex(Request $request): View
    {
        $configuratoare = ProspectareConfigurator::with('grupuri.componente')
            ->orderByDesc('activ')
            ->orderBy('denumire')
            ->paginate(20);

        return view('oferteProspectare.configuratoare', [
            'configuratoare' => $configuratoare,
        ]);
    }

    public function configuratoareStore(Request $request): RedirectResponse
    {
        ProspectareConfigurator::create($this->validateConfigurator($request));

        return back()->with('status', 'Configuratorul a fost adaugat.');
    }

    public function configuratoareUpdate(Request $request, ProspectareConfigurator $configurator): RedirectResponse
    {
        $configurator->update($this->validateConfigurator($request));

        return back()->with('status', 'Configuratorul a fost modificat.');
    }

    public function configuratoareDestroy(Request $request, ProspectareConfigurator $configurator): RedirectResponse
    {
        $configurator->update(['activ' => false]);

        return back()->with('status', 'Configuratorul a fost dezactivat.');
    }

    public function configuratorGrupStore(Request $request, ProspectareConfigurator $configurator): RedirectResponse
    {
        $configurator->grupuri()->create($this->validateConfiguratorGrup($request));

        return back()->with('status', 'Grupul a fost adaugat.');
    }

    public function configuratorGrupUpdate(Request $request, ProspectareConfiguratorGrup $grup): RedirectResponse
    {
        $grup->update($this->validateConfiguratorGrup($request));

        return back()->with('status', 'Grupul a fost modificat.');
    }

    public function configuratorGrupDestroy(Request $request, ProspectareConfiguratorGrup $grup): RedirectResponse
    {
        $grup->componente()->delete();
        $grup->delete();

        return back()->with('status', 'Grupul a fost sters.');
    }

    public function configuratorComponentaStore(Request $request, ProspectareConfiguratorGrup $grup): RedirectResponse
    {
        $grup->componente()->create($this->validateConfiguratorComponenta($request));

        return back()->with('status', 'Componenta a fost adaugata.');
    }

    public function configuratorComponentaUpdate(Request $request, ProspectareConfiguratorComponenta $componenta): RedirectResponse
    {
        $componenta->update($this->validateConfiguratorComponenta($request));

        return back()->with('status', 'Componenta a fost modificata.');
    }

    public function configuratorComponentaDestroy(Request $request, ProspectareConfiguratorComponenta $componenta): RedirectResponse
    {
        $componenta->update(['activ' => false]);

        return back()->with('status', 'Componenta a fost dezactivata.');
    }

    public function produseIndex(Request $request): View
    {
        $produse = ProdusProspectare::query()
            ->withCount('liniiOferta')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('denumire', 'like', '%' . $search . '%')
                        ->orWhere('cod', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('activ')
            ->orderBy('denumire')
            ->paginate(50)
            ->withQueryString();

        return view('oferteProspectare.produse', [
            'produse' => $produse,
            'search' => $request->search,
            'canManageProduseProspectare' => true,
        ]);
    }

    public function produseStore(Request $request): RedirectResponse
    {
        ProdusProspectare::create($this->validateProduct($request));

        return back()->with('status', 'Produsul a fost adaugat.');
    }

    public function produseUpdate(Request $request, ProdusProspectare $produs): RedirectResponse
    {
        $produs->update($this->validateProduct($request));

        return back()->with('status', 'Produsul a fost modificat.');
    }

    public function produseDestroy(Request $request, ProdusProspectare $produs): RedirectResponse
    {
        if (!$produs->liniiOferta()->exists()) {
            $produs->delete();

            return back()->with('status', 'Produsul a fost sters.');
        }

        $produs->update(['activ' => false]);

        return back()->with('status', 'Produsul este folosit in oferte si a fost doar dezactivat.');
    }

    public function produseSelectOptions(Request $request)
    {
        $data = $request->validate([
            'search' => 'nullable|string|max:150',
            'id' => 'nullable|integer|exists:produse_prospectare,id',
            'limit' => 'nullable|integer|min:1|max:50',
            'page' => 'nullable|integer|min:1',
        ]);

        $limit = $data['limit'] ?? 25;
        $page = $data['page'] ?? 1;

        if (!empty($data['id'])) {
            $produs = ProdusProspectare::findOrFail($data['id']);

            return response()->json([
                'results' => [$this->formatProdusProspectareOption($produs)],
            ]);
        }

        $search = $data['search'] ?? null;
        $paginator = ProdusProspectare::query()
            ->where('activ', true)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('denumire', 'like', '%' . $search . '%')
                        ->orWhere('cod', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('denumire')
            ->simplePaginate($limit, ['*'], 'page', $page);

        return response()->json([
            'results' => $paginator->getCollection()
                ->map(fn ($produs) => $this->formatProdusProspectareOption($produs))
                ->values(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'next_page' => $paginator->hasMorePages() ? $paginator->currentPage() + 1 : null,
                'has_more' => $paginator->hasMorePages(),
            ],
        ]);
    }

    public function produseQuickStore(Request $request)
    {
        $produs = ProdusProspectare::create($this->validateProduct($request));

        return response()->json([
            'produs' => $this->formatProdusProspectareOption($produs),
        ], 201);
    }

    public function adaosIndex(Request $request): View
    {
        $intervale = OfertaProspectareAdaosInterval::query()
            ->orderBy('valoare_min')
            ->orderByRaw('valoare_max is null')
            ->orderBy('valoare_max')
            ->paginate(50);

        return view('oferteProspectare.adaos', [
            'intervale' => $intervale,
        ]);
    }

    public function adaosStore(Request $request): RedirectResponse
    {
        $validated = $this->validateAdaosInterval($request);
        if ($error = $this->adaosIntervalError($validated)) {
            return back()->withInput()->withErrors($error);
        }

        OfertaProspectareAdaosInterval::create($validated);

        return back()->with('status', 'Intervalul de adaos a fost adaugat.');
    }

    public function adaosUpdate(Request $request, OfertaProspectareAdaosInterval $adaosInterval): RedirectResponse
    {
        $validated = $this->validateAdaosInterval($request);
        if ($error = $this->adaosIntervalError($validated, $adaosInterval->id)) {
            return back()->withInput()->withErrors($error);
        }

        $adaosInterval->update($validated);

        return back()->with('status', 'Intervalul de adaos a fost modificat.');
    }

    public function adaosDestroy(Request $request, OfertaProspectareAdaosInterval $adaosInterval): RedirectResponse
    {
        $adaosInterval->delete();

        return back()->with('status', 'Intervalul de adaos a fost sters.');
    }

    protected function formData(OfertaProspectare $oferta): array
    {
        $oferta->loadMissing(['amputatii', 'linii.produs', 'variante.componente']);

        $amputatiiFormData = old('amputatii');
        if (is_null($amputatiiFormData)) {
            $amputatiiFormData = $oferta->amputatii->map(function ($amputatie) {
                return $amputatie->only(['id', 'parte_amputata', 'amputatie']);
            })->toArray();
        }

        if (empty($amputatiiFormData) && ($oferta->parte_amputata || $oferta->amputatie)) {
            $amputatiiFormData = [[
                'id' => null,
                'parte_amputata' => $oferta->parte_amputata,
                'amputatie' => $oferta->amputatie,
            ]];
        }

        $varianteFormData = old('variante');
        if (is_null($varianteFormData)) {
            $varianteFormData = $oferta->variante->map(function ($varianta) {
                return [
                    'id' => $varianta->id,
                    'configurator_id' => $varianta->configurator_id,
                    'titlu' => $varianta->titlu,
                    'categorie' => $varianta->categorie,
                    'selected_component_ids' => $varianta->componente->pluck('componenta_id')->filter()->map(fn ($id) => (int) $id)->values()->all(),
                    'total_manual' => $varianta->total_manual,
                    'discount_tip' => $varianta->discount_tip ?: 'valoare',
                    'discount_valoare' => $varianta->discount_valoare,
                ];
            })->toArray();
        }

        $clientiProspectare = ClientProspectare::where('activ', true)
            ->orderBy('nume')
            ->get(['id', 'nume', 'telefon', 'email', 'localitate', 'judet', 'sursa']);

        $configuratoare = ProspectareConfigurator::with(['grupuri.componente' => fn ($query) => $query->where('activ', true)])
            ->where('activ', true)
            ->orderBy('denumire')
            ->get()
            ->map(function (ProspectareConfigurator $configurator) {
                return [
                    'id' => $configurator->id,
                    'denumire' => $configurator->denumire,
                    'categorie' => $configurator->categorie,
                    'grupuri' => $configurator->grupuri->map(fn ($grup) => [
                        'id' => $grup->id,
                        'denumire' => $grup->denumire,
                        'componente' => $grup->componente->map(fn ($componenta) => [
                            'id' => $componenta->id,
                            'denumire' => $componenta->denumire,
                            'producator' => $componenta->producator,
                            'pret' => (int) $componenta->pret,
                        ])->values(),
                    ])->values(),
                ];
            })
            ->values();

        return [
            'oferta' => $oferta,
            'amputatiiFormData' => $amputatiiFormData,
            'varianteFormData' => $varianteFormData,
            'clientiProspectare' => $clientiProspectare,
            'configuratoareProspectare' => $configuratoare,
            'adaosIntervale' => OfertaProspectareAdaosInterval::active()
                ->orderByDesc('valoare_min')
                ->get(['categorie', 'valoare_min', 'valoare_max', 'valoare_adaos', 'procent']),
            'canManageProduseProspectare' => true,
            'surseProspectare' => $this->surseProspectare(),
            'judeteRomania' => $this->judeteRomania(),
        ];
    }

    protected function validateOffer(Request $request): array
    {
        return $request->validate([
            'nume_client' => 'required|max:255',
            'client_prospectare_id' => 'nullable|integer|exists:clienti_prospectare,id',
            'telefon' => 'required|max:100',
            'email' => 'nullable|max:255|email:rfc,dns',
            'localitate' => 'nullable|max:200',
            'judet' => 'required|max:200',
            'sursa' => 'nullable|max:200',
            'data_ofertei' => 'nullable|date',
            'valabila_pana_la' => 'nullable|date',
            'tip_lucrare_solicitata' => 'nullable|max:200',
            'greutate' => 'nullable|integer|min:1|max:255',
            'nivel_de_activitate' => 'nullable|max:100',
            'a_mai_purtat_proteza' => 'nullable|in:0,1',
            'decontare_cas' => 'nullable|boolean',
            'buget_disponibil' => 'nullable|integer|min:0|max:1000000',
            'total_oferta' => 'nullable|integer|min:0|max:10000000',
            'discount_aditional' => 'nullable|integer|min:0|max:1000000',
            'observatii_interne' => 'nullable|max:5000',
            'amputatii.*.id' => 'nullable|integer',
            'amputatii.*.parte_amputata' => 'nullable|max:100',
            'amputatii.*.amputatie' => 'nullable|max:100',
            'linii.*.id' => 'nullable|integer',
            'linii.*.produs_prospectare_id' => 'nullable|integer|exists:produse_prospectare,id',
            'linii.*.denumire_produs' => 'nullable|max:255',
            'variante.*.id' => 'nullable|integer',
            'variante.*.configurator_id' => 'nullable|integer|exists:prospectare_configuratoare,id',
            'variante.*.titlu' => 'nullable|max:255',
            'variante.*.categorie' => 'nullable|max:150',
            'variante.*.selected_component_ids' => 'nullable|array',
            'variante.*.selected_component_ids.*' => 'nullable|integer|exists:prospectare_configurator_componente,id',
            'variante.*.total_manual' => 'nullable|integer|min:0|max:10000000',
            'variante.*.discount_tip' => 'nullable|in:valoare,procent',
            'variante.*.discount_valoare' => 'nullable|integer|min:0|max:10000000',
        ]);
    }

    protected function offerPayload(array $validated): array
    {
        $payload = Arr::except($validated, ['amputatii', 'linii', 'variante']);
        $payload['decontare_cas'] = (bool) ($payload['decontare_cas'] ?? false);
        $payload['buget_disponibil'] = $payload['decontare_cas'] ? ($payload['buget_disponibil'] ?? null) : null;
        $payload['total_oferta'] = $payload['total_oferta'] ?? 0;
        $payload['discount_aditional'] = $payload['discount_aditional'] ?? 0;
        $payload['discount_tip'] = $payload['discount_tip'] ?? 'valoare';
        $payload['cauza_amputatiei'] = null;
        $payload['descriere_amputatie'] = null;

        return $payload;
    }

    protected function validateProduct(Request $request): array
    {
        $validated = $request->validate([
            'denumire' => 'required|max:255',
            'cod' => 'nullable|max:100',
            'activ' => 'nullable|boolean',
            'observatii' => 'nullable|max:5000',
        ]);

        $validated['descriere'] = null;
        $validated['pret_end_user'] = 0;
        $validated['activ'] = (bool) ($validated['activ'] ?? true);

        return $validated;
    }

    protected function validateClientProspectare(Request $request): array
    {
        $validated = $request->validate([
            'nume' => 'required|max:255',
            'telefon' => 'nullable|max:100',
            'email' => 'nullable|max:255|email:rfc,dns',
            'localitate' => 'nullable|max:200',
            'judet' => 'nullable|max:200',
            'sursa' => 'nullable|max:100',
            'activ' => 'nullable|boolean',
        ]);

        $validated['activ'] = (bool) ($validated['activ'] ?? true);

        return $validated;
    }

    protected function validateConfigurator(Request $request): array
    {
        $validated = $request->validate([
            'denumire' => 'required|max:255',
            'categorie' => 'nullable|max:150',
            'text_pdf' => 'nullable|max:10000',
            'activ' => 'nullable|boolean',
        ]);

        $validated['activ'] = (bool) ($validated['activ'] ?? true);

        return $validated;
    }

    protected function validateConfiguratorGrup(Request $request): array
    {
        return $request->validate([
            'denumire' => 'required|max:255',
            'ordine' => 'nullable|integer|min:0|max:999',
        ]);
    }

    protected function validateConfiguratorComponenta(Request $request): array
    {
        $validated = $request->validate([
            'denumire' => 'required|max:255',
            'producator' => 'nullable|max:255',
            'pret' => 'nullable|integer|min:0|max:10000000',
            'ordine' => 'nullable|integer|min:0|max:999',
            'activ' => 'nullable|boolean',
        ]);

        $validated['pret'] = $validated['pret'] ?? 0;
        $validated['activ'] = (bool) ($validated['activ'] ?? true);

        return $validated;
    }

    protected function validateAdaosInterval(Request $request): array
    {
        $validated = $request->validate([
            'categorie' => 'nullable|max:150',
            'valoare_min' => 'required|integer|min:0|max:10000000',
            'valoare_max' => 'nullable|integer|min:0|max:10000000',
            'valoare_adaos' => 'required|integer|min:0|max:10000000',
            'activ' => 'nullable|boolean',
        ]);

        $validated['valoare_max'] = $validated['valoare_max'] ?? null;
        $validated['procent'] = 0;
        $validated['activ'] = (bool) ($validated['activ'] ?? false);

        return $validated;
    }

    protected function adaosIntervalError(array $interval, ?int $ignoreId = null): ?array
    {
        $min = (int) $interval['valoare_min'];
        $max = is_null($interval['valoare_max']) ? null : (int) $interval['valoare_max'];

        if (!is_null($max) && $max < $min) {
            return ['valoare_max' => 'Valoarea maxima trebuie sa fie mai mare sau egala cu valoarea minima.'];
        }

        if (!$interval['activ']) {
            return null;
        }

        $overlapExists = OfertaProspectareAdaosInterval::query()
            ->active()
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->where(function ($query) use ($interval) {
                $query->where('categorie', $interval['categorie'] ?? null);
            })
            ->where('valoare_min', '<=', $max ?? PHP_INT_MAX)
            ->where(function ($query) use ($min) {
                $query->whereNull('valoare_max')
                    ->orWhere('valoare_max', '>=', $min);
            })
            ->exists();

        if ($overlapExists) {
            return ['valoare_min' => 'Exista deja un interval activ care se suprapune cu acesta.'];
        }

        return null;
    }

    protected function formatProdusProspectareOption(ProdusProspectare $produs): array
    {
        $label = $produs->denumire;
        if ($produs->cod) {
            $label .= ' (' . $produs->cod . ')';
        }

        return [
            'id' => $produs->id,
            'label' => $label,
            'denumire' => $produs->denumire,
            'cod' => $produs->cod,
        ];
    }

    protected function syncAmputatii(OfertaProspectare $oferta, array $amputatii): void
    {
        $ids = collect($amputatii)->pluck('id')->filter()->all();
        if (count($ids)) {
            $oferta->amputatii()->whereNotIn('id', $ids)->delete();
        } else {
            $oferta->amputatii()->delete();
        }

        $primaAmputatie = null;

        foreach ($amputatii as $amputatie) {
            $parteAmputata = trim((string) ($amputatie['parte_amputata'] ?? ''));
            $tipAmputatie = trim((string) ($amputatie['amputatie'] ?? ''));

            if ($parteAmputata === '' && $tipAmputatie === '') {
                continue;
            }

            $model = !empty($amputatie['id'])
                ? $oferta->amputatii()->where('id', $amputatie['id'])->first()
                : new OfertaProspectareAmputatie(['oferta_prospectare_id' => $oferta->id]);

            if (!$model) {
                $model = new OfertaProspectareAmputatie(['oferta_prospectare_id' => $oferta->id]);
            }

            $model->fill([
                'oferta_prospectare_id' => $oferta->id,
                'parte_amputata' => $parteAmputata ?: null,
                'amputatie' => $tipAmputatie ?: null,
            ]);
            $model->save();

            $primaAmputatie ??= $model;
        }

        $oferta->forceFill([
            'parte_amputata' => $primaAmputatie?->parte_amputata,
            'amputatie' => $primaAmputatie?->amputatie,
            'cauza_amputatiei' => null,
            'descriere_amputatie' => null,
        ])->save();
    }

    protected function syncLinii(OfertaProspectare $oferta, array $linii): void
    {
        $ids = collect($linii)->pluck('id')->filter()->all();
        if (count($ids)) {
            $oferta->linii()->whereNotIn('id', $ids)->delete();
        } else {
            $oferta->linii()->delete();
        }

        foreach ($linii as $linie) {
            $produs = null;
            if (!empty($linie['produs_prospectare_id'])) {
                $produs = ProdusProspectare::find($linie['produs_prospectare_id']);
            } elseif (!empty($linie['denumire_produs'])) {
                $produs = ProdusProspectare::where('denumire', $linie['denumire_produs'])->first();
            }

            $denumire = trim((string) ($linie['denumire_produs'] ?? ''));
            if ($denumire === '' && $produs) {
                $denumire = $produs->denumire;
            }
            if ($denumire === '') {
                if (!empty($linie['id'])) {
                    $oferta->linii()->where('id', $linie['id'])->delete();
                }

                continue;
            }

            $model = !empty($linie['id'])
                ? $oferta->linii()->where('id', $linie['id'])->first()
                : new OfertaProspectareLinie(['oferta_prospectare_id' => $oferta->id]);

            if (!$model) {
                $model = new OfertaProspectareLinie(['oferta_prospectare_id' => $oferta->id]);
            }

            $model->fill([
                'oferta_prospectare_id' => $oferta->id,
                'produs_prospectare_id' => $produs?->id,
                'denumire_produs' => $denumire,
                'descriere' => null,
                'cantitate' => 1,
                'pret_unitar' => 0,
                'valoare_linie' => 0,
            ]);
            $model->save();
        }
    }

    protected function syncClientProspectareDinOferta(OfertaProspectare $oferta): void
    {
        if (!$oferta->nume_client) {
            return;
        }

        $payload = [
            'nume' => $oferta->nume_client,
            'telefon' => $oferta->telefon,
            'email' => $oferta->email,
            'localitate' => $oferta->localitate,
            'judet' => $oferta->judet,
            'sursa' => $oferta->sursa,
            'activ' => true,
        ];

        if ($oferta->client_prospectare_id) {
            ClientProspectare::where('id', $oferta->client_prospectare_id)->update($payload);

            return;
        }

        $client = null;
        if ($oferta->telefon || $oferta->email) {
            $client = ClientProspectare::query()
                ->when($oferta->telefon, fn ($query) => $query->where('telefon', $oferta->telefon))
                ->when(!$oferta->telefon && $oferta->email, fn ($query) => $query->where('email', $oferta->email))
                ->first();
        }

        if ($client) {
            $client->update($payload);
        } else {
            $client = ClientProspectare::create($payload);
        }

        $oferta->forceFill(['client_prospectare_id' => $client->id])->save();
    }

    protected function syncVariante(OfertaProspectare $oferta, array $variante): void
    {
        $ids = collect($variante)->pluck('id')->filter()->all();
        if (count($ids)) {
            $oferta->variante()->whereNotIn('id', $ids)->get()->each(function (OfertaProspectareVarianta $varianta) {
                $varianta->componente()->delete();
                $varianta->delete();
            });
        } else {
            $oferta->variante()->get()->each(function (OfertaProspectareVarianta $varianta) {
                $varianta->componente()->delete();
                $varianta->delete();
            });
        }

        foreach ($variante as $index => $varianta) {
            $componentIds = collect($varianta['selected_component_ids'] ?? [])->filter()->map(fn ($id) => (int) $id)->unique()->values();
            $configurator = !empty($varianta['configurator_id'])
                ? ProspectareConfigurator::find($varianta['configurator_id'])
                : null;
            $titlu = trim((string) ($varianta['titlu'] ?? ''));

            if (!$configurator && $titlu === '' && $componentIds->isEmpty()) {
                if (!empty($varianta['id'])) {
                    $oferta->variante()->where('id', $varianta['id'])->get()->each(function (OfertaProspectareVarianta $model) {
                        $model->componente()->delete();
                        $model->delete();
                    });
                }

                continue;
            }

            $model = !empty($varianta['id'])
                ? $oferta->variante()->where('id', $varianta['id'])->first()
                : new OfertaProspectareVarianta(['oferta_prospectare_id' => $oferta->id]);

            if (!$model) {
                $model = new OfertaProspectareVarianta(['oferta_prospectare_id' => $oferta->id]);
            }

            $categorie = trim((string) ($varianta['categorie'] ?? '')) ?: ($configurator?->categorie);

            $model->fill([
                'oferta_prospectare_id' => $oferta->id,
                'configurator_id' => $configurator?->id,
                'titlu' => $titlu ?: ('Varianta ' . ($index + 1)),
                'configurator_denumire' => $configurator?->denumire,
                'categorie' => $categorie ?: null,
                'total_manual' => isset($varianta['total_manual']) && $varianta['total_manual'] !== '' ? (int) $varianta['total_manual'] : null,
                'discount_tip' => $varianta['discount_tip'] ?? 'valoare',
                'discount_valoare' => (int) ($varianta['discount_valoare'] ?? 0),
                'ordine' => $index,
            ]);
            $model->save();
            $model->componente()->delete();

            $components = ProspectareConfiguratorComponenta::whereIn('id', $componentIds->all())->get()->keyBy('id');
            foreach ($componentIds as $componentId) {
                $componenta = $components->get($componentId);
                if (!$componenta) {
                    continue;
                }

                $model->componente()->create([
                    'componenta_id' => $componenta->id,
                    'denumire' => $componenta->denumire,
                    'producator' => $componenta->producator,
                    'pret' => (int) $componenta->pret,
                ]);
            }
        }
    }

    protected function authorizeOfferAccess(Request $request, OfertaProspectare $oferta): void
    {
        if ($this->canViewAll($request->user()) || (int) $oferta->user_emitent_id === (int) $request->user()->id) {
            return;
        }

        abort(403);
    }

    protected function authorizeApproval(Request $request): void
    {
        if (!$this->canApprove($request->user())) {
            abort(403);
        }
    }

    protected function canViewAll(User $user): bool
    {
        return in_array($user->id, [1, 2], true) || $user->hasAnyRole(['prospectare.view_all', 'prospectare.edit']);
    }

    protected function canApprove(User $user): bool
    {
        return in_array($user->id, [1, 2], true) || $user->hasRole('prospectare.edit');
    }

    protected function ensureApproved(OfertaProspectare $oferta): void
    {
        if ($oferta->status_aprobare !== OfertaProspectare::APROBARE_APROBATA) {
            abort(403, 'Oferta trebuie aprobata intern inainte de trimitere.');
        }
    }

    protected function notifyAdmins(OfertaProspectare $oferta, string $subiect): void
    {
        $emails = User::whereIn('id', [1, 2])
            ->orWhereHas('roles', fn ($query) => $query->where('nume', 'prospectare.edit'))
            ->pluck('email')
            ->filter()
            ->unique()
            ->values();

        if ($emails->isEmpty()) {
            return;
        }

        Mail::to($emails->all())->send(new OfertaProspectareAdminNotification($oferta, $subiect));
    }

    protected function markSent(OfertaProspectare $oferta, string $canal, ?string $destinatar, ?string $mesaj): void
    {
        $oferta->trimiteri()->create([
            'user_id' => auth()->id(),
            'canal' => $canal,
            'destinatar' => $destinatar,
            'status' => 'trimis',
            'mesaj' => $mesaj,
        ]);

        $oferta->update([
            'status_client' => $oferta->status_client === OfertaProspectare::CLIENT_NESTRIMISA
                ? OfertaProspectare::CLIENT_TRIMISA
                : $oferta->status_client,
            'trimisa_la' => $oferta->trimisa_la ?: now(),
        ]);
    }

    protected function defaultClientMessage(OfertaProspectare $oferta): string
    {
        return 'Buna ziua, va transmitem oferta Theranova #' . $oferta->id
            . ' in valoare de ' . number_format((int) $oferta->valoare_totala, 0, ',', '.')
            . ' lei, valabila pana la ' . optional($oferta->valabila_pana_la)->format('d.m.Y') . '.';
    }

    protected function normalizePhoneForWhatsapp(string $telefon): string
    {
        $digits = preg_replace('/\D+/', '', $telefon) ?: '';
        if (str_starts_with($digits, '0')) {
            return '40' . substr($digits, 1);
        }

        return $digits;
    }

    protected function hasMedicalData(OfertaProspectare $oferta): bool
    {
        return collect([
            $oferta->greutate,
            $oferta->amputatii()->exists() ? 'amputatii' : null,
            $oferta->parte_amputata,
            $oferta->amputatie,
            $oferta->nivel_de_activitate,
            $oferta->a_mai_purtat_proteza,
        ])->filter(fn ($value) => $value !== null && $value !== '')->isNotEmpty();
    }

    protected function surseProspectare(): array
    {
        return ['Site', 'Whatsapp', 'Facebook', 'Recomandare'];
    }

    protected function judeteRomania(): array
    {
        return [
            'Alba', 'Arad', 'Arges', 'Bacau', 'Bihor', 'Bistrita-Nasaud', 'Botosani', 'Brasov',
            'Braila', 'Bucuresti', 'Buzau', 'Caras-Severin', 'Calarasi', 'Cluj', 'Constanta',
            'Covasna', 'Dambovita', 'Dolj', 'Galati', 'Giurgiu', 'Gorj', 'Harghita', 'Hunedoara',
            'Ialomita', 'Iasi', 'Ilfov', 'Maramures', 'Mehedinti', 'Mures', 'Neamt', 'Olt',
            'Prahova', 'Satu Mare', 'Salaj', 'Sibiu', 'Suceava', 'Teleorman', 'Timis', 'Tulcea',
            'Vaslui', 'Valcea', 'Vrancea',
        ];
    }

    protected function syncTipLucrareSolicitataId(FisaCaz $fisaCaz): void
    {
        if (!Schema::hasTable('lucrari') || !Schema::hasColumn('fise_caz', 'tip_lucrare_solicitata_id')) {
            return;
        }

        app(BonusCalculatorService::class)->rezolvaLucrarePentruFisa($fisaCaz, $fisaCaz->tip_lucrare_solicitata);
    }
}
