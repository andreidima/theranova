<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FisaCaz;
use App\Models\Pacient;

class FisaCazController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('fisaCazReturnUrl');

        $searchNume = $request->searchNume;
        $searchPrenume = $request->searchPrenume;
        $searchTelefon = $request->searchTelefon;

        $fiseCaz = FisaCaz::
            // when($searchNume, function ($query, $searchNume) {
            //     foreach (explode(" ", $searchNume) as $cuvant){
            //         $query->where(function ($query) use($cuvant) {
            //             return $query->where('nume', 'like', '%' . $cuvant . '%')
            //                     ->orWhere('prenume', 'like', '%' . $cuvant . '%');
            //         });
            //     }
            //     return $query;
            // })
            // ->when($searchPrenume, function ($query, $searchPrenume) {
            //     return $query->where('nume', 'like', '%' . $searchPrenume . '%');
            // })
            // ->when($searchTelefon, function ($query, $searchTelefon) {
            //     return $query->where('telefon', 'like', '%' . $searchTelefon . '%');
            // })
            latest()
            ->simplePaginate(25);
// dd(session()->getOldInput());
        return view('fiseCaz.index', compact('fiseCaz', 'searchNume', 'searchPrenume', 'searchTelefon'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Daca a fost adaugat un pacient din fisaCaz, se revine in formularul fisaCaz si campurile trebuie sa se recompleteze automat
        // dd($request->session()->get('fisaCazRequest', ''));
        // $request->session()->forget('_old_input');
        // if ($request->session()->exists('fisaCazRequest')) {
        //     session()->put('_old_input', $request->session()->pull('fisaCazRequest', 'default'));
        //     if ($request->session()->exists('fisaCazPacientId')) {
        //         session()->put('_old_input.pacient_id', $request->session()->pull('fisaCazPacientId', ''));
        //     }
        // }
        $fisaCaz = new FisaCaz;
        // if ($request->session()->exists('fisaCazRequest')) {
            $fisaCaz->fill($request->session()->pull('fisaCazRequest', []));
        //     if ($request->session()->exists('fisaCazPacientId')) {
        //         $fisaCaz->pacient_id = $request->session()->pull('fisaCazPacientId', '');
        //     }
        // }
// dd(session()->getOldInput(), $request->session()->pull('fisaCazPacientId', ''));
        $pacienti = Pacient::select('id', 'nume', 'prenume', 'data_nastere', 'localitate')->get();

        $request->session()->get('fisaCazReturnUrl') ?? $request->session()->put('fisaCazReturnUrl', url()->previous());

        return view('fiseCaz.create', compact('fisaCaz', 'pacienti'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fisaCaz = FisaCaz::create($this->validateRequest($request));

        // return redirect($request->session()->get('fisaCazReturnUrl') ?? ('/fise-caz'))->with('status', 'FisaCazul „' . $pacient->nume . ' ' . $pacient->prenume . '” a fost adăugat cu succes!');
        return redirect($request->session()->get('fisaCazReturnUrl') ?? ('/fise-caz'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FisaCaz  $fisaCaz
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, FisaCaz $fisaCaz)
    {
        $request->session()->get('fisaCazReturnUrl') ?? $request->session()->put('fisaCazReturnUrl', url()->previous());

        return view('fiseCaz.show', compact('fisaCaz'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FisaCaz  $fisaCaz
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, FisaCaz $fisaCaz)
    {
        $request->session()->get('fisaCazReturnUrl') ?? $request->session()->put('fisaCazReturnUrl', url()->previous());

        return view('fiseCaz.edit', compact('fisaCaz'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FisaCaz  $fisaCaz
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FisaCaz $fisaCaz)
    {
        $fisaCaz->update($this->validateRequest($request));

        return redirect($request->session()->get('fisaCazReturnUrl') ?? ('/fise-caz'))->with('status', 'FisaCazul „' . $pacient->nume . ' ' . $pacient->prenume . '” a fost modificat cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FisaCaz  $fisaCaz
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, FisaCaz $fisaCaz)
    {
        $fisaCaz->delete();

        return back()->with('status', 'FisaCazul „' . $pacient->nume . ' ' . $pacient->prenume . '”  a fost șters cu succes!');
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

        return $request->validate(
            [
                'pacient_id' => 'required',
                'prenume' => 'required|max:200',
                // 'data_nastere' => '',
                // 'sex' => '',
                // 'adresa' => 'nullable|max:500',
                // 'localitate' => 'nullable|max:200',
                // 'judet' => 'nullable|max:200',
                // 'cod_postal' => 'nullable|max:200',
                // 'telefon' => 'nullable|max:200',
                // 'email' => 'nullable|max:200|email:rfc,dns',
                // 'observatii' => 'nullable|max:2000',
            ],
            [

            ]
        );
    }

    public function fisaCazAdaugaResursa(Request $request, $resursa = null)
    {
        $request->session()->put('fisaCazRequest', $request->all());

        switch($resursa){
            case 'pacient':
                $request->session()->put('pacientReturnUrl', url()->previous());
                return redirect('/pacienti/adauga');
                break;
        }

    }
}
