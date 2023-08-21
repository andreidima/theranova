<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RecoltareSange;

class RecoltareSangeValidareController extends Controller
{
    public function validare()
    {
        return view('recoltariSangeValidari.validare');
    }

    public function axiosCautaPunga(Request $request)
    {
        $recoltariSange = RecoltareSange::with('produs', 'grupa')->where('cod', $request->cod)->get();

        return response()->json([
            // 'raspuns' => $recoltariSange->count(),
            'recoltariSangeGasite' => $recoltariSange,
        ]);
    }

    public function axiosValideazaInvalideazaPunga(Request $request)
    {
        $recoltareSange = RecoltareSange::where('id', $request->recoltareSangeId)->first();

        switch ($request->actiune) {
            case "valideaza":
                $recoltareSange->validat = 1;
                $recoltareSange->save();
                break;
            case "invalideaza":
                $recoltareSange->validat = 0;
                $recoltareSange->save();
                break;
        }

        $recoltariSange = RecoltareSange::with('produs', 'grupa')->where('cod', $recoltareSange->cod)->get();

        return response()->json([
            // 'raspuns' => 'back',
            // 'raspuns' => $recoltareSange,
            // 'raspuns' => $request->actiune,
            'recoltariSangeGasite' => $recoltariSange,
        ]);
    }
}
