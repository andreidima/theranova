<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RecoltareSange;
use App\Models\RecoltareSangeProdus;
use App\Models\RecoltareSangeGrupa;
use App\Models\RecoltareSangeRebut;
use Carbon\Carbon;

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
            when($searchCod, function ($query, $searchCod) {
                return $query->where('cod', $searchCod);
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

        for ($i = 1; $i <= count($request->pungi); $i++){
            $recoltareSange = new RecoltareSange;
            $recoltareSange->recoltari_sange_produs_id = $request->pungi[$i]['produs'];
            $recoltareSange->recoltari_sange_grupa_id = $request->recoltari_sange_grupa_id;
            $recoltareSange->data = $request->data;
            $recoltareSange->cod = $request->cod;
            $recoltareSange->tip = $request->tip;
            $recoltareSange->cantitate = $request->pungi[$i]['cantitate'];
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
// dd($request->method(), $request);
        return $request->validate(
            [
                // 'recoltari_sange_produs_id' => 'required',
                'recoltari_sange_grupa_id' => 'required',
                'data' => 'required',
                'cod' => 'required',
                'tip' => 'required',
                'pungi.*.produs' => $request->isMethod('post') ? 'required' : '',
                'pungi.*.cantitate' => $request->isMethod('post') ? 'required|integer|between:1,999' : '',

                'recoltari_sange_produs_id' => ($request->_method === "PATCH") ? 'required' : '',
                'cantitate' => ($request->_method === "PATCH") ? 'required|integer|between:1,999' : '',
            ],
            [
                // 'tara_id.required' => 'Câmpul țara este obligatoriu'
            ]
        );
    }

    public function rebuturi(Request $request)
    {
        $request->session()->forget('recoltareSangeRebutReturnUrl');

        $searchCod = $request->searchCod;
        $searchData = $request->searchData;

        $query = RecoltareSange::with('produs', 'grupa', 'rebut')
            ->when($searchCod, function ($query, $searchCod) {
                return $query->where('cod', $searchCod);
            })
            ->when($searchData, function ($query, $searchData) {
                return $query->whereDate('rebut_created_at', $searchData);
            })
            ->latest('rebut_created_at');

        $recoltariSange = $query->simplePaginate(25);

        return view('recoltariSange.rebuturi.index', compact('recoltariSange', 'searchCod', 'searchData'));
    }

    public function rebuturiModifica(Request $request, RecoltareSange $recoltareSange)
    {
        $request->session()->get('recoltareSangeRebutReturnUrl') ?? $request->session()->put('recoltareSangeRebutReturnUrl', url()->previous());

        $recoltariSangeRebuturi = RecoltareSangeRebut::get();

        return view('recoltariSange.rebuturi.formularModificare', compact('recoltareSange', 'recoltariSangeRebuturi'));
    }

    public function postRebuturiModifica(Request $request, RecoltareSange $recoltareSange)
    {
        $request->session()->get('recoltareSangeRebutReturnUrl') ?? $request->session()->put('recoltareSangeRebutReturnUrl', url()->previous());

        $recoltareSange->recoltari_sange_rebut_id = $request->recoltari_sange_rebut_id;
        $request->recoltari_sange_rebut_id ? ($recoltareSange->rebut_created_at = Carbon::now()) : ($recoltareSange->rebut_created_at = null); // daca se sterge rebut. se sterge si campul rebut_created_at
        $recoltareSange->save();

        return redirect($request->session()->get('recoltareSangeRebutReturnUrl') ?? ('/recoltari-sange/rebuturi'))->with('status', 'Recoltarea de sânge „' . ($recoltareSange->cod ?? '') . '” a fost modificată cu succes!');
    }
}
