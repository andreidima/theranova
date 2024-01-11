<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pacient;
use App\Models\Apartinator;

class PacientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('pacientReturnUrl');

        $searchNume = $request->searchNume;
        $searchPrenume = $request->searchPrenume;
        $searchTelefon = $request->searchTelefon;

        $pacienti = Pacient::
            when($searchNume, function ($query, $searchNume) {
                foreach (explode(" ", $searchNume) as $cuvant){
                    $query->where(function ($query) use($cuvant) {
                        return $query->where('nume', 'like', '%' . $cuvant . '%')
                                ->orWhere('prenume', 'like', '%' . $cuvant . '%');
                    });
                }
                return $query;
            })
            ->when($searchPrenume, function ($query, $searchPrenume) {
                return $query->where('nume', 'like', '%' . $searchPrenume . '%');
            })
            ->when($searchTelefon, function ($query, $searchTelefon) {
                return $query->where('telefon', 'like', '%' . $searchTelefon . '%');
            })
            ->latest()
            ->simplePaginate(25);

        return view('pacienti.index', compact('pacienti', 'searchNume', 'searchPrenume', 'searchTelefon'));
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
        $this->validateRequest($request);

        $pacient = Pacient::create($request->except(['apartinatori', 'date']));

        if ($request->apartinatori) {
            foreach ($request->apartinatori as $apartinator) {
                $pacient->apartinatori()->save(Apartinator::make($apartinator));
            }
        }

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
        $this->validateRequest($request);

        $pacient->update($request->except(['apartinatori', 'date']));

        $pacient->apartinatori()->whereNotIn('id', collect($request->apartinatori)->where('id')->pluck('id'))->delete();
        if ($request->apartinatori) {
            foreach ($request->apartinatori as $date) {
                $pacient->apartinatori()->save(Apartinator::firstOrNew(['id' =>  $date['id']], $date));
            }
        }

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
        $pacient->apartinatori()->delete();

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

                'apartinatori.*.nume' => 'required|max:200',
                'apartinatori.*.prenume' => 'required|max:200',
                'apartinatori.*.telefon' => 'nullable|max:200',
                'apartinatori.*.email' => 'nullable|max:200',
                'apartinatori.*.grad_rudenie' => 'nullable|max:200',

                'observatii' => 'nullable|max:2000',
            ],
            [
                'apartinatori.*.nume.required' => 'Apartinatorul #:position, campul nume este obligatoriu',
                'apartinatori.*.nume.max' => 'Apartinatorul #:position, campul nume nu poate avea mai mult de 200 de caractere',
                'apartinatori.*.prenume.required' => 'Apartinatorul #:position, campul prenume este obligatoriu',
                'apartinatori.*.prenume.max' => 'Apartinatorul #:position, campul prenume nu poate avea mai mult de 200 de caractere.',
                'apartinatori.*.telefon.max' => 'Apartinatorul #:position, campul telefon nu poate avea mai mult de 200 de caractere.',
                'apartinatori.*.email.max' => 'Apartinatorul #:position, campul email nu poate avea mai mult de 200 de caractere.',
                'apartinatori.*.grad_rudenie.max' => 'Apartinatorul #:position, campul grad_rudenie nu poate avea mai mult de 200 de caractere.',

            ]
        );
    }
}
