<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!DB::getSchemaBuilder()->hasTable('lucrari')) {
            return;
        }

        $lucrari = DB::table('lucrari')
            ->select(['id', 'denumire'])
            ->orderBy('id')
            ->get();

        if ($lucrari->isEmpty()) {
            return;
        }

        // Avoid unique collisions while reseeding codes.
        foreach ($lucrari as $lucrare) {
            DB::table('lucrari')
                ->where('id', $lucrare->id)
                ->update([
                    'cod' => 'TMP ' . $lucrare->id,
                    'updated_at' => now(),
                ]);
        }

        $used = [];
        foreach ($lucrari as $lucrare) {
            $base = $this->normalizeCode((string) ($lucrare->denumire ?? ''));
            if ($base === '') {
                $base = 'LUCRARE';
            }

            $cod = $base;
            $index = 2;
            while (in_array($cod, $used, true)) {
                $cod = $base . ' ' . $index;
                $index++;
            }

            DB::table('lucrari')
                ->where('id', $lucrare->id)
                ->update([
                    'cod' => $cod,
                    'updated_at' => now(),
                ]);

            $used[] = $cod;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Irreversible data normalization.
    }

    private function normalizeCode(string $value): string
    {
        $value = str_replace(['_', '-'], ' ', $value);
        $value = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $value) ?? '';
        $value = preg_replace('/\s+/u', ' ', $value) ?? '';
        $value = trim($value);

        return Str::upper($value);
    }
};

