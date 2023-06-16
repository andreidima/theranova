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
            // ->when($searchNume, function ($query, $searchNume) {
            //     return $query->where('nume', 'like', '%' . $searchNume . '%');
            // })
            latest();

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
        $recoltareSange = RecoltareSange::create($this->validateRequest($request));

        return redirect($request->session()->get('recoltareSangeReturnUrl') ?? ('/mementouri'))->with('status', 'RecoltareSangeul „' . ($memento->nume ?? '') . '” a fost adăugat cu succes!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RecoltareSange  $memento
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, RecoltareSange $memento)
    {
        $request->session()->get('recoltareSangeReturnUrl') ?? $request->session()->put('recoltareSangeReturnUrl', url()->previous());

        return view('mementouri.show', compact('memento'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RecoltareSange  $memento
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, RecoltareSange $memento)
    {
        $request->session()->get('recoltareSangeReturnUrl') ?? $request->session()->put('recoltareSangeReturnUrl', url()->previous());

        return view('mementouri.edit', compact('memento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RecoltareSange  $memento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RecoltareSange $memento)
    {
        $memento->update($this->validateRequest($request));

        $memento->alerte()->delete();
        if ($request->dateSelectate) {
            foreach ($request->dateSelectate as $data){
                $alerta = new RecoltareSangeAlerta(['data' => $data]);
                $memento->alerte()->save($alerta);
            }
        }

        return redirect($request->session()->get('recoltareSangeReturnUrl') ?? ('/mementouri'))->with('status', 'RecoltareSangeul „' . ($memento->nume ?? '') . '” a fost modificat cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RecoltareSange  $memento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, RecoltareSange $memento)
    {
        $memento->alerte()->delete();

        $memento->delete();

        return back()->with('status', 'RecoltareSangeul „' . ($memento->nume ?? '') . '” a fost șters cu succes!');
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
                'recoltari_sange_produs_id' => 'required',
                'data_expirare' => '',
                'descriere' => 'nullable|max:10000',
                'observatii' => 'nullable|max:10000',
            ],
            [
                // 'tara_id.required' => 'Câmpul țara este obligatoriu'
            ]
        );
    }
}
