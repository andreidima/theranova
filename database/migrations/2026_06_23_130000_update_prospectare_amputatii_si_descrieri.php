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
        if (Schema::hasTable('produse_prospectare') && !Schema::hasColumn('produse_prospectare', 'descriere')) {
            Schema::table('produse_prospectare', function (Blueprint $table) {
                $table->text('descriere')->nullable()->after('denumire');
            });
        }

        if (Schema::hasTable('oferte_prospectare_linii') && !Schema::hasColumn('oferte_prospectare_linii', 'descriere')) {
            Schema::table('oferte_prospectare_linii', function (Blueprint $table) {
                $table->text('descriere')->nullable()->after('denumire_produs');
            });
        }

        if (!Schema::hasTable('oferte_prospectare_amputatii')) {
            Schema::create('oferte_prospectare_amputatii', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('oferta_prospectare_id');
                $table->string('parte_amputata', 100)->nullable();
                $table->string('amputatie', 100)->nullable();
                $table->timestamps();

                $table->index('oferta_prospectare_id');
            });
        }

        if (Schema::hasTable('oferte_prospectare') && Schema::hasTable('oferte_prospectare_amputatii')) {
            DB::table('oferte_prospectare')
                ->where(function ($query) {
                    $query->whereNotNull('parte_amputata')
                        ->orWhereNotNull('amputatie');
                })
                ->orderBy('id')
                ->chunkById(100, function ($oferte) {
                    foreach ($oferte as $oferta) {
                        $exists = DB::table('oferte_prospectare_amputatii')
                            ->where('oferta_prospectare_id', $oferta->id)
                            ->exists();

                        if ($exists) {
                            continue;
                        }

                        DB::table('oferte_prospectare_amputatii')->insert([
                            'oferta_prospectare_id' => $oferta->id,
                            'parte_amputata' => $oferta->parte_amputata,
                            'amputatie' => $oferta->amputatie,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oferte_prospectare_amputatii');

        if (Schema::hasTable('oferte_prospectare_linii') && Schema::hasColumn('oferte_prospectare_linii', 'descriere')) {
            Schema::table('oferte_prospectare_linii', function (Blueprint $table) {
                $table->dropColumn('descriere');
            });
        }

        if (Schema::hasTable('produse_prospectare') && Schema::hasColumn('produse_prospectare', 'descriere')) {
            Schema::table('produse_prospectare', function (Blueprint $table) {
                $table->dropColumn('descriere');
            });
        }
    }
};
