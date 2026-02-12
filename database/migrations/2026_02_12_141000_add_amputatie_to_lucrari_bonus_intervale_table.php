<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('lucrari_bonus_intervale')) {
            return;
        }

        Schema::table('lucrari_bonus_intervale', function (Blueprint $table) {
            if (!Schema::hasColumn('lucrari_bonus_intervale', 'amputatie')) {
                $table->string('amputatie', 150)->nullable()->after('lucrare_id');
                $table->index(['lucrare_id', 'amputatie'], 'lucrari_bonus_intervale_lucrare_amputatie_idx');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('lucrari_bonus_intervale')) {
            return;
        }

        Schema::table('lucrari_bonus_intervale', function (Blueprint $table) {
            if (Schema::hasColumn('lucrari_bonus_intervale', 'amputatie')) {
                $table->dropIndex('lucrari_bonus_intervale_lucrare_amputatie_idx');
                $table->dropColumn('amputatie');
            }
        });
    }
};

