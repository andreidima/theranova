<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RecoltareSange;

class RaportController extends Controller
{
    public function index(Request $request)
    {
        // $request->session()->forget('raportReturnUrl');
        // if ($request->interval)
        $searchInterval = $request->searchInterval;
        // $searchIntervalArray = explode(",", $searchInterval);
        // dd($request);
// echo strtok($searchInterval, ',');
// echo strtok( '' );

// dd($searchIntervalArray);
// echo $searchIntervalArray[0];
// echo $searchIntervalArray[1];

        $query = RecoltareSange::
            when($searchInterval, function ($query, $searchInterval) {
                // dd($searchInterval, strtok($searchInterval, ','), strtok( '' ));
                return $query->whereBetween('data', [strtok($searchInterval, ','), strtok( '' )]);
            })
            ->latest();
// dd($query);
        $recoltariSange = $query->get();
// dd($recoltariSange);
        return view('rapoarte.index', compact('recoltariSange', 'searchInterval'));
    }
}
