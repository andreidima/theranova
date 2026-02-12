<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('fise_caz', function (Blueprint $table) {
            if (!Schema::hasColumn('fise_caz', 'tip_lucrare_solicitata_id')) {
                $table->unsignedInteger('tip_lucrare_solicitata_id')->nullable()->after('tip_lucrare_solicitata');
                $table->index('tip_lucrare_solicitata_id');
            }
        });

        if (!Schema::hasTable('lucrari')) {
            return;
        }

        $tipuri = DB::table('fise_caz')
            ->select('tip_lucrare_solicitata')
            ->whereNotNull('tip_lucrare_solicitata')
            ->where('tip_lucrare_solicitata', '<>', '')
            ->groupBy('tip_lucrare_solicitata')
            ->pluck('tip_lucrare_solicitata');

        foreach ($tipuri as $denumire) {
            $existent = DB::table('lucrari')->where('denumire', $denumire)->first();
            if (!$existent) {
                DB::table('lucrari')->insert([
                    'denumire' => $denumire,
                    'cod' => $this->genereazaCodLucrareUnic((string) $denumire),
                    'activ' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $lucrari = DB::table('lucrari')->select(['id', 'denumire'])->get()->keyBy('denumire');
        foreach ($lucrari as $denumire => $lucrare) {
            DB::table('fise_caz')
                ->where('tip_lucrare_solicitata', $denumire)
                ->whereNull('tip_lucrare_solicitata_id')
                ->update(['tip_lucrare_solicitata_id' => $lucrare->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fise_caz', function (Blueprint $table) {
            if (Schema::hasColumn('fise_caz', 'tip_lucrare_solicitata_id')) {
                $table->dropIndex(['tip_lucrare_solicitata_id']);
                $table->dropColumn('tip_lucrare_solicitata_id');
            }
        });
    }

    protected function genereazaCodLucrareUnic(string $denumire): string
    {
        $base = Str::slug($denumire, '_');
        if ($base === '') {
            $base = 'lucrare';
        }

        $cod = $base;
        $index = 1;
        while (DB::table('lucrari')->where('cod', $cod)->exists()) {
            $index++;
            $cod = $base . '_' . $index;
        }

        return $cod;
    }
};

