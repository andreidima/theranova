<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RecoltareSangeIntrare;
use App\Models\RecoltareSange;
use App\Models\RecoltareSangeProdus;
use App\Models\RecoltareSangeGrupa;
use App\Models\RecoltareSangeExpeditor;
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
        $searchExpeditor = $request->searchExpeditor;
        $searchData = $request->searchData;
// dd($searchBonNr);
        $query = RecoltareSangeIntrare::with('recoltariSange')
            ->when($searchBonNr, function ($query, $searchBonNr) {
                return $query->where('bon_nr', $searchBonNr);
            })
            ->when($searchAvizNr, function ($query, $searchAvizNr) {
                return $query->where('aviz_nr', $searchAvizNr);
            })
            ->when($searchExpeditor, function ($query, $searchExpeditor) {
                return $query->where('recoltari_sange_expeditor_id', $searchExpeditor);
            })
            ->when($searchData, function ($query, $searchData) {
                return $query->whereDate('data', $searchData);
            })
            ->latest();
            // dd($query);

        $recoltariSangeIntrari = $query->simplePaginate(25);

        $expeditori = RecoltareSangeExpeditor::select('id', 'nume')->orderBy('nume')->get();

        return view('recoltariSangeIntrari.index', compact('recoltariSangeIntrari', 'expeditori', 'searchBonNr', 'searchAvizNr', 'searchExpeditor', 'searchData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->session()->get('recoltareSangeIntrareReturnUrl') ?? $request->session()->put('recoltareSangeIntrareReturnUrl', url()->previous());

        $expeditori = RecoltareSangeExpeditor::select('id', 'nume')->orderBy('nume')->get();
        $recoltariSangeProduse = RecoltareSangeProdus::get();
        $recoltariSangeGrupe = RecoltareSangeGrupa::get();

        return view('recoltariSangeIntrari.create', compact('expeditori', 'recoltariSangeProduse', 'recoltariSangeGrupe'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request, $request->pungi);
        $this->validateRequest($request);
        // dd($request);
        $recoltareSangeIntrare = RecoltareSangeIntrare::create($request->except('pungi', 'date'));

        foreach($request->pungi as $punga){
            $recoltareSange = RecoltareSange::create();
            $recoltareSange->data_expirare = $punga['data_expirare'];
            $recoltareSange->cod = $punga['cod'];
            $recoltareSange->recoltari_sange_produs_id = $punga['recoltari_sange_produs_id'];
            $recoltareSange->recoltari_sange_grupa_id = $punga['recoltari_sange_grupa_id'];
            $recoltareSange->cantitate = $punga['cantitate'];
            $recoltareSange->intrare_id = $recoltareSangeIntrare->id;
            $recoltareSange->save();
        }

        return redirect($request->session()->get('recoltareSangeIntrareReturnUrl') ?? ('/recoltari-sange/intrari'))->with('status', 'Intrarea „' . ($recoltareSangeIntrare->bon_nr ?? '') . '” a fost adăugată cu succes!');
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

        $expeditori = RecoltareSangeExpeditor::select('id', 'nume')->orderBy('nume')->get();
        $recoltariSangeProduse = RecoltareSangeProdus::get();
        $recoltariSangeGrupe = RecoltareSangeGrupa::get();


        return view('recoltariSangeIntrari.edit', compact('recoltareSangeIntrare', 'expeditori', 'recoltariSangeProduse', 'recoltariSangeGrupe'));
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
        // dd($request->pungi);
        $recoltareSangeIntrare->update($request->except('pungi', 'date'));

        // Se verifica daca intrarea are recoltariSange in plus, pentru a se sterge
        $ids = array_filter(array_column($request->pungi, 'id')); // array_column extrage doar coloana id, iar array_filter elimina cele cu id null
        // dd($ids, RecoltareSange::where('intrare_id', $recoltareSangeIntrare->id)->whereNotIn('id', $ids)->get());
        RecoltareSange::where('intrare_id', $recoltareSangeIntrare->id)->whereNotIn('id', $ids)->delete();
        // dd($recoltareSangeIntrare->recoltariSange->whereNotIn('id', $ids)->get());
        // dd($ids);

        // Se adauga recoltarile de sange la intrare
        foreach($request->pungi as $punga){
            if($punga['id']){
                $recoltareSange = RecoltareSange::where('id', $punga['id'])->first();
                // echo $punga['id'] . '<br>';
            } else {
                $recoltareSange = RecoltareSange::create();
            }
            // echo $punga['id'] .  ' - ' . $recoltareSange . '<br>';
            // dd('stop');
            $recoltareSange->data_expirare = $punga['data_expirare'];
            $recoltareSange->cod = $punga['cod'];
            $recoltareSange->recoltari_sange_produs_id = $punga['recoltari_sange_produs_id'];
            $recoltareSange->recoltari_sange_grupa_id = $punga['recoltari_sange_grupa_id'];
            $recoltareSange->cantitate = $punga['cantitate'];
            $recoltareSange->intrare_id = $recoltareSangeIntrare->id;
            $recoltareSange->save();
        }


// dd('stop');
        return redirect($request->session()->get('recoltareSangeIntrareReturnUrl') ?? ('/recoltari-sange/comenzi'))->with('status', 'Intrarea „' . ($recoltareSangeIntrare->bon_nr ?? '') . '” a fost modificată cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RecoltareSangeIntrare  $recoltareSangeIntrare
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, RecoltareSangeIntrare $recoltareSangeIntrare)
    {
        $recoltareSangeIntrare->recoltariSange()->delete();

        $recoltareSangeIntrare->delete();

        return back()->with('status', 'Intrarea „' . ($recoltareSangeIntrare->bon_nr ?? '') . '” a fost ștearsă cu succes!');
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
                'bon_nr' => 'required|numeric|between:1,999999',
                'aviz_nr' => 'required|numeric|between:1,999999',
                'recoltari_sange_expeditor_id' => 'required',
                'data' => 'required',
                'pungi' => 'required',

                'pungi.*.id' => '',
                'pungi.*.data_expirare' => 'required',
                'pungi.*.recoltari_sange_grupa_id' => 'required',
                'pungi.*.cod' => 'required',
                'pungi.*.recoltari_sange_produs_id' => 'required',
                'pungi.*.cantitate' => 'required|integer|between:1,999',



                // 'recoltariSangeAdaugateLaIntrare' => 'required'
            ],
            [
                'pungi.*.data_expirare.required' => 'Data pentru punga :position este necesară',
                'pungi.*.recoltari_sange_grupa_id.required' => 'Grupa pentru punga :position este necesară',
                'pungi.*.cod.required' => 'Codul pentru punga :position este necesar',
                'pungi.*.recoltari_sange_produs_id.required' => 'Produsul pentru punga :position este necesar',
                'pungi.*.cantitate.required' => 'Cantitatea pentru punga :position este necesară',
                'pungi.*.cantitate.integer' => 'Cantitatea pentru punga :position trebuie să fie un număr',
                'pungi.*.cantitate.between' => 'Cantitatea pentru punga :position trebuie să fie între 1 și 999',
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
