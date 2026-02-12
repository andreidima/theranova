<?php

namespace App\Services;

use App\Models\Bonus;
use App\Models\BonusIstoric;
use App\Models\FisaCaz;
use App\Models\Lucrare;
use App\Models\LucrareBonusInterval;
use App\Models\Oferta;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class BonusCalculatorService
{
    /**
     * @return array<string, int>
     */
    public function calculeazaBonusuriEligibile(?int $initiatorUserId = null): array
    {
        $rezultat = [
            'fise_eligibile' => 0,
            'bonusuri_generate' => 0,
            'fise_bonusate' => 0,
            'fise_fara_interval' => 0,
            'fise_fara_oferta_acceptata' => 0,
            'fise_lucrare_inactiva' => 0,
        ];

        $fiseEligibile = FisaCaz::query()
            ->with([
                'oferte' => function ($query) {
                    $query->where('acceptata', Oferta::STATUS_ACCEPTATA)
                        ->orderBy('created_at')
                        ->orderBy('id');
                },
            ])
            ->where(function ($query) {
                $query->whereNull('bonusat')
                    ->orWhere('bonusat', 0);
            })
            ->whereNotNull('protezare')
            ->where('facturat', 1)
            ->whereHas('oferte', function ($query) {
                $query->where('acceptata', Oferta::STATUS_ACCEPTATA);
            })
            ->get();

        $rezultat['fise_eligibile'] = $fiseEligibile->count();

        foreach ($fiseEligibile as $fisaCaz) {
            $ofertaAcceptata = $fisaCaz->oferte->first();
            if (!$ofertaAcceptata) {
                $rezultat['fise_fara_oferta_acceptata']++;
                continue;
            }

            $lucrare = $this->rezolvaLucrarePentruFisa($fisaCaz);
            if (!$lucrare) {
                $rezultat['fise_fara_interval']++;
                continue;
            }
            if (!(bool) $lucrare->activ) {
                $rezultat['fise_lucrare_inactiva']++;
                continue;
            }

            $valoareOferta = (int) round((float) ($ofertaAcceptata->pret ?? 0));
            $interval = $this->gasesteIntervalBonus($lucrare->id, $valoareOferta, Carbon::parse($fisaCaz->protezare));
            if (!$interval) {
                $rezultat['fise_fara_interval']++;
                continue;
            }

            $bonusuriCreatePeFisa = 0;
            foreach ($this->roluriBonusabile($fisaCaz) as $rolInFisa => $userId) {
                $existing = Bonus::query()
                    ->where('fisa_caz_id', $fisaCaz->id)
                    ->where('oferta_id', $ofertaAcceptata->id)
                    ->where('user_id', $userId)
                    ->where('rol_in_fisa', $rolInFisa)
                    ->exists();

                if ($existing) {
                    continue;
                }

                $bonusFix = (int) $interval->bonus_fix;
                $bonusProcent = (int) $interval->bonus_procent;
                $bonusTotal = (int) round($bonusFix + ($valoareOferta * $bonusProcent / 100));

                $bonus = Bonus::create([
                    'fisa_caz_id' => $fisaCaz->id,
                    'oferta_id' => $ofertaAcceptata->id,
                    'user_id' => $userId,
                    'rol_in_fisa' => $rolInFisa,
                    'lucrare_id' => $lucrare->id,
                    'interval_id' => $interval->id,
                    'valoare_oferta' => $valoareOferta,
                    'bonus_fix' => $bonusFix,
                    'bonus_procent' => $bonusProcent,
                    'bonus_total' => $bonusTotal,
                    'status' => Bonus::STATUS_CALCULAT,
                    'luna_merit' => Carbon::parse($fisaCaz->protezare)->startOfMonth()->toDateString(),
                    'calculated_at' => now(),
                ]);

                BonusIstoric::create([
                    'bonus_id' => $bonus->id,
                    'actiune' => 'calculat_automat',
                    'status' => $bonus->status,
                    'bonus_total' => $bonus->bonus_total,
                    'data_plata' => $bonus->data_plata,
                    'user_id' => $initiatorUserId,
                    'detalii' => 'Bonus calculat automat pentru rolul ' . $rolInFisa . '.',
                ]);

                $bonusuriCreatePeFisa++;
                $rezultat['bonusuri_generate']++;
            }

            if ($bonusuriCreatePeFisa > 0 || $this->fisaAreToateBonusurileGenerate($fisaCaz->id, $ofertaAcceptata->id, array_keys($this->roluriBonusabile($fisaCaz)))) {
                $fisaCaz->bonusat = 1;
                $fisaCaz->save();
                $rezultat['fise_bonusate']++;
            }
        }

        return $rezultat;
    }

    public function rezolvaLucrarePentruFisa(FisaCaz $fisaCaz): ?Lucrare
    {
        if (!empty($fisaCaz->tip_lucrare_solicitata_id)) {
            $lucrare = Lucrare::find($fisaCaz->tip_lucrare_solicitata_id);
            if ($lucrare) {
                return $lucrare;
            }
        }

        $denumire = trim((string) $fisaCaz->tip_lucrare_solicitata);
        if ($denumire === '') {
            return null;
        }

        $lucrare = Lucrare::firstOrCreate(
            ['denumire' => $denumire],
            ['cod' => $this->genereazaCodLucrareUnic($denumire), 'activ' => 1]
        );

        if (Schema::hasColumn('fise_caz', 'tip_lucrare_solicitata_id')) {
            $fisaCaz->tip_lucrare_solicitata_id = $lucrare->id;
            $fisaCaz->save();
        }

        return $lucrare;
    }

    public function gasesteIntervalBonus(int $lucrareId, int $valoareOferta, Carbon $dataReferinta): ?LucrareBonusInterval
    {
        return LucrareBonusInterval::query()
            ->where('lucrare_id', $lucrareId)
            ->where('activ', 1)
            ->where(function ($query) use ($dataReferinta) {
                $query->whereNull('valid_from')
                    ->orWhereDate('valid_from', '<=', $dataReferinta->toDateString());
            })
            ->where(function ($query) use ($dataReferinta) {
                $query->whereNull('valid_to')
                    ->orWhereDate('valid_to', '>=', $dataReferinta->toDateString());
            })
            ->where('min_valoare', '<=', $valoareOferta)
            ->where(function ($query) use ($valoareOferta) {
                $query->whereNull('max_valoare')
                    ->orWhere('max_valoare', '>=', $valoareOferta);
            })
            ->orderByDesc('min_valoare')
            ->orderByDesc('id')
            ->first();
    }

    /**
     * @return array<string, int>
     */
    protected function roluriBonusabile(FisaCaz $fisaCaz): array
    {
        $roluri = [];

        if (!empty($fisaCaz->user_vanzari)) {
            $roluri['vanzari'] = (int) $fisaCaz->user_vanzari;
        }
        if (!empty($fisaCaz->user_tehnic)) {
            $roluri['tehnic'] = (int) $fisaCaz->user_tehnic;
        }

        return $roluri;
    }

    protected function fisaAreToateBonusurileGenerate(int $fisaCazId, int $ofertaId, array $roluri): bool
    {
        foreach ($roluri as $rol) {
            $exists = Bonus::query()
                ->where('fisa_caz_id', $fisaCazId)
                ->where('oferta_id', $ofertaId)
                ->where('rol_in_fisa', $rol)
                ->exists();

            if (!$exists) {
                return false;
            }
        }

        return true;
    }

    protected function genereazaCodLucrareUnic(string $denumire): string
    {
        $base = $this->normalizeCode($denumire);
        if ($base === '') {
            $base = 'LUCRARE';
        }

        $cod = $base;
        $index = 2;

        while (Lucrare::withTrashed()->where('cod', $cod)->exists()) {
            $cod = $base . ' ' . $index;
            $index++;
        }

        return $cod;
    }

    protected function normalizeCode(string $value): string
    {
        $value = str_replace(['_', '-'], ' ', $value);
        $value = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $value) ?? '';
        $value = preg_replace('/\s+/u', ' ', $value) ?? '';
        $value = trim($value);

        return Str::upper($value);
    }
}
