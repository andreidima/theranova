<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RecoltareSange;
use App\Models\RecoltareSangeProdus;
use App\Models\RecoltareSangeGrupa;

class RecoltareSangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('recoltareSangeReturnUrl');

        $searchCod = $request->searchCod;

        $query = RecoltareSange::
            // with('alerte')
            when($searchCod, function ($query, $searchCod) {
                return $query->where('cod', 'like', '%' . $searchCod . '%');
            })
            ->latest();

        $recoltariSange = $query->simplePaginate(25);

        return view('recoltariSange.index', compact('recoltariSange', 'searchCod'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->session()->get('recoltareSangeReturnUrl') ?? $request->session()->put('recoltareSangeReturnUrl', url()->previous());

        $recoltariSangeProduse = RecoltareSangeProdus::get();
        $recoltariSangeGrupe = RecoltareSangeGrupa::get();

        return view('recoltariSange.create', compact('recoltariSangeProduse', 'recoltariSangeGrupe'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateRequest($request);

        for ($i = 1; $i <= $request->nrPungi; $i++){
            $recoltareSange = new RecoltareSange;
            $recoltareSange->recoltari_sange_produs_id = $request->recoltari_sange_produs_id;
            $recoltareSange->recoltari_sange_grupa_id = $request->recoltari_sange_grupa_id;
            $recoltareSange->data = $request->data;
            $recoltareSange->cod = $request->cod;
            $recoltareSange->tip = $request->tip;
            $recoltareSange->cantitate = $request->cantitatiPungiSange[$i];
            $recoltareSange->save();
        }

        return redirect($request->session()->get('recoltareSangeReturnUrl') ?? ('/recoltari-sange'))->with('status', 'Recoltarea de sânge „' . ($recoltareSange->cod ?? '') . '” a fost adăugată cu succes!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RecoltareSange  $recoltareSange
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, RecoltareSange $recoltareSange)
    {
        $request->session()->get('recoltareSangeReturnUrl') ?? $request->session()->put('recoltareSangeReturnUrl', url()->previous());

        return view('recoltariSange.show', compact('recoltareSange'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RecoltareSange  $recoltareSange
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, RecoltareSange $recoltareSange)
    {
        $request->session()->get('recoltareSangeReturnUrl') ?? $request->session()->put('recoltareSangeReturnUrl', url()->previous());

        $recoltariSangeProduse = RecoltareSangeProdus::get();
        $recoltariSangeGrupe = RecoltareSangeGrupa::get();
// dd($recoltareSange);
        return view('recoltariSange.edit', compact('recoltareSange', 'recoltariSangeProduse', 'recoltariSangeGrupe'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RecoltareSange  $recoltareSange
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RecoltareSange $recoltareSange)
    {
        $recoltareSange->update($this->validateRequest($request));

        return redirect($request->session()->get('recoltareSangeReturnUrl') ?? ('/recoltari-sange'))->with('status', 'Recoltarea de sânge „' . ($recoltareSange->cod ?? '') . '” a fost modificată cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RecoltareSange  $recoltareSange
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, RecoltareSange $recoltareSange)
    {
        $recoltareSange->delete();

        return back()->with('status', 'Recoltarea de sânge „' . ($recoltareSange->cod ?? '') . '” a fost ștearsă cu succes!');
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
// dd($request->method());
        return $request->validate(
            [
                'recoltari_sange_produs_id' => 'required',
                'recoltari_sange_grupa_id' => 'required',
                'data' => 'required',
                'cod' => 'required',
                'tip' => 'required',
                'cantitate' => ($request->_method === "PATCH") ? 'required|integer' : '',
                'nrPungi' => $request->isMethod('post') ? 'required|integer|min:1' : '',
                'cantitatiPungiSange.*' => $request->isMethod('post') ? 'required|integer' : '',
            ],
            [
                // 'tara_id.required' => 'Câmpul țara este obligatoriu'
            ]
        );
    }
}
