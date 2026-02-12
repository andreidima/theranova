<?php

namespace App\Http\Controllers\Bonusuri;

use App\Http\Controllers\Controller;
use App\Models\Bonus;
use App\Models\BonusIstoric;
use App\Models\FisaCaz;
use App\Models\Oferta;
use App\Models\User;
use App\Services\BonusCalculatorService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BonusController extends Controller
{
    public function index(Request $request): View
    {
        $canViewAll = $request->user()->hasRole('bonusuri.view_all');
        $canEdit = $request->user()->hasRole('bonusuri.edit');

        $month = $this->validatedMonth($request->input('month'));
        $monthStart = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $monthEnd = (clone $monthStart)->endOfMonth();

        $query = Bonus::query()
            ->with([
                'fisaCaz.pacient:id,nume,prenume',
                'oferta:id,fisa_caz_id,pret,acceptata',
                'user:id,name,email',
                'lucrare:id,denumire,cod',
                'istoric.user:id,name',
            ])
            ->where(function ($q) use ($monthStart, $monthEnd) {
                $q->whereBetween('data_plata', [$monthStart->toDateString(), $monthEnd->toDateString()])
                    ->orWhere(function ($inner) use ($monthStart, $monthEnd) {
                        $inner->whereNull('data_plata')
                            ->whereBetween('luna_merit', [$monthStart->toDateString(), $monthEnd->toDateString()]);
                    });
            });

        if ($canViewAll) {
            if ($request->filled('user_id')) {
                $query->where('user_id', (int) $request->input('user_id'));
            }
        } else {
            $query->where('user_id', $request->user()->id);
        }

        $sumar = [
            'total_bonus' => (float) (clone $query)->sum('bonus_total'),
            'total_platite' => (float) (clone $query)->where('status', Bonus::STATUS_PLATIT)->sum('bonus_total'),
            'total_neplatite' => (float) (clone $query)->where('status', '!=', Bonus::STATUS_PLATIT)->sum('bonus_total'),
        ];

        $bonusuri = $query
            ->orderByRaw('COALESCE(data_plata, luna_merit) desc')
            ->orderByDesc('id')
            ->paginate(50)
            ->withQueryString();

        return view('bonusuri.index', [
            'bonusuri' => $bonusuri,
            'sumar' => $sumar,
            'month' => $month,
            'canViewAll' => $canViewAll,
            'canEdit' => $canEdit,
            'statusuri' => Bonus::statusuri(),
            'users' => $canViewAll
                ? User::query()->select('id', 'name')->where('activ', 1)->orderBy('name')->get()
                : collect(),
            'selectedUserId' => $request->input('user_id'),
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
                    $q->where('acceptata', Oferta::STATUS_ACCEPTATA)
                        ->orderBy('created_at')
                        ->orderBy('id');
                },
            ])
            ->whereHas('oferte', function ($q) {
                $q->where('acceptata', Oferta::STATUS_ACCEPTATA);
            })
            ->where(function ($q) {
                $q->whereNull('protezare')
                    ->orWhereNull('facturat')
                    ->orWhere('facturat', 0);
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

    public function calculeaza(Request $request, BonusCalculatorService $bonusCalculatorService): RedirectResponse
    {
        if (!$request->user()->hasRole('bonusuri.edit')) {
            abort(403);
        }

        $rezultat = $bonusCalculatorService->calculeazaBonusuriEligibile($request->user()->id);

        return back()->with('status', 'Calcul finalizat. Fișe eligibile: ' . $rezultat['fise_eligibile']
            . ', bonusuri generate: ' . $rezultat['bonusuri_generate']
            . ', fișe marcate bonusat: ' . $rezultat['fise_bonusate']
            . ', fără interval: ' . $rezultat['fise_fara_interval'] . '.');
    }

    public function actualizeaza(Request $request, Bonus $bonus): RedirectResponse
    {
        if (!$request->user()->hasRole('bonusuri.edit')) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', Bonus::statusuri()),
            'bonus_fix' => 'required|integer|min:0|max:999999',
            'bonus_procent' => 'required|integer|min:0|max:100',
            'bonus_total' => 'nullable|integer|min:0|max:999999',
            'data_plata' => 'nullable|date',
            'observatii' => 'nullable|string|max:2000',
        ]);

        $statusVechi = $bonus->status;
        $valoareVeche = (int) $bonus->bonus_total;

        $bonusFix = (int) $validated['bonus_fix'];
        $bonusProcent = (int) $validated['bonus_procent'];
        $bonusTotalCalculat = (int) round($bonusFix + (((int) $bonus->valoare_oferta) * $bonusProcent / 100));

        $bonus->status = $validated['status'];
        $bonus->bonus_fix = $bonusFix;
        $bonus->bonus_procent = $bonusProcent;
        $bonus->bonus_total = isset($validated['bonus_total'])
            ? (int) $validated['bonus_total']
            : $bonusTotalCalculat;
        $bonus->observatii = $validated['observatii'] ?? null;

        if ($bonus->status === Bonus::STATUS_PLATIT) {
            $bonus->data_plata = !empty($validated['data_plata']) ? Carbon::parse($validated['data_plata'])->toDateString() : Carbon::today()->toDateString();
            $bonus->platit_de_user_id = $request->user()->id;
            if (empty($bonus->approved_at)) {
                $bonus->approved_at = now();
            }
        } else {
            $bonus->data_plata = !empty($validated['data_plata']) ? Carbon::parse($validated['data_plata'])->toDateString() : null;
            if ($bonus->status === Bonus::STATUS_APROBAT && empty($bonus->approved_at)) {
                $bonus->approved_at = now();
            }
        }

        $bonus->save();

        BonusIstoric::create([
            'bonus_id' => $bonus->id,
            'actiune' => 'actualizare_manuala',
            'status' => $bonus->status,
            'bonus_total' => $bonus->bonus_total,
            'data_plata' => $bonus->data_plata,
            'user_id' => $request->user()->id,
            'detalii' => 'Status: ' . $statusVechi . ' -> ' . $bonus->status
                . '; Bonus: ' . $valoareVeche . ' -> ' . (int) $bonus->bonus_total,
        ]);

        return back()->with('status', 'Bonusul a fost actualizat cu succes.');
    }

    protected function validatedMonth(?string $month): string
    {
        if (is_string($month) && preg_match('/^\d{4}\-\d{2}$/', $month)) {
            return $month;
        }

        return now()->format('Y-m');
    }
}
