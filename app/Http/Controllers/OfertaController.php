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
        $request->session()->forget('ofertaReturnUrl');

        $oferte = Oferta::latest()->simplePaginate(25);

        return view('oferte.index', compact('oferte'));
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
        $oferta = $fisaCaz->oferte()->save(Oferta::make($request->except(['fisier', 'date', 'incasari'])));

        if ($request->incasari){
            foreach($request->incasari as $incasare) {
                $oferta->incasari()->create($incasare);
            }
        }

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
        $oferta->update($request->except(['fisier', 'date', 'incasari']));

        // Stergerea incasarilor ce nu mai sunt in array: array_column scoate doar coloana de id-uri, array_filter elimina din array valorile null (fara id)
        Incasare::where('oferta_id', $oferta->id)->whereNotIn('id', array_filter(array_column(($request->comenziComponente ?? []) , 'id')))->delete();
        // Adaugarea/modificarea incasarilor din array
        foreach(($request->incasari ?? []) as $incasare) {
            Incasare::updateOrCreate(
                [
                    'id' => $incasare['id']
                ],
                [
                    'oferta_id' => $incasare['oferta_id'],
                    'suma' => $incasare['suma'],
                    'data' => $incasare['data'],
                ]
            );
        }

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
                'pret' => 'required|numeric|between:1,999999',
                'observatii' => 'nullable|max:2000',
                'acceptata' => '',
                'fisier' => [
                    File::types(['pdf', 'jpg'])
                        // ->min(1024)
                        ->max(30 * 1024),
                ],
                'contract_nr' => 'nullable|max:200',
                'contract_data' => '',

                'incasari.*.suma' => 'required|numeric|between:1,999999',
                'incasari.*.data' => 'required|date',
            ],
            [
                'incasari.*.suma.required' => 'Câmpul Suma pentru incasarea :position este necesar.',
                'incasari.*.suma.integer' => 'Câmpul Suma pentru incasarea :position trebuie să fie un număr întreg.',
                'incasari.*.suma.between' => 'Câmpul Suma pentru incasarea :position trebuie să fie între 1 și 999.',
                'incasari.*.data' => 'Câmpul Data pentru incasarea :position este necesar.',
            ]
        );
    }
}
