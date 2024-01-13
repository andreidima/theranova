<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

use App\Models\Fisier;

class FisierController extends Controller
{
    public function deschideDescarca(Fisier $fisier)
    {
        //This method will look for the file and get it from drive
        $path = $fisier->cale . '/' . $fisier->nume;
        try {
            $file = Storage::disk('local')->get($path);
            $type = Storage::disk('local')->mimeType($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            return $response;
        } catch (FileNotFoundException $exception) {
            abort(404);
        }
    }
}
