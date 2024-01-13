<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FisaCaz;
use App\Models\User;
use App\Models\Pacient;
use App\Models\DataMedicala;
use App\Models\Cerinta;

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
        $request->session()->forget('ofertaReturnUrl');

        $searchNume = $request->searchNume;
        $searchUserVanzari = $request->searchUserVanzari;
        $searchUserComercial = $request->searchUserComercial;
        $searchUserTehnic = $request->searchUserTehnic;

        $fiseCaz = FisaCaz::with('pacient', 'userVanzari', 'userComercial', 'userTehnic', 'oferte.fisiere', 'oferte.fisaCaz.pacient')
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
            ->orderBy('data', 'desc')
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
        $pacienti = Pacient::select('id', 'nume', 'prenume', 'telefon', 'localitate')->get();

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
        $this->validateRequest($request);

        $fisaCaz = FisaCaz::create($request->only(['data', 'compresie_manson', 'protezare', 'user_vanzari', 'user_comercial', 'user_tehnic', 'pacient_id', 'observatii']));

        foreach ($request->dateMedicale as $date) {
            $fisaCaz->dateMedicale()->save(DataMedicala::make($date));
        }
        foreach ($request->cerinte as $date) {
            $fisaCaz->cerinte()->save(Cerinta::make($date));
        }

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
        $pacienti = Pacient::select('id', 'nume', 'prenume', 'telefon', 'localitate')->get();

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
        $this->validateRequest($request);

        $fisaCaz->update($request->only(['data', 'compresie_manson', 'protezare',  'user_vanzari', 'user_comercial', 'user_tehnic', 'pacient_id', 'observatii']));

        foreach ($request->dateMedicale as $date) {
            $fisaCaz->dateMedicale()->first() ? $fisaCaz->dateMedicale()->first()->update($date) : $fisaCaz->dateMedicale()->save(DataMedicala::make($date));
        }
        foreach ($request->cerinte as $date) {
            $fisaCaz->cerinte()->first() ? $fisaCaz->cerinte()->first()->update($date) : $fisaCaz->cerinte()->save(Cerinta::make($date));
        }

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
        if ($fisaCaz->oferte->count() > 0){
            return back()->with('error', 'Nu poți șterge fișa caz a pacientului „' . ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') . '” pentru că are oferte atașate. Șterge mai întâi ofertele.');
        }

        $fisaCaz->delete();
        $fisaCaz->dateMedicale()->delete();
        $fisaCaz->cerinte()->delete();

        return back()->with('status', 'Fișa Caz pentru pacientul „' . ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') . '”  a fost ștearsă cu succes!');
    }

    /**
     * Validate the request attributes.
     *
     * @return array
     */
    protected function validateRequest(Request $request)
    {
        return $request->validate(
            [
                'data' => 'required',
                'compresie_manson' => '',
                'protezare' => '',
                'user_vanzari' => '',
                'user_comercial' => '',
                'user_tehnic' => '',
                'pacient_id' => 'required',
                'dateMedicale.*.greutate' => 'required|integer|min:10|max:300',
                'dateMedicale.*.parte_amputata' => 'required',
                'dateMedicale.*.amputatie' => 'required',
                'dateMedicale.*.nivel_de_activitate' => 'required',
                'dateMedicale.*.cauza_amputatiei' => 'required',
                'dateMedicale.*.a_mai_purtat_proteza' => 'required',
                'dateMedicale.*.tip_proteza' => 'required',
                'dateMedicale.*.alte_afectiuni' => 'nullable|max:2000',
                'dateMedicale.*.observatii' => 'nullable|max:2000',
                'cerinte.*.decizie_cas' => '',
                'cerinte.*.buget_disponibil' => 'nullable|integer|max:1000000',
                'cerinte.*.cerinte_particulare_1' => '',
                'cerinte.*.cerinte_particulare_2' => '',
                'cerinte.*.cerinte_particulare_3' => '',
                'cerinte.*.cerinte_particulare_4' => '',
                'cerinte.*.observatii' => 'nullable|max:2000',

            ],
            [
                'dateMedicale.*.greutate.required' => 'Câmpul Greutate este obligatoriu.',
                'dateMedicale.*.greutate.integer' => 'Câmpul greutate trebuie să fie un număr.',
                'dateMedicale.*.greutate.min' => 'Câmpul greutate trebuie să aibă valoarea minim 10.',
                'dateMedicale.*.greutate.max' => 'Câmpul greutate trebuie să aibă valoarea maxim 300.',
                'dateMedicale.*.parte_amputata.required' => 'Câmpul Parte amputată este obligatoriu.',
                'dateMedicale.*.amputatie.required' => 'Câmpul Amputație este obligatoriu.',
                'dateMedicale.*.nivel_de_activitate.required' => 'Câmpul Nivel de activitate este obligatoriu.',
                'dateMedicale.*.cauza_amputatiei.required' => 'Câmpul Cauza amputației este obligatoriu.',
                'dateMedicale.*.a_mai_purtat_proteza.required' => 'Câmpul A mai putat proteza este obligatoriu.',
                'dateMedicale.*.tip_proteza.required' => 'Câmpul Tip proteză este obligatoriu.',
                'dateMedicale.*.alte_afectiuni.max' => 'Câmpul alte afecțiuni trebuie să aibă maxim 2000 de caractere.',
                'dateMedicale.*.observatii.max' => 'Câmpul observații trebuie să aibă maxim 2000 de caractere.',
                'cerinte.*.buget_disponibil.max' => 'Câmpul buget disponibil trebuie să aibă valoarea maxim 1.000.000.',
                'cerinte.*.alte_afectiuni.max' => 'Câmpul alte afecțiuni trebuie să aibă maxim 2000 de caractere.',
                'cerinte.*.observatii.max' => 'Câmpul observații trebuie să aibă maxim 2000 de caractere.',
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

    public function stare(Request $request, FisaCaz $fisaCaz, $stare = null)
    {
        switch ($stare) {
            case 'deschide':
                $fisaCaz->stare = 1;
                break;
            case 'inchide':
                $fisaCaz->stare = 2;
                break;
            case 'anuleaza':
                $fisaCaz->stare = 3;
                break;
        }
        $fisaCaz->save();

        return back()->with('status', 'Fișa caz a pacientului „' . ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') . '” a fost ' . (($fisaCaz->stare === 1) ? 'deschisa' : (($fisaCaz->stare === 2) ? 'inchisa' : (($fisaCaz->stare === 3) ? 'anulata' : '' ))) . '!');
    }
}
