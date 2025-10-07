<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Storage;

use App\Models\Oferta;
use App\Models\FisaCaz;
use App\Models\Fisier;
use App\Models\Incasare;

class OfertaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $request->session()->forget('ofertaReturnUrl');

        // $oferte = Oferta::latest()->simplePaginate(25);

        // return view('oferte.index', compact('oferte'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->session()->get('ofertaReturnUrl') ?? $request->session()->put('ofertaReturnUrl', url()->previous());

        return view('oferte.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FisaCaz $fisaCaz)
    {
        $this->validateRequest($request);
        $oferta = $fisaCaz->oferte()->save(Oferta::make($request->except(['fisier', 'date', 'incasari', 'decizii_cas'])));

        $this->syncIncasari($oferta, $request->decizii_cas ?? [], Incasare::TIP_DECIZIE_CAS);
        $this->syncIncasari($oferta, $request->incasari ?? [], Incasare::TIP_INCASARE);

        if ($request->file('fisier')) {
            $fisier = $request->file('fisier');
            $numeFisier = $fisier->getClientOriginalName();
            $cale = 'fiseCaz/' . $fisaCaz->id . '/oferte/' . $oferta->id;

            if (Storage::exists($cale . '/' . $numeFisier)){
                return back()->with('error', 'Există deja un fișier cu numele „' . $numeFisier . '”. Redenumește fișierul și încearcă din nou.');
            }

            try {
                Storage::putFileAs($cale, $fisier, $numeFisier);
                $fisier = new Fisier;
                $fisier->referinta = 1;
                $fisier->referinta_id = $oferta->id;
                $fisier->cale = $cale;
                $fisier->nume = $numeFisier;
                $fisier->save();
            } catch (Exception $e) {
                return back()->with('error', 'Fișierul nu a putut fi încărcat.');
            }
        }

        return redirect($request->session()->get('ofertaReturnUrl') ?? ('/fise-caz'))->with('status', 'Oferta pentru pacientul „' . ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume) . '” a fost adăugată cu succes!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Oferta  $oferta
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Oferta $oferta)
    {
        $request->session()->get('ofertaReturnUrl') ?? $request->session()->put('ofertaReturnUrl', url()->previous());

        return view('oferte.show', compact('oferta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Oferta  $oferta
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, FisaCaz $fisaCaz, Oferta $oferta)
    {
        $request->session()->get('ofertaReturnUrl') ?? $request->session()->put('ofertaReturnUrl', url()->previous());

        return view('oferte.edit', compact('oferta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Oferta  $oferta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FisaCaz $fisaCaz, Oferta $oferta)
    {
        $this->validateRequest($request);
        $oferta->update($request->except(['fisier', 'date', 'incasari', 'decizii_cas']));

        $idsDePastrat = array_filter(array_column(($request->incasari ?? []), 'id'));
        $idsDePastrat = array_merge($idsDePastrat, array_filter(array_column(($request->decizii_cas ?? []), 'id')));

        if (count($idsDePastrat)) {
            Incasare::where('oferta_id', $oferta->id)->whereNotIn('id', $idsDePastrat)->delete();
        } else {
            Incasare::where('oferta_id', $oferta->id)->delete();
        }

        $this->syncIncasari($oferta, $request->decizii_cas ?? [], Incasare::TIP_DECIZIE_CAS);
        $this->syncIncasari($oferta, $request->incasari ?? [], Incasare::TIP_INCASARE);

        // Daca exista fisier in request, se sterge vechiul fisier si se salveaza cel de acum
        if ($request->file('fisier')) {
            // stergere fisier vechi
            if ($oferta->fisiere->count() > 0){
                Storage::delete($oferta->fisiere()->first()->cale . '/' . $oferta->fisiere()->first()->nume);
            }

            $fisier = $request->file('fisier');
            $numeFisier = $fisier->getClientOriginalName();
            $cale = 'fiseCaz/' . $fisaCaz->id . '/oferte/' . $oferta->id;

            if (Storage::exists($cale . '/' . $numeFisier)){
                return back()->with('error', 'Există deja un fișier cu numele „' . $numeFisier . '”. Redenumește fișierul și încearcă din nou.');
            }

            try {
                Storage::putFileAs($cale, $fisier, $numeFisier);

                // Daca exista fisier in baza de date, se actualizeaza.
                // Daca nu, se creaza unul nou
                if ($oferta->fisiere->count() > 0){
                    $oferta->fisiere()->first()->update(['nume' => $numeFisier]);
                } else {
                    $fisier = new Fisier;
                    $fisier->referinta = 1;
                    $fisier->referinta_id = $oferta->id;
                    $fisier->cale = $cale;
                    $fisier->nume = $numeFisier;
                    $fisier->save();
                }
            } catch (Exception $e) {
                return back()->with('error', 'Fișierul nu a putut fi încărcat.');
            }
        }

        return redirect($request->session()->get('ofertaReturnUrl') ?? ('/oferte'))->with('status', 'Oferta pentru pacientul „' . ($oferta->fisaCaz->pacient->nume ?? '') . ' ' . ($oferta->fisaCaz->pacient->prenume) . '” a fost modificată cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Oferta  $oferta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, FisaCaz $fisaCaz, Oferta $oferta)
    {
        if (!auth()->user()->hasRole("stergere")){
            return back()->with('error', 'Nu ai drepturi de ștergere.');
        }

        $oferta->incasari()->delete();
        $oferta->delete();

        // Se sterge fisierul
        if ($oferta->fisiere->count() > 0){
            Storage::delete($oferta->fisiere()->first()->cale . '/' . $oferta->fisiere()->first()->nume);
        }

        // Se verifica toate directoarele pana la radacina, si daca sunt goale se sterg
        if (empty(Storage::allFiles('fiseCaz/' . $fisaCaz->id . '/oferte/' . $oferta->id))){
            Storage::deleteDirectory('fiseCaz/' . $fisaCaz->id . '/oferte/' . $oferta->id);
            if (empty(Storage::allFiles('fiseCaz/' . $fisaCaz->id . '/oferte/'))){
                Storage::deleteDirectory('fiseCaz/' . $fisaCaz->id . '/oferte/');
                if (empty(Storage::allFiles('fiseCaz/' . $fisaCaz->id))){
                    Storage::deleteDirectory('fiseCaz/' . $fisaCaz->id);
                }
            }
        }


        return back()->with('status', 'Oferta pentru pacientul „' . ($oferta->fisaCaz->pacient->nume ?? '') . ' ' . ($oferta->fisaCaz->pacient->prenume) . '” a fost ștearsă cu succes!');
    }

    protected function syncIncasari(Oferta $oferta, array $incasari, string $tip): void
    {
        foreach ($incasari as $incasare) {
            $id = $incasare['id'] ?? null;

            $payload = [
                'oferta_id' => $oferta->id,
                'suma' => $incasare['suma'] ?? null,
                'data' => $incasare['data'] ?? null,
                'nr_data' => $incasare['nr_data'] ?? null,
                'observatii' => $incasare['observatii'] ?? null,
                'data_inregistrare' => $incasare['data_inregistrare'] ?? null,
                'data_validare' => $incasare['data_validare'] ?? null,
                'tip' => $tip,
            ];

            if ($id) {
                $model = Incasare::where('id', $id)
                    ->where('oferta_id', $oferta->id)
                    ->first();

                if ($model) {
                    $model->fill($payload);
                    $model->save();

                    continue;
                }
            }

            $oferta->incasari()->create($payload);
        }
    }

    /**
     * Validate the request attributes.
     *
     * @return array
     */
    protected function validateRequest(Request $request)
    {
        // Se adauga userul doar la adaugare, iar la modificare nu se schimba
        // if ($request->isMethod('post')) {
        //     $request->request->add(['user_id' => $request->user()->id]);
        // }

        // if ($request->isMethod('post')) {
        //     $request->request->add(['cheie_unica' => uniqid()]);
        // }

        return $request->validate(
            [
                'obiect_contract' => 'required|max:500',
                'pret' => 'required|numeric|between:0,999999',
                'observatii' => 'nullable|max:2000',
                'acceptata' => 'required',
                'fisier' => [
                    File::types(['pdf', 'jpg'])
                        // ->min(1024)
                        ->max(10 * 1024),
                ],
                'contract_nr' => 'nullable|max:200',
                'contract_data' => '',

                'incasari.*.suma' => 'required|numeric|between:1,999999',
                'incasari.*.data' => ['required' , 'date', 'regex:/^(0?[1-9]|[12][0-9]|3[01])\.(0?[1-9]|1[0-2])\.(\d{4})$/'],
                'incasari.*.observatii' => 'nullable|max:5000',

                'decizii_cas.*.suma' => 'required|numeric|between:1,999999',
                'decizii_cas.*.data' => ['required' , 'date', 'regex:/^(0?[1-9]|[12][0-9]|3[01])\.(0?[1-9]|1[0-2])\.(\d{4})$/'],
                'decizii_cas.*.nr_data' => 'nullable|string|max:255',
                'decizii_cas.*.data_inregistrare' => ['required' , 'date', 'regex:/^(0?[1-9]|[12][0-9]|3[01])\.(0?[1-9]|1[0-2])\.(\d{4})$/'],
                'decizii_cas.*.data_validare' => ['nullable' , 'date', 'regex:/^(0?[1-9]|[12][0-9]|3[01])\.(0?[1-9]|1[0-2])\.(\d{4})$/'],
                'decizii_cas.*.observatii' => 'nullable|max:5000',
            ],
            [
                'fisier.uploaded' => 'Fișierul nu a putut fi încărcat - fie este prea mare (maxim 10 MB) sau altceva a întrerupt procesul.',

                'incasari.*.suma.required' => 'Câmpul Suma pentru incasarea :position este necesar.',
                'incasari.*.suma.integer' => 'Câmpul Suma pentru incasarea :position trebuie să fie un număr întreg.',
                'incasari.*.suma.between' => 'Câmpul Suma pentru incasarea :position trebuie să fie între 1 și 999.',
                'incasari.*.data.required' => 'Câmpul Data pentru incasarea :position este necesar.',
                'incasari.*.data.date' => 'Câmpul Data pentru incasarea :position nu există în calendar.',
                'incasari.*.data.regex' => 'Câmpul Data pentru incasarea :position nu este completat corect.',
                'incasari.*.observatii.max' => 'Câmpul Observații pentru incasarea :position trebuie să fie maxim 5000 de caractere.',

                'decizii_cas.*.suma.required' => 'Câmpul Suma pentru decizia CAS :position este necesar.',
                'decizii_cas.*.suma.integer' => 'Câmpul Suma pentru decizia CAS :position trebuie să fie un număr întreg.',
                'decizii_cas.*.suma.between' => 'Câmpul Suma pentru decizia CAS :position trebuie să fie între 1 și 999.',
                'decizii_cas.*.data.required' => 'Câmpul Data pentru decizia CAS :position este necesar.',
                'decizii_cas.*.data.date' => 'Câmpul Data pentru decizia CAS :position nu există în calendar.',
                'decizii_cas.*.data.regex' => 'Câmpul Data pentru decizia CAS :position nu este completat corect.',
                'decizii_cas.*.data_inregistrare.required' => 'Câmpul Data înregistrare pentru decizia CAS :position este necesar.',
                'decizii_cas.*.data_inregistrare.date' => 'Câmpul Data înregistrare pentru decizia CAS :position nu există în calendar.',
                'decizii_cas.*.data_inregistrare.regex' => 'Câmpul Data înregistrare pentru decizia CAS :position nu este completat corect.',
                'decizii_cas.*.nr_data.max' => 'Câmpul Decizie CAS nr/data pentru decizia CAS :position poate avea cel mult 255 de caractere.',
                'decizii_cas.*.data_validare.date' => 'Câmpul Data validare pentru decizia CAS :position nu există în calendar.',
                'decizii_cas.*.data_validare.regex' => 'Câmpul Data validare pentru decizia CAS :position nu este completat corect.',
                'decizii_cas.*.observatii.max' => 'Câmpul Observații pentru decizia CAS :position trebuie să fie maxim 5000 de caractere.',
            ]
        );
    }
}
