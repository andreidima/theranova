<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Calendar\Activitate;
use App\Models\Calendar\Calendar;
use App\Models\FisaCaz;
use Carbon\Carbon;

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

            $activitatiPeMaiMulteZile = Activitate::
                when($searchLunaCalendar, function ($query, $searchLunaCalendar) {
                    return $query->where(function ($query) use ($searchLunaCalendar) {
                        $query->whereDate('data_inceput', '>=', Carbon::parse($searchLunaCalendar)->startOfMonth()->startOfWeek())
                            ->orWhereDate('data_sfarsit', '<', Carbon::parse($searchLunaCalendar)->endOfMonth()->endOfWeek());
                    });
                })
                ->whereIn('calendar_id', $searchCalendareSelectate)
                ->whereRaw('DATE(data_inceput) <> DATE(data_sfarsit)')
                ->orderBy('data_inceput')
                ->get();


            $activitatiPeOZi = Activitate::
                whereNotIn('id', $activitatiPeMaiMulteZile->pluck('id'))
                ->when($searchLunaCalendar, function ($query, $searchLunaCalendar) {
                    return $query->where(function ($query) use ($searchLunaCalendar) {
                        $query->whereDate('data_inceput', '>=', Carbon::parse($searchLunaCalendar)->startOfMonth()->startOfWeek())
                            ->orWhereDate('data_sfarsit', '<', Carbon::parse($searchLunaCalendar)->endOfMonth()->endOfWeek());
                    });
                })
                ->whereIn('calendar_id', $searchCalendareSelectate)
                ->orderBy('data_inceput')
                ->get();

            $calendare = Calendar::all();

            return view('calendar.activitati.index', compact('activitatiPeMaiMulteZile', 'activitatiPeOZi', 'calendare', 'searchLunaCalendar', 'searchCalendareSelectate'));
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
            $activitate->fisa_caz_id = $fisaCaz->id;
            $activitate->descriere = 'Pacient ' . ($fisaCaz->pacient->nume ?? '') . ' ' . ($fisaCaz->pacient->prenume ?? '')
                                        . ', tehnician ' . ($fisaCaz->userTehnic->name ?? '')
                                        . ', vanzari ' . ($fisaCaz->userVanzari->name ?? '');
            // dd ($fisaCaz, $activitate);
        } else {
            // dd ('nu');
        }

        $calendare = Calendar::all();

        return view('calendar.activitati.create', compact('activitate', 'calendare'));
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

        return view('calendar.activitati.edit', compact('activitate', 'calendare'));
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
            ],
            [
            ]
        );
    }
}
