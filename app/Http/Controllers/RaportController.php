<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RecoltareSange;

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
