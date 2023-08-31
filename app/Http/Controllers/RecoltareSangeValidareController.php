<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RecoltareSange;
use App\Models\RecoltareSangeRebut;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class RecoltareSangeValidareController extends Controller
{
    public function validare()
    {
        $rebuturi = RecoltareSangeRebut::get();

        return view('recoltariSangeValidari.validare', compact('rebuturi'));
    }

    public function axiosCautaPunga(Request $request)
    {
        $recoltariSange = RecoltareSange::with('produs', 'grupa', 'rebut')->where('cod', $request->cod)->get();

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
            // 'raspuns' => $request->actiune,
            'recoltariSangeGasite' => $recoltariSange,
        ]);
    }

    public function axiosModificaRebutPunga(Request $request)
    {
        $validator = Validator::make(['dataRebut' => $request->dataRebut], ['dataRebut' => 'date']);
        if (!$validator->passes()){
            return response()->json([
                'mesaj' => "Nu s-a putut face actualizarea pentru că formatul datei este greșit. Formatul datei trebui sa fie de tip '" . Carbon::today()->isoFormat('DD.MM.YYYY') . "'",
            ]);
        }

        $recoltareSange = RecoltareSange::where('id', $request->recoltareSangeId)->first();
        $recoltareSange->recoltari_sange_rebut_id = $request->rebutId;
        if($request->rebutId){
            $recoltareSange->rebut_data = Carbon::parse($request->dataRebut)->isoFormat('YYYY-MM-DD');
        } else {
            $recoltareSange->rebut_data = null;
        }
        $recoltareSange->save();

        $recoltariSange = RecoltareSange::with('produs', 'grupa', 'rebut')->where('cod', ($recoltareSange->cod ?? 'XXXXXXXXXX'))->get();

        return response()->json([
            // 'raspuns' => $request->actiune,
            'mesaj' => 'succes',
            'recoltariSangeGasite' => $recoltariSange,
        ]);
    }
}
