<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('oferte_prospectare')) {
            Schema::table('oferte_prospectare', function (Blueprint $table) {
                if (!Schema::hasColumn('oferte_prospectare', 'total_oferta')) {
                    $table->unsignedInteger('total_oferta')->default(0)->after('discount_aditional');
                }
                if (!Schema::hasColumn('oferte_prospectare', 'valoare_adaos')) {
                    $table->unsignedInteger('valoare_adaos')->default(0)->after('total_oferta');
                }
                if (!Schema::hasColumn('oferte_prospectare', 'procent_adaos')) {
                    $table->decimal('procent_adaos', 5, 2)->default(0)->after('valoare_adaos');
                }
            });

            DB::table('oferte_prospectare')
                ->where('total_oferta', 0)
                ->update([
                    'total_oferta' => DB::raw('subtotal'),
                    'valoare_adaos' => 0,
                    'procent_adaos' => 0,
                ]);
        }

        if (!Schema::hasTable('oferte_prospectare_adaos_intervale')) {
            Schema::create('oferte_prospectare_adaos_intervale', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('valoare_min');
                $table->unsignedInteger('valoare_max')->nullable();
                $table->decimal('procent', 5, 2)->default(0);
                $table->boolean('activ')->default(true);
                $table->timestamps();

                $table->index(['activ', 'valoare_min', 'valoare_max'], 'opp_adaos_intervale_lookup_index');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('oferte_prospectare_adaos_intervale');

        if (Schema::hasTable('oferte_prospectare')) {
            Schema::table('oferte_prospectare', function (Blueprint $table) {
                if (Schema::hasColumn('oferte_prospectare', 'procent_adaos')) {
                    $table->dropColumn('procent_adaos');
                }
                if (Schema::hasColumn('oferte_prospectare', 'valoare_adaos')) {
                    $table->dropColumn('valoare_adaos');
                }
                if (Schema::hasColumn('oferte_prospectare', 'total_oferta')) {
                    $table->dropColumn('total_oferta');
                }
            });
        }
    }
};
