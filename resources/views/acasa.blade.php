@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 mb-5">
            <div class="card culoare2">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    Bine ai venit <b>{{ auth()->user()->name ?? '' }}</b>!
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        @php
            use Carbon\Carbon;
            // $comenziLunaCurenta = \App\Models\Comanda::select('id', 'transportator_valoare_contract', 'transportator_moneda_id', 'client_valoare_contract', 'client_moneda_id')
            //                                             ->whereDate('created_at', '>=', \Carbon\Carbon::today()->startOfMonth())->get();

            // $monede = \App\Models\Moneda::select('id', 'nume')->get();
            // $leiLunaCurenta = $comenziLunaCurenta->where('client_moneda_id', 1)->sum('client_valoare_contract') - $comenziLunaCurenta->where('transportator_moneda_id', 1)->sum('transportator_valoare_contract')
            $dataCurenta = Carbon::now();
            $lunaTrecuta = Carbon::now()->subMonthNoOverflow();
        @endphp
        <div class="col-md-4 mb-3">
            <div class="card culoare2">
                <div class="card-header text-center">Tip lucrare solicitată</div>
                <div class="card-body text-center d-flex justify-content-center">
                    @php
                        // $dateMedicaleAnulCurent = \App\Models\DataMedicala::with('fisaCaz:id,protezare')
                        //     ->select('id', 'fisa_caz_id', 'tip_proteza')
                        //     ->whereHas('fisaCaz', function($query) {
                        //         $query->whereYear('protezare', now());
                        //     })
                        //     ->get();
                        // $dateMedicaleLunaCurenta = \App\Models\DataMedicala::with('fisaCaz:id,protezare,data')
                        //     ->select('id', 'fisa_caz_id', 'tip_proteza')
                        //     ->whereHas('fisaCaz', function($query) {
                        //         $query->whereMonth('protezare', now())
                        //             ->whereYear('protezare', now());
                        //     })
                        //     ->get();
                        $fiseCazAnulCurent = \App\Models\FisaCaz::select('tip_lucrare_solicitata')
                            ->whereYear('protezare', now())
                            ->get();
                        $fiseCazLunaCurenta = \App\Models\FisaCaz::select('tip_lucrare_solicitata')
                            ->whereMonth('protezare', now())
                            ->whereYear('protezare', now())
                            ->get();
                    @endphp
                    <table>
                        <tr>
                            <th class="px-4">Lucrare</th>
                            <th class="px-4">{{ now()->isoFormat('MM.YYYY') }}</th>
                            <th class="px-4">{{ now()->year }}</th>
                        </tr>
                    @foreach ($fiseCazAnulCurent->unique('tip_lucrare_solicitata')->sortBy('tip_lucrare_solicitata') as $fisaCaz)
                        <tr>
                            <td class="text-start">
                                {{ $fisaCaz->tip_lucrare_solicitata }}
                            </td>
                            <th>
                                {{ $fiseCazLunaCurenta->where('tip_lucrare_solicitata', $fisaCaz->tip_lucrare_solicitata)->count() }}
                            </th>
                            <th>
                                {{ $fiseCazAnulCurent->where('tip_lucrare_solicitata', $fisaCaz->tip_lucrare_solicitata)->count() }}
                            </th>
                        </tr>
                    @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-4 mb-3">
            <div class="card culoare2">
                <div class="card-header text-center">Fișe caz - toate</div>
                <div class="card-body text-center">
                    <b class="fs-2">{{ \App\Models\FisaCaz::count() }}</b>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card culoare2">
                <div class="card-header text-center">Fișe caz - create {{ $dataCurenta->isoFormat('MMMM YYYY') }}</div>
                <div class="card-body text-center">
                    <b class="fs-2">{{ \App\Models\FisaCaz::whereMonth('created_at', $dataCurenta)->whereYear('created_at', $dataCurenta)->count() }}</b>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card culoare2">
                <div class="card-header text-center">Fișe caz - create {{ $lunaTrecuta->isoFormat('MMMM YYYY') }}</div>
                <div class="card-body text-center">
                    <b class="fs-2">{{ \App\Models\FisaCaz::whereMonth('created_at', $lunaTrecuta)->whereYear('created_at', $lunaTrecuta)->count() }}</b>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card culoare2">
                <div class="card-header text-center">Pacienți - toți</div>
                <div class="card-body text-center">
                    <b class="fs-2">{{ \App\Models\Pacient::count() }}</b>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card culoare2">
                <div class="card-header text-center">Pacienți - creați {{ $dataCurenta->isoFormat('MMMM YYYY') }}</div>
                <div class="card-body text-center">
                    <b class="fs-2">{{ \App\Models\Pacient::whereMonth('created_at', $dataCurenta)->whereYear('created_at', $dataCurenta)->count() }}</b>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card culoare2">
                <div class="card-header text-center">Pacienți - creați {{ $lunaTrecuta->isoFormat('MMMM YYYY') }}</div>
                <div class="card-body text-center">
                    <b class="fs-2">{{ \App\Models\Pacient::whereMonth('created_at', $lunaTrecuta)->whereYear('created_at', $lunaTrecuta)->count() }}</b>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

