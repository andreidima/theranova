<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RecoltareSangeComanda;
use App\Models\RecoltareSange;
use App\Models\RecoltareSangeProdus;
use App\Models\RecoltareSangeGrupa;
use Carbon\Carbon;

class RecoltareSangeComandaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('recoltareSangeComandaReturnUrl');

        $searchNumar = $request->searchNumar;
        $searchUnitate = $request->searchUnitate;
        $searchData = $request->searchData;

        $query = RecoltareSangeComanda::with('recoltariSange')
            ->when($searchNumar, function ($query, $searchNumar) {
                return $query->where('numar', $searchNumar);
            })
            ->when($searchUnitate, function ($query, $searchUnitate) {
                return $query->where('unitate', $searchUnitate);
            })
            ->when($searchData, function ($query, $searchData) {
                return $query->whereDate('data', $searchData);
            })
            ->latest();

        $recoltariSangeComenzi = $query->simplePaginate(25);

        return view('recoltariSangeComenzi.index', compact('recoltariSangeComenzi', 'searchNumar', 'searchUnitate', 'searchData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->session()->get('recoltareSangeComandaReturnUrl') ?? $request->session()->put('recoltareSangeComandaReturnUrl', url()->previous());

        $recoltariSange = RecoltareSange::whereNull('rebut')->whereNull('comanda_id')->get();

        return view('recoltariSangeComenzi.create', compact('recoltariSange'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $recoltareSangeComanda = RecoltareSangeComanda::create($this->validateRequest($request));

        // Adaugarea recoltarilor la comanda
        RecoltareSange::whereIn('id', $request->recoltariSangeAdaugateLaComanda)->update(['comanda_id' => $recoltareSangeComanda->id]);

        return redirect($request->session()->get('recoltareSangeComandaReturnUrl') ?? ('/recoltari-sange/comenzi'))->with('status', 'Comanda „' . ($recoltareSangeComanda->numar ?? '') . '” a fost adăugată cu succes!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RecoltareSangeComanda  $recoltareSangeComanda
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, RecoltareSangeComanda $recoltareSangeComanda)
    {
        $request->session()->get('recoltareSangeComandaReturnUrl') ?? $request->session()->put('recoltareSangeComandaReturnUrl', url()->previous());

        return view('recoltariSangeComenzi.show', compact('recoltareSangeComanda'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RecoltareSangeComanda  $recoltareSangeComanda
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, RecoltareSangeComanda $recoltareSangeComanda)
    {
        $request->session()->get('recoltareSangeComandaReturnUrl') ?? $request->session()->put('recoltareSangeComandaReturnUrl', url()->previous());

        $recoltareSangeComanda = RecoltareSangeComanda::where('id', $recoltareSangeComanda->id)->with('recoltariSange')->first();

        $recoltariSange = RecoltareSange::whereNull('rebut')
            ->where(function($query) use ($recoltareSangeComanda) {
                return $query
                        ->whereNull('comanda_id')
                        ->orWhere('comanda_id', $recoltareSangeComanda->id);
                })
            ->get();


        return view('recoltariSangeComenzi.edit', compact('recoltareSangeComanda', 'recoltariSange'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RecoltareSangeComanda  $recoltareSangeComanda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RecoltareSangeComanda $recoltareSangeComanda)
    {
        $recoltareSangeComanda->update($this->validateRequest($request));

        // Scoaterea recoltarilor ce nu mai sunt din comanda
        RecoltareSange::where('comanda_id', $recoltareSangeComanda->id)->whereNotIn('id', $request->recoltariSangeAdaugateLaComanda)->update(['comanda_id' => null]);

        // Adaugarea recoltarilor la comanda
        RecoltareSange::whereIn('id', $request->recoltariSangeAdaugateLaComanda)->update(['comanda_id' => $recoltareSangeComanda->id]);


//         $recoltariSangeVechiIduri = $recoltareSangeComanda->recoltariSange->pluck('id');
// dd($recoltariSangeVechiIduri, $request->recoltariSangeAdaugateLaComanda);
//         foreach ($recoltareSangeComanda->recoltariSange as $recoltareSange){
//             $recoltareSangeDB = RecoltareSange::findOrFail($recoltareSange->id);
//             $recoltareSangeDB->comanda_id = '';
//             $recoltareSangeDB->save();
//         }

//         foreach ($request->recoltariSangeAdaugateLaComanda as $recoltareSange){
//             $recoltareSangeDB = RecoltareSange::findOrFail($recoltareSange);
//             $recoltareSangeDB->comanda_id = $recoltareSangeComanda->id;
//             $recoltareSangeDB->save();
//         }

//         dd('stop');

        return redirect($request->session()->get('recoltareSangeComandaReturnUrl') ?? ('/recoltari-sange/comenzi'))->with('status', 'Comanda „' . ($recoltareSangeComanda->numar ?? '') . '” a fost modificată cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RecoltareSangeComanda  $recoltareSangeComanda
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, RecoltareSangeComanda $recoltareSangeComanda)
    {
        $recoltareSangeComanda->delete();

        // Scoaterea recoltarilor de la comanda
        RecoltareSange::where('comanda_id', $recoltareSangeComanda->id)->update(['comanda_id' => null]);

        return back()->with('status', 'Comanda „' . ($recoltareSangeComanda->numar ?? '') . '” a fost ștearsă cu succes!');
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
                'numar' => 'required|numeric',
                'unitate' => 'required',
                'localitate' => 'required',
                'judet' => 'required',
                'data' => 'required',
            ],
            [
                // 'tara_id.required' => 'Câmpul țara este obligatoriu'
            ]
        );
    }

    public function exportPdf(Request $request, RecoltareSangeComanda $recoltareSangeComanda)
    {
        $recoltareSangeGrupe = RecoltareSangeGrupa::select('id', 'nume')->get();

        if ($request->view_type === 'export-html') {
            return view('recoltariSangeComenzi.export.recoltareSangeComandaPdf', compact('recoltareSangeComanda', 'recoltareSangeGrupe'));
        } elseif ($request->view_type === 'export-pdf') {
            $pdf = \PDF::loadView('recoltariSangeComenzi.export.recoltareSangeComandaPdf', compact('recoltareSangeComanda', 'recoltareSangeGrupe'))
                ->setPaper('a4', 'portrait');
            $pdf->getDomPDF()->set_option("enable_php", true);
            // return $pdf->download('Contract ' . $comanda->transportator_contract . '.pdf');
            return $pdf->stream();
        }
    }
}
