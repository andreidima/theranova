<?php

namespace App\Services;

use App\Models\FisaCaz;
use App\Models\Lucrare;
use App\Models\LucrareBonusInterval;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class BonusCalculatorService
{
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

    public function gasesteIntervalBonus(int $lucrareId, int $valoareOferta, Carbon $dataReferinta, ?string $amputatie = null): ?LucrareBonusInterval
    {
        $amputatieNormalizata = $this->normalizeAmputatie($amputatie);

        $query = LucrareBonusInterval::query()
            ->where('lucrare_id', $lucrareId)
            ->where('activ', 1)
            ->where(function ($query) use ($amputatieNormalizata) {
                if ($amputatieNormalizata === null) {
                    $query->whereNull('amputatie');
                    return;
                }

                $query->whereNull('amputatie')
                    ->orWhere('amputatie', $amputatieNormalizata);
            })
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
            });

        if ($amputatieNormalizata !== null) {
            $query->orderByRaw('CASE WHEN amputatie = ? THEN 1 ELSE 0 END DESC', [$amputatieNormalizata]);
        }

        return $query
            ->orderByDesc('min_valoare')
            ->orderByDesc('id')
            ->first();
    }

    public function calculeazaBonusTotal(int $valoareOferta, int $bonusFix, int $bonusProcent): int
    {
        return (int) round($bonusFix + ($valoareOferta * $bonusProcent / 100));
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

    public function normalizeAmputatie(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = preg_replace('/\s+/u', ' ', trim($value)) ?? '';

        return $value === '' ? null : $value;
    }
}
