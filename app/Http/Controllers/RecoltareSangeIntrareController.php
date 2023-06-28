<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RecoltareSangeIntrare;
use App\Models\RecoltareSange;
use App\Models\RecoltareSangeProdus;
use App\Models\RecoltareSangeGrupa;
use App\Models\RecoltareSangeBeneficiar;
use Carbon\Carbon;

class RecoltareSangeIntrareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('recoltareSangeIntrareReturnUrl');

        $searchBonNr = $request->searchBonNr;
        $searchAvizNr = $request->searchAvizNr;
        $searchBeneficiar = $request->searchBeneficiar;
        $searchData = $request->searchData;

        $query = RecoltareSangeIntrare::with('recoltariSange')
            ->when($searchBonNr, function ($query, $searchBonNr) {
                return $query->where('comanda_nr', $searchBonNr);
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

        $recoltariSangeIntrari = $query->simplePaginate(25);

        $beneficiari = RecoltareSangeBeneficiar::select('id', 'nume')->get();

        return view('recoltariSangeIntrari.index', compact('recoltariSangeIntrari', 'beneficiari', 'searchBonNr', 'searchAvizNr', 'searchBeneficiar', 'searchData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->session()->get('recoltareSangeIntrareReturnUrl') ?? $request->session()->put('recoltareSangeIntrareReturnUrl', url()->previous());

        $beneficiari = RecoltareSangeBeneficiar::select('id', 'nume')->get();

        return view('recoltariSangeIntrari.create', compact('beneficiari'));
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

        $recoltareSangeIntrare = RecoltareSangeIntrare::create($request->except('recoltariSangeAdaugateLaIntrare', 'date'));

        // Adaugarea recoltarilor la comanda
        RecoltareSange::whereIn('id', $request->recoltariSangeAdaugateLaIntrare)->update(['comanda_id' => $recoltareSangeIntrare->id]);

        return redirect($request->session()->get('recoltareSangeIntrareReturnUrl') ?? ('/recoltari-sange/comenzi'))->with('status', 'Intrare „' . ($recoltareSangeIntrare->numar ?? '') . '” a fost adăugată cu succes!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RecoltareSangeIntrare  $recoltareSangeIntrare
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, RecoltareSangeIntrare $recoltareSangeIntrare)
    {
        $request->session()->get('recoltareSangeIntrareReturnUrl') ?? $request->session()->put('recoltareSangeIntrareReturnUrl', url()->previous());

        return view('recoltariSangeIntrari.show', compact('recoltareSangeIntrare'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RecoltareSangeIntrare  $recoltareSangeIntrare
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, RecoltareSangeIntrare $recoltareSangeIntrare)
    {
        $request->session()->get('recoltareSangeIntrareReturnUrl') ?? $request->session()->put('recoltareSangeIntrareReturnUrl', url()->previous());

        $recoltareSangeIntrare = RecoltareSangeIntrare::where('id', $recoltareSangeIntrare->id)->with('recoltariSange')->first();

        $beneficiari = RecoltareSangeBeneficiar::select('id', 'nume')->get();
        $recoltariSange = RecoltareSange::whereNull('recoltari_sange_rebut_id')
            ->where(function($query) use ($recoltareSangeIntrare) {
                return $query
                        ->whereNull('comanda_id')
                        ->orWhere('comanda_id', $recoltareSangeIntrare->id);
                })
            ->get();


        return view('recoltariSangeIntrari.edit', compact('recoltareSangeIntrare', 'beneficiari', 'recoltariSange'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RecoltareSangeIntrare  $recoltareSangeIntrare
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RecoltareSangeIntrare $recoltareSangeIntrare)
    {
        $this->validateRequest($request);

        $recoltareSangeIntrare->update($request->except('recoltariSangeAdaugateLaIntrare', 'date'));

        // Scoaterea recoltarilor ce nu mai sunt din comanda
        RecoltareSange::where('comanda_id', $recoltareSangeIntrare->id)->whereNotIn('id', $request->recoltariSangeAdaugateLaIntrare)->update(['comanda_id' => null]);

        // Adaugarea recoltarilor la comanda
        RecoltareSange::whereIn('id', $request->recoltariSangeAdaugateLaIntrare)->update(['comanda_id' => $recoltareSangeIntrare->id]);


//         $recoltariSangeVechiIduri = $recoltareSangeIntrare->recoltariSange->pluck('id');
// dd($recoltariSangeVechiIduri, $request->recoltariSangeAdaugateLaIntrare);
//         foreach ($recoltareSangeIntrare->recoltariSange as $recoltareSange){
//             $recoltareSangeDB = RecoltareSange::findOrFail($recoltareSange->id);
//             $recoltareSangeDB->comanda_id = '';
//             $recoltareSangeDB->save();
//         }

//         foreach ($request->recoltariSangeAdaugateLaIntrare as $recoltareSange){
//             $recoltareSangeDB = RecoltareSange::findOrFail($recoltareSange);
//             $recoltareSangeDB->comanda_id = $recoltareSangeIntrare->id;
//             $recoltareSangeDB->save();
//         }

//         dd('stop');

        return redirect($request->session()->get('recoltareSangeIntrareReturnUrl') ?? ('/recoltari-sange/comenzi'))->with('status', 'Intrare „' . ($recoltareSangeIntrare->numar ?? '') . '” a fost modificată cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RecoltareSangeIntrare  $recoltareSangeIntrare
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, RecoltareSangeIntrare $recoltareSangeIntrare)
    {
        $recoltareSangeIntrare->delete();

        // Scoaterea recoltarilor de la comanda
        RecoltareSange::where('comanda_id', $recoltareSangeIntrare->id)->update(['comanda_id' => null]);

        return back()->with('status', 'Intrare „' . ($recoltareSangeIntrare->numar ?? '') . '” a fost ștearsă cu succes!');
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
                'recoltariSangeAdaugateLaIntrare' => 'required'
            ],
            [
                // 'tara_id.required' => 'Câmpul țara este obligatoriu'
            ]
        );
    }

    public function exportPdf(Request $request, RecoltareSangeIntrare $recoltareSangeIntrare)
    {
        $recoltareSangeGrupe = RecoltareSangeGrupa::select('id', 'nume')->get();

        if ($request->view_type === 'export-html') {
            return view('recoltariSangeIntrari.export.recoltareSangeIntrarePdf', compact('recoltareSangeIntrare', 'recoltareSangeGrupe'));
        } elseif ($request->view_type === 'export-pdf') {
            $pdf = \PDF::loadView('recoltariSangeIntrari.export.recoltareSangeIntrarePdf', compact('recoltareSangeIntrare', 'recoltareSangeGrupe'))
                ->setPaper('a4', 'portrait');
            $pdf->getDomPDF()->set_option("enable_php", true);
            // return $pdf->download('Contract ' . $comanda->transportator_contract . '.pdf');
            return $pdf->stream();
        }
    }
}
