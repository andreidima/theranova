<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Storage;

use App\Models\Comanda;
use App\Models\FisaCaz;
use App\Models\ComandaComponenta;
use App\Models\Fisier;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ComandaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $request->session()->forget('comandaReturnUrl');

        // $comenzi = Comanda::latest()->simplePaginate(25);

        // return view('comenziComponente.toate.index', compact('comenzi'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, FisaCaz $fisaCaz)
    {
        $request->session()->get('comandaReturnUrl') ?? $request->session()->put('comandaReturnUrl', url()->previous());

        $comanda = new Comanda;
        $comanda->data = Carbon::now();

        return view('comenzi.create', compact('fisaCaz', 'comanda'));
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

        $comanda = new Comanda;
        $comanda->fisa_caz_id = $fisaCaz->id;
        $comanda->data = $request->data;
        $comanda->sosita = $request->sosita;
        $comanda->save();

        if ($request->comenziComponente){
            foreach($request->comenziComponente as $componenta) {
                $comandaComponenta = ComandaComponenta::make($componenta);
                $comandaComponenta->comanda_id = $comanda->id;
                $comandaComponenta->save();
            }
        }

        // Fisier Comanda
        if ($request->file('fisier')) {
            $fisier = $request->file('fisier');
            $numeFisier = $fisier->getClientOriginalName();
            $cale = 'fiseCaz/' . $fisaCaz->id . '/comenzi' . '/' . $comanda->id;
            if (Storage::exists($cale . '/' . $numeFisier)){
                return back()->with('error', 'Există deja un fișier cu numele „' . $numeFisier . '”. Redenumește fișierul și încearcă din nou.');
            }
            try {
                Storage::putFileAs($cale, $fisier, $numeFisier);
                $fisier = new Fisier;
                $fisier->referinta = 4;
                $fisier->referinta_id = $comanda->id;
                $fisier->cale = $cale;
                $fisier->nume = $numeFisier;
                $fisier->save();
            } catch (Exception $e) {
                return back()->with('error', 'Fișierul nu a putut fi încărcat.');
            }
        }

        if ($comanda->sosita == '1') {
            $this->trimitePrinEmailCatreUtilizator($fisaCaz, 'Comanda sosită', null, $comanda);
        }

        return redirect($request->session()->get('comandaReturnUrl') ?? ('/fise-caz'))->with('status', 'Comanda de componente pentru pacientul „' . ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume) . '” a fost adăugată cu succes!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comanda  $comanda
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Comanda $comanda)
    {
        $request->session()->get('comandaReturnUrl') ?? $request->session()->put('comandaReturnUrl', url()->previous());

        return view('comenzi.show', compact('comanda'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comanda  $comanda
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, FisaCaz $fisaCaz, Comanda $comanda)
    {
        $request->session()->get('comandaReturnUrl') ?? $request->session()->put('comandaReturnUrl', url()->previous());

        return view('comenzi.edit', compact('comanda'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comanda  $comanda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FisaCaz $fisaCaz, Comanda $comanda)
    {
        $this->validateRequest($request);

        // Fisier Comanda
        // Daca exista fisier in request, se sterge vechiul fisier si se salveaza cel de acum
        if ($request->file('fisier')) {
            // stergere fisier vechi
            if ($comanda->fisiere->count() > 0){
                Storage::delete($comanda->fisiere()->first()->cale . '/' . $comanda->fisiere()->first()->nume);
            }
            $fisier = $request->file('fisier');
            $numeFisier = $fisier->getClientOriginalName();
            $cale = 'fiseCaz/' . $fisaCaz->id . '/comenzi' . '/' . $comanda->id;
            if (Storage::exists($cale . '/' . $numeFisier)){
                return back()->with('error', 'Există deja un fișier cu numele „' . $numeFisier . '”. Redenumește fișierul și încearcă din nou.');
            }
            try {
                // se salveaza fisierul pe disk
                Storage::putFileAs($cale, $fisier, $numeFisier);
                if ($comanda->fisiere->count() > 0){ // daca exista deja inregistrare cu un fisier, se face update in baza de data
                    $comanda->fisiere->first()->update(['nume' => $numeFisier]);
                } else { // daca nu exista deja inregistrare in baza de date, se creaza o inregistrare noua
                    $fisier = new Fisier;
                    $fisier->referinta = 4;
                    $fisier->referinta_id = $comanda->id;
                    $fisier->cale = $cale;
                    $fisier->nume = $numeFisier;
                    $fisier->save();
                }
            } catch (Exception $e) {
                return back()->with('error', 'Fișierul nu a putut fi încărcat.');
            }
        }

        $comanda->update([
            'data' => $request->data,
            'sosita' => $request->sosita,
        ]);

        // Stergerea componentelor ce nu mai sunt in array: array_column scoate doar coloana de id-uri, array_filter elimina din array valorile null (fara id)
        ComandaComponenta::where('comanda_id', $comanda->id)->whereNotIn('id', array_filter(array_column(($request->comenziComponente ?? []) , 'id')))->delete();
        // Adaugarea/modificarea comenzilorComponente din array
        foreach(($request->comenziComponente ?? []) as $componenta) {
            ComandaComponenta::updateOrCreate(
                [
                    'id' => $componenta['id']
                ],
                [
                    'comanda_id' => $componenta['comanda_id'],
                    'producator' => $componenta['producator'],
                    'cod_produs' => $componenta['cod_produs'],
                    'bucati' => $componenta['bucati'],
                ]
            );
        }

        if ($comanda->wasChanged('sosita') && ($comanda->sosita == '1')) {
            $this->trimitePrinEmailCatreUtilizator($fisaCaz, 'Comanda sosită', null, $comanda);
        }

        return redirect($request->session()->get('comandaReturnUrl') ?? ('/fise-caz'))->with('status', 'Comanda de componente pentru pacientul „' . ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume) . '” a fost modificată cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comanda  $oferta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, FisaCaz $fisaCaz, Comanda $comanda)
    {
        if (!auth()->user()->hasRole("stergere")){
            return back()->with('error', 'Nu ai drepturi de ștergere.');
        }

        $comanda->componente()->delete();
        $comanda->delete();

        // Se sterg fisierele
        if ($comanda->fisiere->count() > 0){
            foreach ($comanda->fisiere as $fisier);
            Storage::delete($fisier->cale . '/' . $fisier->nume);
        }

        // Se verifica toate directoarele pana la radacina, si daca sunt goale se sterg
        if (empty(Storage::allFiles('fiseCaz/' . $fisaCaz->id . '/comenzi/' . $comanda->id))){
            Storage::deleteDirectory('fiseCaz/' . $fisaCaz->id . '/comenzi/' . $comanda->id);
            if (empty(Storage::allFiles('fiseCaz/' . $fisaCaz->id . '/comenzi/'))){
                Storage::deleteDirectory('fiseCaz/' . $fisaCaz->id . '/comenzi/');
                if (empty(Storage::allFiles('fiseCaz/' . $fisaCaz->id))){
                    Storage::deleteDirectory('fiseCaz/' . $fisaCaz->id);
                }
            }
        }


        return back()->with('status', 'Comanda pentru pacientul „' . ($comanda->fisaCaz->pacient->nume ?? '') . ' ' . ($comanda->fisaCaz->pacient->prenume) . '” a fost ștearsă cu succes!');
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
                'id' => '',
                'fisier' => ['nullable',
                    File::types(['pdf', 'jpg'])
                        ->max(10 * 1024),
                    ],
                // 'comenziComponente' => 'required',
                // 'comenziComponente.*.fisa_caz_id' => 'required', // I think that should be deleted
                'comenziComponente.*.producator' => 'required|max:200',
                'comenziComponente.*.cod_produs' => 'required|max:200',
                'comenziComponente.*.bucati' => 'required|integer|between:1,999',
            ],
            [
                'comenziComponente.required' => 'Este obligatoriu să fie adăugată minim o componentă.',
                'comenziComponente.*.producator.required' => 'Câmpul Producător pentru componenta :position este necesar.',
                'comenziComponente.*.producator.max' => 'Câmpul Producător pentru componenta :position poate avea maxim 200 de caractere.',
                'comenziComponente.*.cod_produs.required' => 'Câmpul Cod produs pentru componenta :position este necesar.',
                'comenziComponente.*.cod_produs.max' => 'Câmpul Cod produs pentru componenta :position poate avea maxim 200 de caractere.',
                'comenziComponente.*.bucati.required' => 'Câmpul Bucăți pentru componenta :position este necesar.',
                'comenziComponente.*.bucati.integer' => 'Câmpul Bucăți pentru componenta :position trebuie să fie un număr întreg.',
                'comenziComponente.*.bucati.between' => 'Câmpul Bucăți pentru componenta :position trebuie să fie între 1 și 999.',
            ]
        );
    }

    public function exportPdf(Request $request, FisaCaz $fisaCaz, Comanda $comanda)
    {
        $pdf = \PDF::loadView('comenzi.export.comandaComponentePdf', compact('comanda'))
            ->setPaper('a4', 'portrait');
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream();
    }

    public function trimitePrinEmailCatreUtilizator(FisaCaz $fisaCaz, $tipEmail=null, $mesaj, $comanda)
    {
        // dd('here');
        $validator = Validator::make(
            [
                'email_vanzari' => $fisaCaz->userVanzari->email ?? '',
                'email_comercial' => $fisaCaz->userComercial->email ?? '',
                'email_tehnic' => $fisaCaz->userTehnic->email ?? '',
            ],
            [
                'email_vanzari' => 'email:rfc,dns',
                'email_comercial' => 'email:rfc,dns',
                'email_tehnic' => 'email:rfc,dns'
            ]);
        if ($validator->fails()) {
            return;
        }

        $adreseEmail = [];
        ($fisaCaz->userVanzari->email ?? null) ? array_push($adreseEmail, $fisaCaz->userVanzari->email) : '';
        ($fisaCaz->userComercial->email ?? null) ? array_push($adreseEmail, $fisaCaz->userComercial->email) : '';
        ($fisaCaz->userTehnic->email ?? null) ? array_push($adreseEmail, $fisaCaz->userTehnic->email) : '';

        Mail::to($adreseEmail)
            ->cc(['danatudorache@theranova.ro', 'adrianples@theranova.ro'])
            ->send(new \App\Mail\FisaCaz($fisaCaz, $tipEmail, $mesaj, $comanda));

        $mesajTrimisEmail = \App\Models\MesajTrimisEmail::create([
            'referinta' => 2, // Comanda
            'referinta_id' => $comanda->id,
            'referinta2' => null, // User
            'referinta2_id' => null,
            'tip' => 4, // comanda Sosita
            'mesaj' => '',
            'email' => implode(', ', $adreseEmail)
        ]);
    }
}
