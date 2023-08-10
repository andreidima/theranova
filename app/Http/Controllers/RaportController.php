<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RecoltareSange;
use App\Models\RecoltareSangeRebut;
use App\Models\RecoltareSangeProdus;
use App\Models\RecoltareSangeComanda;

class RaportController extends Controller
{
    public function index(Request $request)
    {
        // $request->session()->forget('raportReturnUrl');
        $interval = $request->interval;
        $produse = RecoltareSangeProdus::all();

        switch ($request->input('action')) {
            case 'recoltariSangeCtsvToate':
                $request->validate(['interval' => 'required']);
                $query = RecoltareSange::
                    with('produs', 'comanda')
                    ->whereNull('intrare_id')
                    ->when($interval, function ($query, $interval) {
                        return $query->whereBetween('data', [strtok($interval, ','), strtok( '' )]);
                    })
                    ->latest();
                $recoltariSange = $query->get();

                $livrari = RecoltareSange::with('comanda.beneficiar')
                    ->whereHas('comanda', function ($query) use($interval) {
                        return $query->whereBetween('data', [strtok($interval, ','), strtok( '' )]);
                    })
                    ->get();

                $rebutari = RecoltareSange::with('produs')
                    ->when($interval, function ($query, $interval) {
                        return $query->whereBetween('rebut_data', [strtok($interval, ','), strtok( '' )]);
                    })
                    ->get();

                // return view('rapoarte.export.recoltariSangeCtsvToate', compact('recoltariSange', 'livrari', 'rebutari', 'interval'));
                $pdf = \PDF::loadView('rapoarte.export.recoltariSangeCtsvToate', compact('recoltariSange', 'livrari', 'rebutari', 'interval'))
                    ->setPaper('a4', 'portrait');
                $pdf->getDomPDF()->set_option("enable_php", true);
                // return $pdf->download('Contract ' . $comanda->transportator_contract . '.pdf');
                return $pdf->stream();

            case 'recoltariSangeCtsvToateDetaliatPeZile':
                $request->validate(['interval' => 'required']);
                $query = RecoltareSange::
                    with('produs', 'comanda')
                    ->when($interval, function ($query, $interval) {
                        return $query->whereBetween('data', [strtok($interval, ','), strtok( '' )]);
                    })
                    ->latest();
                $recoltariSange = $query->get();

                // return view('rapoarte.export.recoltariSangeCtsvToateDetaliatPeZile', compact('recoltariSange', 'interval'));
                $pdf = \PDF::loadView('rapoarte.export.recoltariSangeCtsvToateDetaliatPeZile', compact('recoltariSange', 'interval'))
                    ->setPaper('a4', 'portrait');
                $pdf->getDomPDF()->set_option("enable_php", true);
                // return $pdf->download('Contract ' . $comanda->transportator_contract . '.pdf');
                return $pdf->stream();

            case 'livrariDetaliatePeZile':
                $request->validate(['interval' => 'required']);

                $query = RecoltareSangeComanda::
                    with('recoltariSange.produs', 'recoltariSange.grupa')
                    ->whereBetween('data', [strtok($interval, ','), strtok( '' )])
                    ->orderBy('data');
                $comenzi = $query->get();

                // return view('rapoarte.export.livrariDetaliatePeZile', compact('comenzi', 'interval'));
                $pdf = \PDF::loadView('rapoarte.export.livrariDetaliatePeZile', compact('comenzi', 'interval'))
                    ->setPaper('a4', 'portrait');
                $pdf->getDomPDF()->set_option("enable_php", true);
                // return $pdf->download('Contract ' . $comanda->transportator_contract . '.pdf');
                return $pdf->stream();

            case 'rebuturiDetaliatePeZile':
                $request->validate(['interval' => 'required']);

                $recoltariSange = RecoltareSange::with('rebut')
                    ->when($interval, function ($query, $interval) {
                        return $query->whereBetween('rebut_data', [strtok($interval, ','), strtok( '' )]);
                    })
                    ->get();
                $rebuturi = RecoltareSangeRebut::select('id', 'nume')->orderBy('nume')->get();

                // return view('rapoarte.export.rebuturiDetaliatePeZile', compact('recoltariSange', 'rebuturi', 'interval'));
                $pdf = \PDF::loadView('rapoarte.export.rebuturiDetaliatePeZile', compact('recoltariSange', 'rebuturi', 'interval'))
                    ->setPaper('a4', 'landscape');
                $pdf->getDomPDF()->set_option("enable_php", true);
                // return $pdf->download('Contract ' . $comanda->transportator_contract . '.pdf');
                return $pdf->stream();

            case 'stocuriPungiSange':
                $request->validate(['interval' => 'required']);
                $recoltariSange = RecoltareSange::with('produs', 'grupa')
                    ->when($interval, function ($query, $interval) {
                        return $query->whereDate('data', '<', [strtok($interval, ',')]);
                    })
                    ->where(function($query) use ($interval){
                        $query->whereDoesntHave('comanda')
                            ->orWhereHas('comanda', function ($query) use ($interval) {
                                $query->whereDate('data', '>', [strtok($interval, ',')]);
                            });
                    })
                    ->where(function($query) use ($interval){
                        $query->whereNull('rebut_data')
                            ->orwhereDate('rebut_data',  '>', [strtok($interval, ',')]);
                    })
                    ->get();

                return view('rapoarte.stocuriPungiSange', compact('recoltariSange', 'interval'));

                return view('rapoarte.export.stocuriPungiSange', compact('recoltariSange', 'interval'));
                $pdf = \PDF::loadView('rapoarte.export.stocuriPungiSange', compact('recoltariSange', 'interval'))
                    ->setPaper('a4', 'portrait');
                $pdf->getDomPDF()->set_option("enable_php", true);
                // return $pdf->download('Stocuri pungi sange.pdf');
                return $pdf->stream();

            case 'situatiaSangeluiSiAProduselorDinSange':
                $request->validate(['interval' => 'required']);
                $recoltariSangeInterval = RecoltareSange::with('produs')
                    // ->select('id', 'recoltari_sange_produs_id', 'cantitate')
                    ->when($interval, function ($query, $interval) {
                        return $query->whereBetween('data', [strtok($interval, ','), strtok( '' )]);
                    })
                    ->get();
                $recoltariSangeInitiale = RecoltareSange::with('produs')
                    ->when($interval, function ($query, $interval) {
                        return $query->whereDate('data', '<', [strtok($interval, ',')]);
                    })
                    ->where(function($query) use ($interval){
                        $query->whereDoesntHave('comanda')
                            ->orWhereHas('comanda', function ($query) use ($interval) {
                                $query->whereDate('data', '>', [strtok($interval, ',')]);
                            });
                    })
                    ->where(function($query) use ($interval){
                        $query->whereNull('rebut_data')
                            ->orwhereDate('rebut_data',  '>', [strtok($interval, ',')]);
                    })
                    ->get();
                $recoltariSangeRebutate = RecoltareSange::with('produs')
                    ->when($interval, function ($query, $interval) {
                        return $query->whereBetween('rebut_data', [strtok($interval, ','), strtok( '' )]);
                    })
                    ->get();
                $recoltariSangeLivrate = RecoltareSange::with('produs', 'comanda')
                    ->whereHas('comanda', function ($query) use ($interval) {
                        $query->whereBetween('data', [strtok($interval, ','), strtok( '' )]);
                    })
                    ->get();
                $recoltariSangeStocFinal = RecoltareSange::with('produs')
                    ->whereNull('comanda_id')
                    ->whereNull('recoltari_sange_rebut_id')
                    ->get();

                // $recoltariSange = RecoltareSange::with('produs')
                //     ->whereNotNull('comanda_id')
                //     ->whereNotNull('recoltari_sange_rebut_id')
                //     ->get();
                // dd($recoltariSange);

                // return view('rapoarte.export.situatiaSangeluiSiAProduselorDinSange', compact('recoltariSangeInterval', 'recoltariSangeInitiale', 'recoltariSangeRebutate', 'recoltariSangeLivrate', 'recoltariSangeStocFinal', 'produse', 'interval'));
                $pdf = \PDF::loadView('rapoarte.export.situatiaSangeluiSiAProduselorDinSange', compact('recoltariSangeInterval', 'recoltariSangeInitiale', 'recoltariSangeRebutate', 'recoltariSangeLivrate', 'recoltariSangeStocFinal', 'produse', 'interval'))
                    ->setPaper('a4', 'landscape');
                $pdf->getDomPDF()->set_option("enable_php", true);
                // return $pdf->download('Stocuri pungi sange.pdf');
                return $pdf->stream();

            case 'G1Rebut':
                $request->validate(['interval' => 'required']);
                $recoltariSange = RecoltareSange::
                    with('rebut', 'produs')
                    ->when($interval, function ($query, $interval) {
                        return $query->whereBetween('rebut_data', [strtok($interval, ','), strtok( '' )]);
                    })
                    ->get();
                $rebuturi = RecoltareSangeRebut::select('id', 'nume')->orderBy('nume')->get();

                // return view('rapoarte.export.G1Rebut', compact('recoltariSange', 'rebuturi', 'interval'));
                $pdf = \PDF::loadView('rapoarte.export.G1Rebut', compact('recoltariSange', 'rebuturi', 'interval'))
                    ->setPaper('a4', 'portrait');
                $pdf->getDomPDF()->set_option("enable_php", true);
                // return $pdf->download('Contract ' . $comanda->transportator_contract . '.pdf');
                return $pdf->stream();

            case 'G2RebutRepartitie':
                $request->validate(['interval' => 'required']);
                $recoltariSange = RecoltareSange::
                    with('rebut', 'produs')
                    ->when($interval, function ($query, $interval) {
                        return $query->whereBetween('rebut_data', [strtok($interval, ','), strtok( '' )]);
                    })
                    ->latest()
                    ->get();
                $rebuturi = RecoltareSangeRebut::select('id', 'nume')->orderBy('nume')->get();

                // return view('rapoarte.export.G2RebutRepartitie', compact('recoltariSange', 'rebuturi', 'interval'));
                $pdf = \PDF::loadView('rapoarte.export.G2RebutRepartitie', compact('recoltariSange', 'rebuturi', 'interval'))
                    // ->setPaper('a4', 'portrait');
                    ->setPaper('a4', 'landscape');
                $pdf->getDomPDF()->set_option("enable_php", true);
                // return $pdf->download('Contract ' . $comanda->transportator_contract . '.pdf');
                return $pdf->stream();

            case 'HUnitatiValidateDonareStandard':
                $request->validate(['interval' => 'required']);
                $recoltariSange = RecoltareSange::
                    whereNull('recoltari_sange_rebut_id')
                    ->when($interval, function ($query, $interval) {
                        return $query->whereBetween('data', [strtok($interval, ','), strtok( '' )]);
                    })
                    ->latest()
                    ->get();

                // return view('rapoarte.export.HUnitatiValidateDonareStandard', compact('recoltariSange', 'interval'));
                $pdf = \PDF::loadView('rapoarte.export.HUnitatiValidateDonareStandard', compact('recoltariSange', 'interval'))
                    ->setPaper('a4', 'portrait');
                $pdf->getDomPDF()->set_option("enable_php", true);
                // return $pdf->download('Contract ' . $comanda->transportator_contract . '.pdf');
                return $pdf->stream();

            case 'JCerereSiDistributie':
                $request->validate(['interval' => 'required']);
                $recoltariSangeDistribuite = RecoltareSange::with('produs')
                    ->whereHas('comanda', function ($query) use ($interval) {
                        $query->whereBetween('data', [strtok($interval, ','), strtok( '' )]);
                    })
                    ->get();
                $recoltariSangeDistribuiteInJudet = RecoltareSange::with('produs')
                    ->whereHas('comanda', function ($query) use ($interval) {
                        $query->whereIn('recoltari_sange_beneficiar_id', [1,2,3])
                            ->whereBetween('data', [strtok($interval, ','), strtok( '' )]);
                    })
                    ->get();
                $recoltariSangeDistribuiteCatreAlteCts = RecoltareSange::with('produs')
                    ->whereHas('comanda', function ($query) use ($interval) {
                        $query->whereNotIn('recoltari_sange_beneficiar_id', [1,2,3])
                            ->whereBetween('data', [strtok($interval, ','), strtok( '' )]);
                    })
                    ->get();
                $recoltariSangePrimite = RecoltareSange::with('produs')
                    ->whereHas('intrare', function ($query) use ($interval) {
                        $query->whereBetween('data', [strtok($interval, ','), strtok( '' )]);
                    })
                    ->get();

                // return view('rapoarte.export.JCerereSiDistributie', compact('recoltariSange', 'interval'));
                $pdf = \PDF::loadView('rapoarte.export.JCerereSiDistributie', compact('recoltariSangeDistribuite', 'recoltariSangeDistribuiteInJudet', 'recoltariSangeDistribuiteCatreAlteCts', 'recoltariSangePrimite', 'interval'))
                    ->setPaper('a4', 'portrait');
                $pdf->getDomPDF()->set_option("enable_php", true);
                // return $pdf->download('Contract ' . $comanda->transportator_contract . '.pdf');
                return $pdf->stream();


            default:
                    $query = RecoltareSange::
                        when($interval, function ($query, $interval) {
                            return $query->whereBetween('data', [strtok($interval, ','), strtok( '' )]);
                        })
                        ->latest();

                    $recoltariSange = $query->get();

                    return view('rapoarte.index', compact('recoltariSange', 'interval', 'produse'));
                break;
        }
    }

    public function stocuriPungiSange(Request $request)
    {
        $interval = $request->interval;

        $request->validate(['interval' => 'required']);
        $recoltariSange = RecoltareSange::with('produs', 'grupa')
            ->when($interval, function ($query, $interval) {
                return $query->whereDate('data', '<', [strtok($interval, ',')]);
            })
            ->where(function($query) use ($interval){
                $query->whereDoesntHave('comanda')
                    ->orWhereHas('comanda', function ($query) use ($interval) {
                        $query->whereDate('data', '>', [strtok($interval, ',')]);
                    });
            })
            ->where(function($query) use ($interval){
                $query->whereNull('rebut_data')
                    ->orwhereDate('rebut_data',  '>', [strtok($interval, ',')]);
            })
            ->where('recoltari_sange_produs_id', $request->produsId)
            ->get();

        return view('rapoarte.export.stocuriPungiSange', compact('recoltariSange', 'interval'));
        $pdf = \PDF::loadView('rapoarte.export.stocuriPungiSange', compact('recoltariSange', 'interval'))
            ->setPaper('a4', 'portrait');
        $pdf->getDomPDF()->set_option("enable_php", true);
        // return $pdf->download('Stocuri pungi sange.pdf');
        return $pdf->stream();
    }
}
