<?php

namespace App\Http\Controllers\Bonusuri;

use App\Http\Controllers\Controller;
use App\Models\FisaCaz;
use App\Models\Oferta;
use App\Models\User;
use App\Services\BonusCalculatorService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class BonusController extends Controller
{
    public function index(Request $request, BonusCalculatorService $bonusCalculatorService): View
    {
        $canViewAll = $request->user()->hasRole('bonusuri.view_all');

        $month = $this->validatedMonth($request->input('month'));
        $monthStart = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $selectedUserId = $canViewAll ? (int) $request->input('user_id', 0) : 0;

        $query = FisaCaz::query()
            ->with([
                'pacient:id,nume,prenume',
                'userVanzari:id,name,email',
                'userTehnic:id,name,email',
                'lucrare:id,denumire,cod,activ',
                'latestDateMedicale' => function ($q) {
                    $q->select('date_medicale.id', 'date_medicale.fisa_caz_id', 'date_medicale.amputatie');
                },
                'oferte' => function ($q) {
                    $q->select('oferte.id', 'oferte.fisa_caz_id', 'oferte.pret', 'oferte.acceptata', 'oferte.created_at')
                        ->where('oferte.acceptata', Oferta::STATUS_ACCEPTATA)
                        ->orderBy('oferte.created_at')
                        ->orderBy('oferte.id');
                },
            ])
            ->whereDate('luna_bonus', $monthStart->toDateString())
            ->whereHas('oferte', function ($q) {
                $q->where('oferte.acceptata', Oferta::STATUS_ACCEPTATA);
            });

        if (!$canViewAll) {
            $query->where(function ($q) use ($request) {
                $q->where('user_vanzari', $request->user()->id)
                    ->orWhere('user_tehnic', $request->user()->id);
            });
        } elseif ($selectedUserId > 0) {
            $query->where(function ($q) use ($selectedUserId) {
                $q->where('user_vanzari', $selectedUserId)
                    ->orWhere('user_tehnic', $selectedUserId);
            });
        }

        $fiseCaz = $query->orderByDesc('id')->get();

        $rows = collect();

        foreach ($fiseCaz as $fisaCaz) {
            $ofertaAcceptata = $fisaCaz->oferte->first();
            if (!$ofertaAcceptata) {
                continue;
            }

            $lucrare = $fisaCaz->lucrare;
            if (!$lucrare) {
                $lucrare = $bonusCalculatorService->rezolvaLucrarePentruFisa($fisaCaz);
            }
            if (!$lucrare || !(bool) $lucrare->activ) {
                continue;
            }

            if (empty($fisaCaz->luna_bonus)) {
                continue;
            }

            $valoareOferta = (int) round((float) ($ofertaAcceptata->pret ?? 0));
            $lunaBonusDate = Carbon::parse($fisaCaz->luna_bonus)->startOfMonth();
            $amputatieFisa = $fisaCaz->latestDateMedicale->amputatie ?? null;
            $interval = $bonusCalculatorService->gasesteIntervalBonus($lucrare->id, $valoareOferta, $lunaBonusDate, $amputatieFisa);

            if (!$interval) {
                continue;
            }

            $roluri = [
                'vanzari' => (int) ($fisaCaz->user_vanzari ?? 0),
                'tehnic' => (int) ($fisaCaz->user_tehnic ?? 0),
            ];

            foreach ($roluri as $rol => $userId) {
                if ($userId <= 0) {
                    continue;
                }

                if (!$canViewAll && $userId !== (int) $request->user()->id) {
                    continue;
                }

                if ($canViewAll && $selectedUserId > 0 && $selectedUserId !== $userId) {
                    continue;
                }

                $bonusFix = (int) $interval->bonus_fix;
                $bonusProcent = (int) $interval->bonus_procent;
                $bonusTotal = $bonusCalculatorService->calculeazaBonusTotal($valoareOferta, $bonusFix, $bonusProcent);

                $rows->push([
                    'fisa_caz_id' => (int) $fisaCaz->id,
                    'pacient_nume' => (string) ($fisaCaz->pacient->nume ?? ''),
                    'pacient_prenume' => (string) ($fisaCaz->pacient->prenume ?? ''),
                    'user_id' => $userId,
                    'user_name' => $rol === 'vanzari'
                        ? (string) ($fisaCaz->userVanzari->name ?? '-')
                        : (string) ($fisaCaz->userTehnic->name ?? '-'),
                    'rol' => $rol,
                    'lucrare_denumire' => (string) $lucrare->denumire,
                    'amputatie' => $bonusCalculatorService->normalizeAmputatie($interval->amputatie) ?? 'Toate amputatiile',
                    'valoare_oferta' => $valoareOferta,
                    'bonus_fix' => $bonusFix,
                    'bonus_procent' => $bonusProcent,
                    'bonus_total' => $bonusTotal,
                    'luna_bonus' => $lunaBonusDate->toDateString(),
                ]);
            }
        }

        $rows = $rows
            ->sortBy([
                ['pacient_nume', 'asc'],
                ['pacient_prenume', 'asc'],
                ['fisa_caz_id', 'asc'],
                ['rol', 'asc'],
            ])
            ->values();

        $sumar = [
            'total_bonus' => (int) $rows->sum('bonus_total'),
            'pozitii' => (int) $rows->count(),
            'fise_unice' => (int) $rows->pluck('fisa_caz_id')->unique()->count(),
        ];

        $perPage = 50;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $rowsPage = $rows->slice(($page - 1) * $perPage, $perPage)->values();

        $paginator = new LengthAwarePaginator(
            $rowsPage,
            $rows->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('bonusuri.index', [
            'rows' => $paginator,
            'sumar' => $sumar,
            'month' => $month,
            'canViewAll' => $canViewAll,
            'users' => $canViewAll
                ? User::query()->select('id', 'name')->where('activ', 1)->orderBy('name')->get()
                : collect(),
            'selectedUserId' => $selectedUserId > 0 ? $selectedUserId : null,
        ]);
    }

    public function situatii(Request $request): View
    {
        $canViewAll = $request->user()->hasRole('bonusuri.view_all');

        $query = FisaCaz::query()
            ->with([
                'pacient:id,nume,prenume',
                'userVanzari:id,name',
                'userTehnic:id,name',
                'oferte' => function ($q) {
                    $q->where('oferte.acceptata', Oferta::STATUS_ACCEPTATA)
                        ->orderBy('oferte.created_at')
                        ->orderBy('oferte.id');
                },
            ])
            ->whereHas('oferte', function ($q) {
                $q->where('oferte.acceptata', Oferta::STATUS_ACCEPTATA);
            })
            ->where(function ($q) {
                $q->whereNull('protezare')
                    ->orWhereNull('luna_bonus');
            });

        if (!$canViewAll) {
            $query->where(function ($q) use ($request) {
                $q->where('user_vanzari', $request->user()->id)
                    ->orWhere('user_tehnic', $request->user()->id);
            });
        }

        $fiseCaz = $query
            ->orderByDesc('id')
            ->paginate(100)
            ->withQueryString();

        return view('bonusuri.situatii', [
            'fiseCaz' => $fiseCaz,
            'canViewAll' => $canViewAll,
        ]);
    }

    protected function validatedMonth(?string $month): string
    {
        if (is_string($month) && preg_match('/^\d{4}\-\d{2}$/', $month)) {
            return $month;
        }

        return now()->format('Y-m');
    }
}
