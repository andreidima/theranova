<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('fise_caz', function (Blueprint $table) {
            if (!Schema::hasColumn('fise_caz', 'facturat')) {
                $table->boolean('facturat')->default(false)->after('protezare');
            }

            if (!Schema::hasColumn('fise_caz', 'bonusat')) {
                $table->boolean('bonusat')->default(false)->after('facturat');
            }
        });

        $this->seteazaFacturatPentruFiseEligibile();
        $this->seteazaBonusatIstoricPentruFiseEligibile();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fise_caz', function (Blueprint $table) {
            if (Schema::hasColumn('fise_caz', 'bonusat')) {
                $table->dropColumn('bonusat');
            }

            if (Schema::hasColumn('fise_caz', 'facturat')) {
                $table->dropColumn('facturat');
            }
        });
    }

    private function seteazaFacturatPentruFiseEligibile(): void
    {
        DB::table('fise_caz')
            ->whereNotNull('protezare')
            ->whereExists(function ($query) {
                $query->selectRaw('1')
                    ->from('oferte')
                    ->whereColumn('oferte.fisa_caz_id', 'fise_caz.id')
                    ->where('oferte.acceptata', 1);
            })
            ->update(['facturat' => 1]);
    }

    private function seteazaBonusatIstoricPentruFiseEligibile(): void
    {
        DB::table('fise_caz')
            ->whereDate('protezare', '<', '2026-02-01')
            ->whereExists(function ($query) {
                $query->selectRaw('1')
                    ->from('oferte')
                    ->whereColumn('oferte.fisa_caz_id', 'fise_caz.id')
                    ->where('oferte.acceptata', 1);
            })
            ->update(['bonusat' => 1]);
    }
};
