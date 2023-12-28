<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('userReturnUrl');

        $searchNume = $request->searchNume;

        $useri = User::
            when($searchNume, function ($query, $searchNume) {
                return $query->where('name', 'like', '%' . $searchNume . '%');
            })
            ->where('id', '>', 1) // se sare pentru user 1, Andrei Dima
            ->orderBy('role')
            ->simplePaginate(25);

        return view('useri.index', compact('useri', 'searchNume'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->session()->get('pacientReturnUrl') ?? $request->session()->put('pacientReturnUrl', url()->previous());

        return view('pacienti.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pacient = Pacient::create($this->validateRequest($request));

        // Daca pacientul a fost adaugat din formularul FisaCaz, se trimite in sesiune, pentru a fi folosita in fisaCaz
        if ($request->session()->exists('fisaCazRequest')) {
            $fisaCazRequest = $request->session()->put('fisaCazRequest.pacient_id', $pacient->id);
        }

        return redirect($request->session()->get('pacientReturnUrl') ?? ('/pacienti'))->with('status', 'Pacientul „' . $pacient->nume . ' ' . $pacient->prenume . '” a fost adăugat cu succes!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pacient  $pacient
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Pacient $pacient)
    {
        $request->session()->get('pacientReturnUrl') ?? $request->session()->put('pacientReturnUrl', url()->previous());

        return view('pacienti.show', compact('pacient'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pacient  $pacient
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Pacient $pacient)
    {
        $request->session()->get('pacientReturnUrl') ?? $request->session()->put('pacientReturnUrl', url()->previous());

        return view('pacienti.edit', compact('pacient'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pacient  $pacient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pacient $pacient)
    {
        $pacient->update($this->validateRequest($request));

        return redirect($request->session()->get('pacientReturnUrl') ?? ('/pacienti'))->with('status', 'Pacientul „' . $pacient->nume . ' ' . $pacient->prenume . '” a fost modificat cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pacient  $pacient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Pacient $pacient)
    {
        $pacient->delete();

        return back()->with('status', 'Pacientul „' . $pacient->nume . ' ' . $pacient->prenume . '” a fost șters cu succes!');
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
                'nume' => 'required|max:200',
                'prenume' => 'required|max:200',
                'data_nastere' => '',
                'sex' => '',
                'adresa' => 'nullable|max:500',
                'localitate' => 'nullable|max:200',
                'judet' => 'nullable|max:200',
                'cod_postal' => 'nullable|max:200',
                'telefon' => 'nullable|max:200',
                'email' => 'nullable|max:200|email:rfc,dns',
                'observatii' => 'nullable|max:2000',
            ],
            [

            ]
        );
    }
}
