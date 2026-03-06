<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('lucrari_bonus_intervale')) {
            return;
        }

        if (!Schema::hasColumn('lucrari_bonus_intervale', 'rol_in_fisa')) {
            Schema::table('lucrari_bonus_intervale', function (Blueprint $table) {
                $table->string('rol_in_fisa', 30)->nullable()->after('lucrare_id');
            });
        }

        $hasAmputatieColumn = Schema::hasColumn('lucrari_bonus_intervale', 'amputatie');
        $selectColumns = [
            'id',
            'lucrare_id',
            'min_valoare',
            'max_valoare',
            'bonus_fix',
            'bonus_procent',
            'valid_from',
            'valid_to',
            'activ',
            'created_at',
            'updated_at',
        ];

        if ($hasAmputatieColumn) {
            $selectColumns[] = 'amputatie';
        }

        DB::table('lucrari_bonus_intervale')
            ->whereNull('rol_in_fisa')
            ->select($selectColumns)
            ->orderBy('id')
            ->chunkById(200, function ($rows) use ($hasAmputatieColumn) {
                $insertRows = [];
                $sourceIds = [];

                foreach ($rows as $row) {
                    $sourceIds[] = (int) $row->id;

                    foreach (['vanzari', 'tehnic'] as $rolInFisa) {
                        $newRow = [
                            'lucrare_id' => (int) $row->lucrare_id,
                            'rol_in_fisa' => $rolInFisa,
                            'min_valoare' => $row->min_valoare,
                            'max_valoare' => $row->max_valoare,
                            'bonus_fix' => $row->bonus_fix,
                            'bonus_procent' => $row->bonus_procent,
                            'valid_from' => $row->valid_from,
                            'valid_to' => $row->valid_to,
                            'activ' => (int) $row->activ,
                            'created_at' => $row->created_at,
                            'updated_at' => $row->updated_at,
                        ];

                        if ($hasAmputatieColumn) {
                            $newRow['amputatie'] = $row->amputatie;
                        }

                        $insertRows[] = $newRow;
                    }
                }

                if ($insertRows !== []) {
                    DB::table('lucrari_bonus_intervale')->insert($insertRows);
                }

                if ($sourceIds !== []) {
                    DB::table('lucrari_bonus_intervale')
                        ->whereIn('id', $sourceIds)
                        ->delete();
                }
            }, 'id');

        Schema::table('lucrari_bonus_intervale', function (Blueprint $table) {
            $table->index(['lucrare_id', 'rol_in_fisa', 'activ'], 'lucrari_bonus_intervale_lucrare_rol_activ_idx');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('lucrari_bonus_intervale')) {
            return;
        }

        if (Schema::hasColumn('lucrari_bonus_intervale', 'rol_in_fisa')) {
            Schema::table('lucrari_bonus_intervale', function (Blueprint $table) {
                $table->dropIndex('lucrari_bonus_intervale_lucrare_rol_activ_idx');
                $table->dropColumn('rol_in_fisa');
            });
        }
    }
};
