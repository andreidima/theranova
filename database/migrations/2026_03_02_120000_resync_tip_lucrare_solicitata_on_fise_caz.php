<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('fise_caz') || !Schema::hasTable('lucrari')) {
            return;
        }

        DB::table('fise_caz')
            ->select('id', 'tip_lucrare_solicitata', 'tip_lucrare_solicitata_id')
            ->orderBy('id')
            ->chunkById(500, function ($rows) {
                foreach ($rows as $row) {
                    $denumireDinText = $this->normalizeDenumire($row->tip_lucrare_solicitata);
                    $lucrare = null;

                    // The text field reflects the user's last visible selection, so it wins over stale IDs.
                    if ($denumireDinText !== '') {
                        $lucrare = $this->findOrCreateLucrare($denumireDinText);
                    } elseif (!empty($row->tip_lucrare_solicitata_id)) {
                        $lucrare = DB::table('lucrari')
                            ->where('id', $row->tip_lucrare_solicitata_id)
                            ->whereNull('deleted_at')
                            ->first();
                    }

                    if (!$lucrare) {
                        continue;
                    }

                    $updates = [];

                    if ((int) $row->tip_lucrare_solicitata_id !== (int) $lucrare->id) {
                        $updates['tip_lucrare_solicitata_id'] = $lucrare->id;
                    }

                    if ((string) ($row->tip_lucrare_solicitata ?? '') !== (string) $lucrare->denumire) {
                        $updates['tip_lucrare_solicitata'] = $lucrare->denumire;
                    }

                    if ($updates !== []) {
                        DB::table('fise_caz')
                            ->where('id', $row->id)
                            ->update($updates);
                    }
                }
            });
    }

    public function down(): void
    {
        // Irreversible data sync.
    }

    protected function findOrCreateLucrare(string $denumire): object
    {
        $lucrare = DB::table('lucrari')
            ->where('denumire', $denumire)
            ->whereNull('deleted_at')
            ->orderBy('id')
            ->first();

        if ($lucrare) {
            return $lucrare;
        }

        $id = DB::table('lucrari')->insertGetId([
            'denumire' => $denumire,
            'cod' => $this->genereazaCodLucrareUnic($denumire),
            'activ' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('lucrari')->where('id', $id)->first();
    }

    protected function genereazaCodLucrareUnic(string $denumire): string
    {
        $base = $this->normalizeCode($denumire);
        if ($base === '') {
            $base = 'LUCRARE';
        }

        $cod = $base;
        $index = 2;

        while (DB::table('lucrari')->where('cod', $cod)->exists()) {
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

    protected function normalizeDenumire(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        return preg_replace('/\s+/u', ' ', trim($value)) ?? '';
    }
};
