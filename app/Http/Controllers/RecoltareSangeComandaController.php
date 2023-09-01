<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RecoltareSangeComanda;
use App\Models\RecoltareSange;
use App\Models\RecoltareSangeProdus;
use App\Models\RecoltareSangeGrupa;
use App\Models\RecoltareSangeBeneficiar;
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

        $searchComandaNr = $request->searchComandaNr;
        $searchAvizNr = $request->searchAvizNr;
        $searchBeneficiar = $request->searchBeneficiar;
        $searchData = $request->searchData;

        $query = RecoltareSangeComanda::with('recoltariSange', 'beneficiar')
            ->when($searchComandaNr, function ($query, $searchComandaNr) {
                return $query->where('comanda_nr', $searchComandaNr);
            })
            ->when($searchAvizNr, function ($query, $searchAvizNr) {
                return $query->where('aviz_nr', $searchAvizNr);
            })
            ->when($searchBeneficiar, function ($query, $searchBeneficiar) {
                return $query->where('recoltari_sange_beneficiar_id', $searchBeneficiar);
            })
            ->when($searchData, function ($query, $searchData) {
                return $query->whereDate('data', $searchData);
            })
            ->latest();

        $recoltariSangeComenzi = $query->simplePaginate(25);

        $beneficiari = RecoltareSangeBeneficiar::select('id', 'nume')->get();

        return view('recoltariSangeComenzi.index', compact('recoltariSangeComenzi', 'beneficiari', 'searchComandaNr', 'searchAvizNr', 'searchBeneficiar', 'searchData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->session()->get('recoltareSangeComandaReturnUrl') ?? $request->session()->put('recoltareSangeComandaReturnUrl', url()->previous());

        $beneficiari = RecoltareSangeBeneficiar::select('id', 'nume')->get();
        $recoltariSange = RecoltareSange::with('grupa', 'produs')->whereNull('recoltari_sange_rebut_id')->whereNull('comanda_id')->get();

        return view('recoltariSangeComenzi.create', compact('beneficiari', 'recoltariSange'));
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

        $recoltareSangeComanda = RecoltareSangeComanda::create($request->except('recoltariSangeAdaugateLaComanda', 'date'));
        // Adaugarea recoltarilor la comanda
        foreach ($request->recoltariSangeAdaugateLaComanda as $key=>$recoltareSange){
            RecoltareSange::where('id', $recoltareSange)->update(['comanda_id' => $recoltareSangeComanda->id, 'comanda_ordine_recoltari' => $key+1]);
        }

        return redirect($request->session()->get('recoltareSangeComandaReturnUrl') ?? ('/recoltari-sange/comenzi'))->with('status', 'Comanda „' . ($recoltareSangeComanda->comanda_nr ?? '') . '” a fost adăugată cu succes!');
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

        $recoltareSangeComanda = RecoltareSangeComanda::where('id', $recoltareSangeComanda->id)
            ->with('recoltariSange', function($query){
                $query->orderBy('comanda_ordine_recoltari');
            })
            ->first();

        $beneficiari = RecoltareSangeBeneficiar::select('id', 'nume')->get();;
        $recoltariSange = RecoltareSange::with('grupa', 'produs')
            ->whereNull('recoltari_sange_rebut_id')
            ->where(function($query) use ($recoltareSangeComanda) {
                return $query
                        ->whereNull('comanda_id')
                        ->orWhere('comanda_id', $recoltareSangeComanda->id);
                })
            ->get();


        return view('recoltariSangeComenzi.edit', compact('recoltareSangeComanda', 'beneficiari', 'recoltariSange'));
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
        $this->validateRequest($request);

        $recoltareSangeComanda->update($request->except('recoltariSangeAdaugateLaComanda', 'date'));

        // Scoaterea recoltarilor ce nu mai sunt din comanda
        RecoltareSange::where('comanda_id', $recoltareSangeComanda->id)->whereNotIn('id', $request->recoltariSangeAdaugateLaComanda)->update(['comanda_id' => null]);

        // Adaugarea recoltarilor la comanda
        foreach ($request->recoltariSangeAdaugateLaComanda as $key=>$recoltareSange){
            RecoltareSange::where('id', $recoltareSange)->update(['comanda_id' => $recoltareSangeComanda->id, 'comanda_ordine_recoltari' => $key+1]);
        }


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

        return redirect($request->session()->get('recoltareSangeComandaReturnUrl') ?? ('/recoltari-sange/comenzi'))->with('status', 'Comanda „' . ($recoltareSangeComanda->comanda_nr ?? '') . '” a fost modificată cu succes!');
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
// dd($request);
        return $request->validate(
            [
                'comanda_nr' => 'required|numeric|between:1,999999',
                'aviz_nr' => 'required|numeric|between:1,999999',
                'recoltari_sange_beneficiar_id' => 'required',
                'data' => 'required',
                'recoltariSangeAdaugateLaComanda' => 'required'
            ],
            [
                // 'tara_id.required' => 'Câmpul țara este obligatoriu'
            ]
        );
    }

    public function exportPdf(Request $request, RecoltareSangeComanda $recoltareSangeComanda)
    {
        // $recoltareSangeGrupe = RecoltareSangeGrupa::select('id', 'nume')->get();

        $recoltareSangeComanda = RecoltareSangeComanda::where('id', $recoltareSangeComanda->id)
            ->with('recoltariSange.produs', 'recoltariSange.grupa')
            ->with('recoltariSange', function($query){
                $query->orderBy('comanda_ordine_recoltari');
            })
            ->first();

        // dd($recoltareSangeComanda);

        if ($request->view_type === 'export-html') {
            return view('recoltariSangeComenzi.export.recoltareSangeComandaPdf', compact('recoltareSangeComanda'));
        } elseif ($request->view_type === 'export-pdf') {
            // return view('recoltariSangeComenzi.export.recoltareSangeComandaPdf', compact('recoltareSangeComanda'));
            $pdf = \PDF::loadView('recoltariSangeComenzi.export.recoltareSangeComandaPdf', compact('recoltareSangeComanda'))
                ->setPaper('a4', 'portrait');
            $pdf->getDomPDF()->set_option("enable_php", true);
            // return $pdf->download('Contract ' . $comanda->transportator_contract . '.pdf');
            return $pdf->stream();
        }
    }
}
