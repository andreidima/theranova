<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FisaCaz;
use App\Models\User;
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
        $searchUserVanzari = $request->searchUserVanzari;
        $searchUserComercial = $request->searchUserComercial;
        $searchUserTehnic = $request->searchUserTehnic;

        $fiseCaz = FisaCaz::with('pacient', 'userVanzari', 'userComercial', 'userTehnic')
            ->when($searchNume, function ($query, $searchNume) {
                foreach (explode(" ", $searchNume) as $cuvant){
                    $query->whereHas('pacient', function ($query) use($cuvant) {
                        $query->where(function ($query) use($cuvant) {
                            return $query->where('nume', 'like', '%' . $cuvant . '%')
                                    ->orWhere('prenume', 'like', '%' . $cuvant . '%');
                        });
                    });
                }
                return $query;
            })
            ->when($searchUserVanzari, function ($query, $searchUserVanzari) {
                $query->whereHas('userVanzari', function ($query) use ($searchUserVanzari) {
                    return $query->where('id', $searchUserVanzari);
                });
            })
            ->when($searchUserComercial, function ($query, $searchUserComercial) {
                $query->whereHas('userComercial', function ($query) use ($searchUserComercial) {
                    return $query->where('id', $searchUserComercial);
                });
            })
            ->when($searchUserTehnic, function ($query, $searchUserTehnic) {
                $query->whereHas('userTehnic', function ($query) use ($searchUserTehnic) {
                    return $query->where('id', $searchUserTehnic);
                });
            })
            ->latest()
            ->simplePaginate(25);

        $useri = User::select('id', 'name', 'role')->orderBy('name')->get();

        return view('fiseCaz.index', compact('fiseCaz', 'useri', 'searchNume', 'searchUserVanzari', 'searchUserComercial', 'searchUserTehnic'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $fisaCaz = new FisaCaz;

        // Daca a fost adaugat un pacient din fisaCaz, se revine in formularul fisaCaz si campurile trebuie sa se recompleteze automat
        $fisaCaz->fill($request->session()->pull('fisaCazRequest', []));

        $useri = User::select('id', 'name', 'role')->orderBy('name')->get();
        $pacienti = Pacient::select('id', 'nume', 'prenume', 'data_nastere', 'localitate')->get();

        $request->session()->get('fisaCazReturnUrl') ?? $request->session()->put('fisaCazReturnUrl', url()->previous());

        return view('fiseCaz.create', compact('fisaCaz', 'useri', 'pacienti'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fisaCaz = FisaCaz::create($this->validateRequestFisa($request));

        return redirect($request->session()->get('fisaCazReturnUrl') ?? ('/fise-caz'))->with('status', 'Fișa Caz pentru pacientul „' . ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') . '” a fost adăugată cu succes!');
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

        $useri = User::select('id', 'name', 'role')->orderBy('name')->get();
        $pacienti = Pacient::select('id', 'nume', 'prenume', 'data_nastere', 'localitate')->get();

        return view('fiseCaz.edit', compact('fisaCaz', 'useri', 'pacienti'));
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
        $fisaCaz->update($this->validateRequestFisa($request));

        return redirect($request->session()->get('fisaCazReturnUrl') ?? ('/fise-caz'))->with('status', 'Fișa Caz pentru pacientul „' . ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') . '” a fost modificată cu succes!');
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

        return back()->with('status', 'Fișa Caz pentru pacientul „' . ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') . '”  a fost ștearsă cu succes!');
    }

    /**
     * Validate the request attributes.
     *
     * @return array
     */
    protected function validateRequestFisa(Request $request)
    {
        return $request->validate(
            [
                'data' => 'required',
                'user_vanzari' => '',
                'user_comercial' => '',
                'user_tehnic' => '',
                'pacient_id' => 'required',
                // 'prenume' => 'required|max:200',
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
