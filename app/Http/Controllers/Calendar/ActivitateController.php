<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Calendar\Activitate;
use App\Models\Calendar\Calendar;
use App\Models\FisaCaz;
use App\Models\InformatiiGenerale;

class ActivitateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('calendarActivitateReturnUrl');

        if (!str_contains(url()->current(), 'mod-afisare' )){ // This is the standard index
            $searchDescriere = $request->searchDescriere;

            $activitati = Activitate::
                when($searchDescriere, function ($query, $searchDescriere) {
                    return $query->where('descriere', 'like', '%' . $searchDescriere . '%');
                })
                ->latest()
                ->simplePaginate(25);

            return view('calendar.activitati.index', compact('activitati', 'searchDescriere'));
        } elseif (str_contains(url()->current(), 'mod-afisare-lunar' )){
            $searchLunaCalendar = $request->searchLunaCalendar ? Carbon::parse($request->searchLunaCalendar) : Carbon::today();
            $searchCalendareSelectate = $request->searchCalendareSelectate ?? Calendar::select('id')->pluck('id')->toArray();

            // If is pressed one of the buttons to change the calendar month
            if ($request->action){
                if ($request->action === "previousMonth"){
                    $searchLunaCalendar->subMonthNoOverflow();
                }else if ($request->action === "nextMonth"){
                    $searchLunaCalendar->addMonthNoOverflow();
                }
            }

            $activitatiPeMaiMulteZile = Activitate::with('calendar')
                ->when($searchLunaCalendar, function ($query, $searchLunaCalendar) {
                    return $query->where(function ($query) use ($searchLunaCalendar) {
                        $query->whereDate('data_inceput', '>=', Carbon::parse($searchLunaCalendar)->startOfMonth()->startOfWeek())
                            ->orWhereDate('data_sfarsit', '<', Carbon::parse($searchLunaCalendar)->endOfMonth()->endOfWeek());
                    });
                })
                ->whereIn('calendar_id', $searchCalendareSelectate)
                ->whereRaw('DATE(data_inceput) <> DATE(data_sfarsit)')
                // ->orderBy('data_inceput')
                // ->orderByRaw(DB::raw("
                //         case when salariat like '%revisal%' then 0 else 1 end ASC,
                //         case when salariat like '%situatie%' then 0 else 1 end ASC,
                //         case when salariat like '%3 luni%' then 0 else 1 end ASC,
                //         case when salariat like '%3luni%' then 0 else 1 end ASC,
                //         case when salariat like '%6 luni%' then 0 else 1 end ASC,
                //         case when salariat like '%6luni%' then 0 else 1 end ASC,
                //         case when
                //             data_incetare like '%înc%' or
                //             data_incetare like '%lip%' or
                //             data_incetare like '%susp%' or
                //             data_incetare like '%c.c.c%' or
                //             data_incetare like '%ccc%' or
                //             data_incetare like '%cm%'
                //         then 0 else 1 end DESC
                //     "))
                ->orderByRaw("
                        case when cazare like 'Apartament 1' then 0 else 1 end ASC,
                        case when cazare like 'Apartament 2' then 0 else 1 end ASC,
                        case when cazare like 'Apartament 3' then 0 else 1 end ASC,
                        case when calendar_id like '3' then 0 else 1 end ASC,
                        case when calendar_id like '1' then 0 else 1 end ASC,
                        case when calendar_id like '2' then 0 else 1 end ASC,
                        case when calendar_id like '4' then 0 else 1 end ASC
                    ")
                ->orderBy('data_inceput')
                ->get();


            $activitatiPeOZi = Activitate::with('calendar')
                ->whereNotIn('id', $activitatiPeMaiMulteZile->pluck('id'))
                ->when($searchLunaCalendar, function ($query, $searchLunaCalendar) {
                    return $query->where(function ($query) use ($searchLunaCalendar) {
                        $query->whereDate('data_inceput', '>=', Carbon::parse($searchLunaCalendar)->startOfMonth()->startOfWeek())
                            ->orWhereDate('data_sfarsit', '<', Carbon::parse($searchLunaCalendar)->endOfMonth()->endOfWeek());
                    });
                })
                ->whereIn('calendar_id', $searchCalendareSelectate)
                ->orderByRaw("
                        case when cazare like 'Apartament 1' then 0 else 1 end ASC,
                        case when cazare like 'Apartament 2' then 0 else 1 end ASC,
                        case when cazare like 'Apartament 3' then 0 else 1 end ASC,
                        case when calendar_id like '3' then 0 else 1 end ASC,
                        case when calendar_id like '1' then 0 else 1 end ASC,
                        case when calendar_id like '2' then 0 else 1 end ASC,
                        case when calendar_id like '4' then 0 else 1 end ASC
                    ")
                ->orderBy('data_inceput')
                ->get();

            $calendare = Calendar::all();

            return view('calendar.activitati.index', compact(
                'activitatiPeMaiMulteZile',
                'activitatiPeOZi',
                'calendare',
                'searchLunaCalendar',
                'searchCalendareSelectate'
            ));
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $fisaCaz = null)
    {
        $request->session()->get('calendarActivitateReturnUrl') ?? $request->session()->put('calendarActivitateReturnUrl', url()->previous());

        $activitate = new Activitate;

        // If the request is comming from fise caz, the we create the description and put the fisaCaz id in activity
        if ($fisaCaz) {
            $fisaCaz = FisaCaz::find($fisaCaz);

            // First are created the acronyms for users name
            $words = explode(" ", ($fisaCaz->userTehnic->name ?? ''));
            $userTehnic = ($words[0] ?? '') . ' ' . mb_substr(($words[1] ?? ''), 0, 1);
            $words = explode(" ", ($fisaCaz->userVanzari->name ?? ''));
            $userVanzari = ($words[0] ?? '') . ' ' . mb_substr(($words[1] ?? ''), 0, 1);

            $activitate->fisa_caz_id = $fisaCaz->id;
            $activitate->descriere = ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '')
                                        . ', ' . $fisaCaz->tip_lucrare_solicitata
                                        . ' - ' . $userTehnic . '/' . $userVanzari;

            // Lucrarile cu tehnicieni Ionut Miron si Alex Oprea sunt pe Bucuresti, altfel se pune Oradea
            if (str_contains($activitate->descriere, 'Ionut M') || str_contains($activitate->descriere, 'Alex O')){
                $activitate->calendar_id = 1; // Bucuresti
            } else {
                $activitate->calendar_id = 3; // Oradea
            }
        }

        // The curent user email is added automatically to mementouri_emailuri
        $activitate->mementouri_emailuri = auth()->user()->email ?? '';

        $calendare = Calendar::all();
        $coduriApartamente = $this->getCoduriApartamente();

        return view('calendar.activitati.create', compact('activitate', 'calendare', 'coduriApartamente'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $activitate = Activitate::create($this->validateRequest($request));

        return redirect($request->session()->get('calendarActivitateReturnUrl') ?? ('/calendar/activitati'))->with('status', 'Activitatea „' . $activitate->descriere . '” a fost adăugată cu succes!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Activitate  $activitate
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Activitate $activitate)
    {
        $request->session()->get('calendarActivitateReturnUrl') ?? $request->session()->put('calendarActivitateReturnUrl', url()->previous());

        return view('calendar.activitati.show', compact('activitate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Activitate  $activitate
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Activitate $activitate)
    {
        $request->session()->get('calendarActivitateReturnUrl') ?? $request->session()->put('calendarActivitateReturnUrl', url()->previous());

        $calendare = Calendar::all();
        $coduriApartamente = $this->getCoduriApartamente();

        return view('calendar.activitati.edit', compact('activitate', 'calendare', 'coduriApartamente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Activitate  $activitate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Activitate $activitate)
    {
        $activitate->update($this->validateRequest($request));

        return redirect($request->session()->get('calendarActivitateReturnUrl') ?? ('/calendar/activitati'))->with('status', 'Activitatea „' . $activitate->descriere . '” a fost modificată cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Activitate  $activitate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Activitate $activitate)
    {
        // if (!auth()->user()->hasRole("stergere")){
        //     return back()->with('error', 'Nu ai drepturi de ștergere.');
        // }

        $activitate->delete();

        return back()->with('status', 'Activitatea „' . $activitate->descriere . '” a fost ștearsă cu succes!');
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
                'calendar_id' => 'required',
                'fisa_caz_id' => '',
                'descriere' => 'required|max:500',
                'data_inceput' => 'required',
                'data_sfarsit' => '',
                'cazare' => 'nullable|max:500',
                'observatii' => 'nullable|max:2000',
                'mementouri_zile' => ['nullable' , 'max:500',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($value){
                            $mementouriZile = preg_split ("/\,/", $value);
                            foreach ($mementouriZile as $mementoZi){
                                if (!(intval($mementoZi) == $mementoZi)){
                                    $fail('Câmpul „Mementouri - zile” nu este completat corect');
                                }elseif ($mementoZi < 0){
                                    $fail('Câmpul „Mementouri - zile” nu poate conține valori negative');
                                }elseif ($mementoZi > 100){
                                    $fail('Câmpul „Mementouri - zile” nu poate conține valori mai mari de 100');
                                }
                            }
                        }
                    }],
                'mementouri_emailuri' => ['required_with:mementouri_zile', 'max:500',
                    function ($attribute, $value, $fail) {
                        if ($value){
                            $emails = array_map('trim', explode(',', $value));
                            $validator = Validator::make(['emails' => $emails], ['emails.*' => 'required|email:rfc,dns']);
                            if ($validator->fails()) {
                                $fail('Câmpul Mementouri emailuri nu are toate emailurile valide.');
                            }
                        }
                    }],
            ],
            [
            ]
        );
    }

    /**
     * Retrieve apartment codes keyed by apartment label.
     */
    protected function getCoduriApartamente()
    {
        return InformatiiGenerale::where('variabila', 'like', 'cod_apartament_%')
            ->orderBy('variabila')
            ->get()
            ->mapWithKeys(function (InformatiiGenerale $informatie) {
                $numarApartament = preg_replace('/[^0-9]/', '', $informatie->variabila);

                if (!$numarApartament) {
                    return [];
                }

                return ['Apartament ' . $numarApartament => $informatie->valoare];
            })
            ->filter();
    }
}
