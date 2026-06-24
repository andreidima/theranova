<?php

namespace App\Http\Controllers;

use App\Mail\OfertaProspectareAdminNotification;
use App\Mail\OfertaProspectareClient;
use App\Models\Cerinta;
use App\Models\DataMedicala;
use App\Models\FisaCaz;
use App\Models\OfertaProspectare;
use App\Models\OfertaProspectareAmputatie;
use App\Models\OfertaProspectareLinie;
use App\Models\Pacient;
use App\Models\ProdusProspectare;
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

        $this->syncAmputatii($oferta, $request->input('amputatii', []));
        $this->syncLinii($oferta, $request->input('linii', []));
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

        $ofertaProspectare->load(['emitent', 'aprobator', 'amputatii', 'linii.produs', 'trimiteri.user', 'pacient', 'fisaCaz']);

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
        $this->syncAmputatii($ofertaProspectare, $request->input('amputatii', []));
        $this->syncLinii($ofertaProspectare, $request->input('linii', []));
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
        $ofertaProspectare->amputatii()->delete();
        $ofertaProspectare->trimiteri()->delete();
        $ofertaProspectare->delete();

        return redirect('/oferte-prospectare')->with('status', 'Oferta de prospectare a fost stearsa.');
    }

    public function submitForApproval(Request $request, OfertaProspectare $ofertaProspectare): RedirectResponse
    {
        $this->authorizeOfferAccess($request, $ofertaProspectare);

        if (!$ofertaProspectare->linii()->exists()) {
            return back()->with('error', 'Adauga cel putin un produs inainte de trimiterea la aprobare.');
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
        $ofertaProspectare->load(['emitent', 'aprobator', 'amputatii', 'linii']);

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

    public function produseIndex(Request $request): View
    {
        $produse = ProdusProspectare::query()
            ->when($request->search, fn ($query, $search) => $query->where('denumire', 'like', '%' . $search . '%'))
            ->orderByDesc('activ')
            ->orderBy('denumire')
            ->paginate(50)
            ->withQueryString();

        return view('oferteProspectare.produse', [
            'produse' => $produse,
            'search' => $request->search,
        ]);
    }

    public function produseStore(Request $request): RedirectResponse
    {
        $this->authorizeApproval($request);
        ProdusProspectare::create($this->validateProduct($request));

        return back()->with('status', 'Produsul a fost adaugat.');
    }

    public function produseUpdate(Request $request, ProdusProspectare $produs): RedirectResponse
    {
        $this->authorizeApproval($request);
        $produs->update($this->validateProduct($request));

        return back()->with('status', 'Produsul a fost modificat.');
    }

    public function produseDestroy(Request $request, ProdusProspectare $produs): RedirectResponse
    {
        $this->authorizeApproval($request);
        $produs->update(['activ' => false]);

        return back()->with('status', 'Produsul a fost dezactivat.');
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
            ->when($search, fn ($query) => $query->where('denumire', 'like', '%' . $search . '%'))
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
        $this->authorizeApproval($request);

        $produs = ProdusProspectare::create($this->validateProduct($request));

        return response()->json([
            'produs' => $this->formatProdusProspectareOption($produs),
        ], 201);
    }

    protected function formData(OfertaProspectare $oferta): array
    {
        $oferta->loadMissing(['amputatii', 'linii.produs']);

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

        return [
            'oferta' => $oferta,
            'amputatiiFormData' => $amputatiiFormData,
            'canManageProduseProspectare' => $this->canApprove(request()->user()),
        ];
    }

    protected function validateOffer(Request $request): array
    {
        return $request->validate([
            'nume_client' => 'required|max:255',
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
            'discount_aditional' => 'nullable|integer|min:0|max:1000000',
            'observatii_interne' => 'nullable|max:5000',
            'amputatii.*.id' => 'nullable|integer',
            'amputatii.*.parte_amputata' => 'nullable|max:100',
            'amputatii.*.amputatie' => 'nullable|max:100',
            'linii.*.id' => 'nullable|integer',
            'linii.*.produs_prospectare_id' => 'nullable|integer|exists:produse_prospectare,id',
            'linii.*.denumire_produs' => 'nullable|max:255',
            'linii.*.descriere' => 'nullable|max:5000',
            'linii.*.cantitate' => 'nullable|integer|min:1|max:999',
            'linii.*.pret_unitar' => 'nullable|integer|min:0|max:1000000',
        ]);
    }

    protected function offerPayload(array $validated): array
    {
        $payload = Arr::except($validated, ['amputatii', 'linii']);
        $payload['decontare_cas'] = (bool) ($payload['decontare_cas'] ?? false);
        $payload['buget_disponibil'] = $payload['decontare_cas'] ? ($payload['buget_disponibil'] ?? null) : null;
        $payload['discount_aditional'] = $payload['discount_aditional'] ?? 0;
        $payload['cauza_amputatiei'] = null;
        $payload['descriere_amputatie'] = null;

        return $payload;
    }

    protected function validateProduct(Request $request): array
    {
        return $request->validate([
            'denumire' => 'required|max:255',
            'cod' => 'nullable|max:100',
            'descriere' => 'nullable|max:5000',
            'pret_end_user' => 'required|integer|min:0|max:1000000',
            'activ' => 'nullable|boolean',
            'observatii' => 'nullable|max:5000',
        ]);
    }

    protected function formatProdusProspectareOption(ProdusProspectare $produs): array
    {
        return [
            'id' => $produs->id,
            'label' => $produs->denumire . ' (' . number_format((int) $produs->pret_end_user, 0, ',', '.') . ' lei)',
            'denumire' => $produs->denumire,
            'descriere' => $produs->descriere,
            'pret_end_user' => (int) $produs->pret_end_user,
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

            $cantitate = max(1, (int) ($linie['cantitate'] ?? 1));
            $pret = (int) ($linie['pret_unitar'] ?? 0);
            if ($pret <= 0 && $produs) {
                $pret = (int) $produs->pret_end_user;
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
                'descriere' => trim((string) ($linie['descriere'] ?? '')) ?: null,
                'cantitate' => $cantitate,
                'pret_unitar' => $pret,
                'valoare_linie' => $cantitate * $pret,
            ]);
            $model->save();
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

    protected function syncTipLucrareSolicitataId(FisaCaz $fisaCaz): void
    {
        if (!Schema::hasTable('lucrari') || !Schema::hasColumn('fise_caz', 'tip_lucrare_solicitata_id')) {
            return;
        }

        app(BonusCalculatorService::class)->rezolvaLucrarePentruFisa($fisaCaz, $fisaCaz->tip_lucrare_solicitata);
    }
}
