<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RecoltareSange;
use App\Models\RecoltareSangeRebut;

class RaportController extends Controller
{
    public function index(Request $request)
    {
        // $request->session()->forget('raportReturnUrl');
        $interval = $request->interval;

        switch ($request->input('action')) {
            case 'recoltariSangeCtsvToate':
                $request->validate(['interval' => 'required']);
                $query = RecoltareSange::
                    with('produs', 'comanda')
                    ->when($interval, function ($query, $interval) {
                        return $query->whereBetween('data', [strtok($interval, ','), strtok( '' )]);
                    })
                    ->latest();
                $recoltariSange = $query->get();

                $pdf = \PDF::loadView('rapoarte.export.recoltariSangeCtsvToate', compact('recoltariSange', 'interval'))
                    ->setPaper('a4', 'portrait');
                $pdf->getDomPDF()->set_option("enable_php", true);
                // return $pdf->download('Contract ' . $comanda->transportator_contract . '.pdf');
                return $pdf->stream();

            case 'stocuriPungiSange':
                $recoltariSange = RecoltareSange::with('produs')->get();

                $pdf = \PDF::loadView('rapoarte.export.stocuriPungiSange', compact('recoltariSange'))
                    ->setPaper('a4', 'portrait');
                $pdf->getDomPDF()->set_option("enable_php", true);
                // return $pdf->download('Contract ' . $comanda->transportator_contract . '.pdf');
                return $pdf->stream();

            case 'G1Rebut':
                $request->validate(['interval' => 'required']);
                $recoltariSange = RecoltareSange::
                    with('rebut', 'produs')
                    ->whereNotNull('recoltari_sange_rebut_id')
                    ->when($interval, function ($query, $interval) {
                        return $query->whereBetween('data', [strtok($interval, ','), strtok( '' )]);
                    })
                    ->latest()
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
                    ->whereNotNull('recoltari_sange_rebut_id')
                    ->when($interval, function ($query, $interval) {
                        return $query->whereBetween('data', [strtok($interval, ','), strtok( '' )]);
                    })
                    ->latest()
                    ->get();
                $rebuturi = RecoltareSangeRebut::select('id', 'nume')->orderBy('nume')->get();

                // return view('rapoarte.export.G2RebutRepartitie', compact('recoltariSange', 'rebuturi', 'interval'));
                $pdf = \PDF::loadView('rapoarte.export.G2RebutRepartitie', compact('recoltariSange', 'rebuturi', 'interval'))
                    ->setPaper('a4', 'portrait');
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
                $recoltariSange = RecoltareSange::
                    with('produs')
                    ->whereNull('recoltari_sange_rebut_id')
                    ->when($interval, function ($query, $interval) {
                        return $query->whereBetween('data', [strtok($interval, ','), strtok( '' )]);
                    })
                    ->latest()
                    ->get();

                // return view('rapoarte.export.JCerereSiDistributie', compact('recoltariSange', 'interval'));
                $pdf = \PDF::loadView('rapoarte.export.JCerereSiDistributie', compact('recoltariSange', 'interval'))
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

                    return view('rapoarte.index', compact('recoltariSange', 'interval'));
                break;
        }
    }
}
