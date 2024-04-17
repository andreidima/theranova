<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

use App\Models\FisaCaz;
use App\Models\User;
use App\Models\Pacient;
use App\Models\DataMedicala;
use App\Models\Cerinta;
use App\Models\Fisier;
use App\Models\Comanda;

use Carbon\Carbon;

class FisaCazController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget(['fisaCazReturnUrl', 'ofertaReturnUrl', 'comandaComponentaReturnUrl', 'comandaComponenteReturnUrl']);

        $searchNume = $request->searchNume;
        $searchInterval = $request->searchInterval;
        $searchProgramareAtelier = $request->searchProgramareAtelier;
        $searchTipLucrareSolicitata = $request->searchTipLucrareSolicitata;
        $searchUserVanzari = $request->searchUserVanzari;
        $searchUserComercial = $request->searchUserComercial;
        $searchUserTehnic = $request->searchUserTehnic;

// dd(FisaCaz::where('id', 217)->get()->first()->numarEmailuriFisaCazUserComercial());

        $fiseCaz = FisaCaz::with('pacient', 'userVanzari', 'userComercial', 'userTehnic', 'oferte.fisiere', 'oferte.fisaCaz.pacient', 'dateMedicale', 'comenziComponente', 'fisiereComanda', 'fisiereFisaMasuri', 'emailuriFisaCaz', 'emailuriOferta', 'emailuriComanda')
            // ->withCount('emailuriFisaCazUserVanzari', 'emailuriFisaCazUserComercial', 'emailuriFisaCazUserTehnic', 'emailuriOfertaUserVanzari', 'emailuriOfertaUserComercial', 'emailuriOfertaUserTehnic', 'emailuriComandaUserVanzari', 'emailuriComandaUserComercial', 'emailuriComandaUserTehnic')
            ->when($searchNume, function ($query, $searchNume) {
                foreach (explode(" ", $searchNume) as $cuvant){
                    $query->whereHas('pacient', function ($query) use($cuvant) {
                        $query->where(function ($query) use($cuvant) {
                            return $query->where('nume', 'like', '%' . $cuvant . '%')
                                    ->orWhere('prenume', 'like', '%' . $cuvant . '%')
                                    ->orwhere('telefon', 'like', '%' . $cuvant . '%');
                        });
                    });
                }
                return $query;
            })
            ->when($searchTipLucrareSolicitata, function ($query, $searchTipLucrareSolicitata) {
                // $query->whereHas('dateMedicale', function ($query) use ($searchTipLucrareSolicitata) {
                //     return $query->where('tip_proteza', $searchTipLucrareSolicitata);
                // });
                $query->where('tip_lucrare_solicitata', $searchTipLucrareSolicitata);
            })
            ->when($searchInterval, function ($query, $searchInterval) {
                return $query->whereBetween('protezare', [strtok($searchInterval, ','), strtok( '' )]);
            })
            ->when($searchProgramareAtelier, function ($query, $searchProgramareAtelier) {
                // return $query->whereDate('programare_atelier', $searchProgramareAtelier);
                return $query->whereBetween('programare_atelier', [strtok($searchProgramareAtelier, ','), Carbon::parse(strtok( '' ))->endOfDay()]);
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
            // ->simplePaginate(25);
            ->paginate(25)
            ->withQueryString();

        $useri = User::select('id', 'name', 'role')->orderBy('name')->get();

        return view('fiseCaz.index', compact('fiseCaz', 'useri', 'searchNume', 'searchInterval', 'searchProgramareAtelier', 'searchTipLucrareSolicitata', 'searchUserVanzari', 'searchUserComercial', 'searchUserTehnic'));
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

        $fisaCaz = FisaCaz::create($request->only(['data', 'tip_lucrare_solicitata', 'programare_atelier', 'compresie_manson', 'protezare', 'user_vanzari', 'user_comercial', 'user_tehnic', 'pacient_id', 'observatii']));

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
// dd($request);
        $fisaCaz->update($request->only(['data', 'tip_lucrare_solicitata', 'programare_atelier', 'compresie_manson', 'protezare',  'user_vanzari', 'user_comercial', 'user_tehnic', 'pacient_id', 'observatii']));

        foreach ($request->dateMedicale as $date) {
            $fisaCaz->dateMedicale()->first() ? $fisaCaz->dateMedicale()->first()->update($date) : $fisaCaz->dateMedicale()->save(DataMedicala::make($date));
        }
        foreach ($request->cerinte as $date) {
            $fisaCaz->cerinte()->first() ? $fisaCaz->cerinte()->first()->update($date) : $fisaCaz->cerinte()->save(Cerinta::make($date));
        }

        // Trimitere notificare pe email
        // $casuteEmail = [];
        // ($email = ($fisaCaz->userVanzari->email ?? null)) ? array_push($casuteEmail, $email) : '';
        // ($email = ($fisaCaz->userComercial->email ?? null)) ? array_push($casuteEmail, $email) : '';
        // ($email = ($fisaCaz->userTehnic->email ?? null)) ? array_push($casuteEmail, $email) : '';
        // $casuteEmail = implode(', ', $casuteEmail);
        // Mail::to($casuteEmail)->send(new \App\Mail\AdaugareModificareFisaCaz($fisaCaz));
        // \App\Models\MesajTrimisEmail::create([
        //     'referinta' => 1, // Fisa caz
        //     'referinta_id' => $fisaCaz->id,
        //     'categorie' => 'Ofertari',
        //     'subcategorie' => 'Modificare',
        //     'email' => $casuteEmail
        // ]);
        // dd($casuteEmail, implode(', ', $casuteEmail));

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
        if (!auth()->user()->hasRole("stergere")){
            return back()->with('error', 'Nu ai drepturi de ștergere.');
        }

        if ($fisaCaz->oferte->count() > 0){
            return back()->with('error', 'Nu poți șterge fișa caz a pacientului „' . ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') . '” pentru că are oferte atașate. Șterge mai întâi ofertele.');
        }else if ($fisaCaz->comenzi->count() > 0){
            return back()->with('error', 'Nu poți șterge fișa caz a pacientului „' . ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') . '” pentru că are comenzi atașate. Șterge mai întâi comenzile.');
        }

        $fisaCaz->delete();
        $fisaCaz->dateMedicale()->delete();
        $fisaCaz->cerinte()->delete();
        // $fisaCaz->comenziComponente()->delete();

        // Se sterge complet directorul fisaCaz cu tot ce contine acesta
        Storage::deleteDirectory('fiseCaz/' . $fisaCaz->id);

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
                'tip_lucrare_solicitata' => 'required',
                'programare_atelier' => '',
                'compresie_manson' => '',
                'protezare' => '',
                'user_vanzari' => '',
                'user_comercial' => '',
                'user_tehnic' => '',
                'pacient_id' => 'required',
                'dateMedicale.*.greutate' => 'required|integer|min:1|max:255',
                'dateMedicale.*.parte_amputata' => 'required',
                'dateMedicale.*.amputatie' => 'required',
                'dateMedicale.*.nivel_de_activitate' => 'required',
                'dateMedicale.*.cauza_amputatiei' => 'required',
                'dateMedicale.*.a_mai_purtat_proteza' => 'required',
                // 'dateMedicale.*.tip_proteza' => 'required',
                'dateMedicale.*.circumferinta_bont' => 'nullable|max:100',
                'dateMedicale.*.circumferinta_bont_la_nivel_perineu' => 'nullable|max:100',
                'dateMedicale.*.marime_picior' => 'nullable|max:100',
                'dateMedicale.*.marime_picior_valoare' => 'nullable|max:100',
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
                'dateMedicale.*.greutate.min' => 'Câmpul greutate trebuie să aibă valoarea minim 1.',
                'dateMedicale.*.greutate.max' => 'Câmpul greutate trebuie să aibă valoarea maxim 255.',
                'dateMedicale.*.parte_amputata.required' => 'Câmpul Parte amputată este obligatoriu.',
                'dateMedicale.*.amputatie.required' => 'Câmpul Amputație este obligatoriu.',
                'dateMedicale.*.nivel_de_activitate.required' => 'Câmpul Nivel de activitate este obligatoriu.',
                'dateMedicale.*.cauza_amputatiei.required' => 'Câmpul Cauza amputației este obligatoriu.',
                'dateMedicale.*.a_mai_purtat_proteza.required' => 'Câmpul A mai putat proteza este obligatoriu.',
                // 'dateMedicale.*.tip_proteza.required' => 'Câmpul Tip proteză este obligatoriu.',
                'dateMedicale.*.circumferinta_bont.max' => 'Câmpul Circumferință bont trebuie să aibă maxim 100 de caractere.',
                'dateMedicale.*.circumferinta_bont_la_nivel_perineu.max' => 'Câmpul Circumferință bont la nivel perineu trebuie să aibă maxim 100 de caractere.',
                'dateMedicale.*.marime_picior.max' => 'Câmpul Mărime picior trebuie să aibă maxim 100 de caractere.',
                'dateMedicale.*.marime_picior_valoare.max' => 'Câmpul Mărime picior valoare trebuie să aibă maxim 100 de caractere.',
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

    public function adaugaModificaFisaMasuri(Request $request, FisaCaz $fisaCaz)
    {
        $request->validate(
            [
                'fisiereFisaMasuri' => 'required',
                'fisiereFisaMasuri.*' => ['nullable',
                    File::types(['pdf', 'jpg'])
                        ->max(30 * 1024),
                    ],
            ]
        );

        // Fisiere Fisa Masuri
        // Daca exista fisiere in request, se sterge vechile fisiere si se salveaza cele de acum
        if ($request->file('fisiereFisaMasuri')) {
            // stergere fisiere vechi
            if ($fisaCaz->fisiereFisaMasuri->count() > 0){
                // Se sterge tot directorul cu toate fisierele din el
                Storage::deleteDirectory($fisaCaz->fisiereFisaMasuri->first()->cale);

                // Se sterge toate fisierele din baza de date
                $fisaCaz->fisiereFisaMasuri()->delete();
            }

            foreach ($request->file('fisiereFisaMasuri') as $fisier){
                $numeFisier = $fisier->getClientOriginalName();
                $cale = 'fiseCaz/' . $fisaCaz->id . '/fisaMasuri';
                if (Storage::exists($cale . '/' . $numeFisier)){
                    return back()->with('error', 'Există deja un fișier cu numele „' . $numeFisier . '”. Redenumește fișierul și încearcă din nou.');
                }
                try {
                    // se salveaza fisierul pe disk
                    Storage::putFileAs($cale, $fisier, $numeFisier);

                    // se salveaza fisierul in baza de date
                    $fisier = new Fisier;
                    $fisier->referinta = 3;
                    $fisier->referinta_id = $fisaCaz->id;
                    $fisier->cale = $cale;
                    $fisier->nume = $numeFisier;
                    $fisier->save();
                } catch (Exception $e) {
                    return back()->with('error', 'Fișierul nu a putut fi încărcat.');
                }
            }
        }

        return back()->with('status', 'Fișa măsuri pentru Fișa Caz a pacientului „' . ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '') . '”  a fost încărcată cu succes!');
    }

    // public function trimitePrinEmailCatreUtilizator(Request $request, FisaCaz $fisaCaz, $tipEmail=null, User $user)
    // {
    //     $validator = Validator::make(
    //         [
    //             'mesaj' => $request->mesaj,
    //             'email' => $user->email,
    //         ],
    //         [
    //             'mesaj' => 'nullable|max:2000',
    //             'email' => 'email:rfc,dns'
    //         ]);
    //     if ($validator->fails()) {
    //         return back()->withErrors($validator)->withInput();
    //     }

    //     $trimitereEmail = Mail::to($user->email)
    //         ->cc(['danatudorache@theranova.ro', 'adrianples@theranova.ro', 'andrei.dima@usm.ro'])
    //         ->send(new \App\Mail\FisaCaz($fisaCaz, $tipEmail, $request->mesaj, $user->name));

    //     $mesajTrimisEmail = \App\Models\MesajTrimisEmail::create([
    //         'referinta' => 1, // Fisa caz
    //         'referinta_id' => $fisaCaz->id,
    //         'referinta2' => 1, // User
    //         'referinta2_id' => $user->id,
    //         'tip' => (($tipEmail == "fisaCaz") ? '1' : (($tipEmail == "oferta") ? '2' : (($tipEmail == "comanda") ? '3' : ''))),
    //         'mesaj' => $request->mesaj,
    //         'email' => $user->email
    //     ]);

    //     return back()->with('status',' Emailul către ' . $user->name . ' a fost trimis cu succes.');
    // }

    public function trimitePrinEmailCatreUtilizatori(Request $request, FisaCaz $fisaCaz, $tipEmail, Comanda $comanda)
    {
        $validator = Validator::make(
            [
                'mesaj' => $request->mesaj,
            ],
            [
                'mesaj' => 'nullable|max:2000',
            ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $usersEmails = [];
        ($fisaCaz->userVanzari->email ?? null) ? array_push($usersEmails, $fisaCaz->userVanzari->email) : '';
        ($fisaCaz->userComercial->email ?? null) ? array_push($usersEmails, $fisaCaz->userComercial->email) : '';
        ($fisaCaz->userTehnic->email ?? null) ? array_push($usersEmails, $fisaCaz->userTehnic->email) : '';

        if (count($usersEmails) == 0){
            return back()->with('error', 'Nu există adrese de email către care să se trimită mesajul');
        }
// dd($fisaCaz, $tipEmail, $usersEmails, $request->mesaj);
        $trimitereEmail = Mail::to($usersEmails)
            ->cc(['danatudorache@theranova.ro', 'adrianples@theranova.ro'])
            ->send(new \App\Mail\FisaCaz($fisaCaz, $tipEmail, $request->mesaj, $comanda));

        $mesajTrimisEmail = \App\Models\MesajTrimisEmail::create([
            'referinta' => $comanda->id ? 2 : 1, // Daca se trimie comanda in request, atunci este pentru comenzi, daca nu este pentru fise caz
            'referinta_id' => $comanda->id ? $comanda->id : $fisaCaz->id, // Daca se trimie comanda in request, atunci este pentru comenzi, daca nu este pentru fise caz
            'tip' =>
                (
                    ($tipEmail == "fisaCaz") ?
                        '1'
                        :
                        (
                            ($tipEmail == "oferta") ?
                                '2'
                                :
                                (
                                    ($tipEmail == "comanda") ?
                                        '3'
                                        :
                                        (
                                            ($tipEmail == "comandaVersiuneNoua") ?
                                                '7'
                                                :
                                                ''
                                        )
                                )
                        )
                ),
            'mesaj' => $request->mesaj,
            'email' => implode(', ', $usersEmails)
        ]);

        return back()->with('status',' Emailul a fost trimis cu succes.');
    }

    public function contractPdf(Request $request, FisaCaz $fisaCaz)
    {
        $pdf = \PDF::loadView('fiseCaz.export.contractPdf', compact('fisaCaz'))
            ->setPaper('a4', 'portrait');
        $pdf->getDomPDF()->set_option("enable_php", true);
        return $pdf->stream();
    }

    public function toateHtml()
    {
        $fiseCaz = FisaCaz::with('pacient:id,nume,prenume,localitate,judet,telefon,cum_a_aflat_de_theranova', 'pacient.apartinatori:pacient_id,nume,prenume,telefon', 'userVanzari:id,name', 'userTehnic:id,name', 'cerinte:fisa_caz_id,sursa_buget', 'ofertaAcceptata:fisa_caz_id,pret')
            ->select('id', 'tip_lucrare_solicitata', 'user_vanzari', 'user_tehnic', 'pacient_id', 'protezare')
            ->orderBy('protezare', 'asc')
            ->get();

        return view('fiseCaz.export.toateHtml', compact('fiseCaz'));
    }
}
