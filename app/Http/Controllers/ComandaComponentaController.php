<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ComandaComponenta;
use App\Models\FisaCaz;

class ComandaComponentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('comandaComponentaReturnUrl');

        $comenziComponente = ComandaComponenta::latest()->simplePaginate(25);

        return view('comenziComponente.index', compact('comenziComponente'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->session()->get('comandaComponentaReturnUrl') ?? $request->session()->put('comandaComponentaReturnUrl', url()->previous());

        return view('comenziComponente.create');
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
        $comandaComponenta = $fisaCaz->comenziComponente()->save(ComandaComponenta::make($request->except(['fisier'])));

        return redirect($request->session()->get('comandaComponentaReturnUrl') ?? ('/fise-caz'))->with('status', 'Comanda de componente pentru pacientul „' . ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume) . '” a fost adăugată cu succes!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ComandaComponenta  $comandaComponenta
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ComandaComponenta $comandaComponenta)
    {
        $request->session()->get('comandaComponentaReturnUrl') ?? $request->session()->put('comandaComponentaReturnUrl', url()->previous());

        return view('comenziComponente.show', compact('oferta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ComandaComponenta  $comandaComponenta
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, FisaCaz $fisaCaz, ComandaComponenta $comandaComponenta)
    {
        $request->session()->get('comandaComponentaReturnUrl') ?? $request->session()->put('comandaComponentaReturnUrl', url()->previous());

        return view('comenziComponente.edit', compact('oferta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ComandaComponenta  $comandaComponenta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FisaCaz $fisaCaz, ComandaComponenta $comandaComponenta)
    {
        $comandaComponenta->update($this->validateRequest($request));

        return redirect($request->session()->get('comandaComponentaReturnUrl') ?? ('/comenziComponente'))->with('status', 'Comanda de componente pentru pacientul „' . ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume) . '” a fost modificată cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ComandaComponenta  $comandaComponenta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, FisaCaz $fisaCaz, ComandaComponenta $comandaComponenta)
    {
        if (!auth()->user()->hasRole("stergere")){
            return back()->with('error', 'Nu ai drepturi de ștergere.');
        }

        $comandaComponenta->delete();

        return back()->with('status', 'Comanda de componente pentru pacientul „' . ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume) . '” a fost ștearsă cu succes!');
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
                'fisa_caz_id' => 'required',
                'producator' => 'required|max:200',
                'cod_produs' => 'required|max:200',
                'bucati' => 'required|numeric|between:1,999',
            ],
            [
            ]
        );
    }

    public function toateAdauga(Request $request, FisaCaz $fisaCaz)
    {
        $request->session()->get('comandaComponenteReturnUrl') ?? $request->session()->put('comandaComponenteReturnUrl', url()->previous());

        return view('comenziComponente.toate.create', compact('fisaCaz'));
    }

    public function postToateAdauga(Request $request, FisaCaz $fisaCaz)
    {
        $this->toateValidateRequest($request);

        foreach($request->comenziComponente as $componenta) {
            $comandaComponenta = new ComandaComponenta;
            $comandaComponenta->create($componenta);
        }

        return redirect($request->session()->get('comandaComponenteReturnUrl') ?? ('/fise-caz'))->with('status', 'Comanda de componente pentru pacientul „' . ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume) . '” a fost adăugată cu succes!');
    }

    public function toateModifica(Request $request, FisaCaz $fisaCaz)
    {
        $request->session()->get('comandaComponenteReturnUrl') ?? $request->session()->put('comandaComponenteReturnUrl', url()->previous());

        return view('comenziComponente.toate.edit', compact('fisaCaz'));
    }

    public function postToateModifica(Request $request, FisaCaz $fisaCaz)
    {
        $this->toateValidateRequest($request);

        // Stergerea comenzilorComponente ce nu mai sunt in array: array_column scoate doar coloana de id-uri, array_filter elimina din array valorile null (fara id)
        ComandaComponenta::where('fisa_caz_id', $fisaCaz->id)->whereNotIn('id', array_filter(array_column($request->comenziComponente , 'id')))->delete();

        // Adaugarea/modificarea comenzilorComponente din array
        foreach($request->comenziComponente as $componenta) {
            ComandaComponenta::updateOrCreate(
                [
                    'id' => $componenta['id']
                ],
                [
                    'fisa_caz_id' => $componenta['fisa_caz_id'],
                    'producator' => $componenta['producator'],
                    'cod_produs' => $componenta['cod_produs'],
                    'bucati' => $componenta['bucati'],
                ]
            );
        }

        return redirect($request->session()->get('comandaComponenteReturnUrl') ?? ('/fise-caz'))->with('status', 'Comanda de componente pentru pacientul „' . ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume) . '” a fost modificată cu succes!');
    }

    public function postToateSterge(Request $request, FisaCaz $fisaCaz)
    {
        $fisaCaz->comenziComponente()->delete();

        return redirect($request->session()->get('comandaComponenteReturnUrl') ?? ('/fise-caz'))->with('status', 'Comanda de componente pentru pacientul „' . ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume) . '” a fost ștearsă cu succes!');
    }

    public function toateExport(Request $request, FisaCaz $fisaCaz)
    {
        $pdf = \PDF::loadView('comenziComponente.toate.export.comandaComponentePdf', compact('fisaCaz'))
            ->setPaper('a4', 'portrait');
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream();
    }

    /**
     * Validate the request attributes.
     *
     * @return array
     */
    protected function toateValidateRequest(Request $request)
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
                'comenziComponente' => 'required',
                'comenziComponente.*.fisa_caz_id' => 'required',
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
}
