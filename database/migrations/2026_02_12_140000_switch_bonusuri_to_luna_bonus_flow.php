<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('fise_caz')) {
            Schema::table('fise_caz', function (Blueprint $table) {
                if (!Schema::hasColumn('fise_caz', 'luna_bonus')) {
                    $table->date('luna_bonus')->nullable()->after('protezare');
                    $table->index('luna_bonus');
                }
            });

            DB::table('fise_caz')
                ->whereNull('luna_bonus')
                ->whereNotNull('protezare')
                ->orderBy('id')
                ->chunkById(500, function ($rows) {
                    foreach ($rows as $row) {
                        DB::table('fise_caz')
                            ->where('id', $row->id)
                            ->update([
                                'luna_bonus' => \Carbon\Carbon::parse($row->protezare)->startOfMonth()->toDateString(),
                            ]);
                    }
                });

            Schema::table('fise_caz', function (Blueprint $table) {
                if (Schema::hasColumn('fise_caz', 'bonusat')) {
                    $table->dropColumn('bonusat');
                }
                if (Schema::hasColumn('fise_caz', 'facturat')) {
                    $table->dropColumn('facturat');
                }
            });
        }

        if (!Schema::hasTable('fise_caz_luna_bonus_istoric')) {
            Schema::create('fise_caz_luna_bonus_istoric', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('fisa_caz_id');
                $table->date('luna_bonus_veche')->nullable();
                $table->date('luna_bonus_noua')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->timestamps();

                $table->index('fisa_caz_id');
                $table->index('user_id');
                $table->index('created_at');
            });
        }

        Schema::dropIfExists('bonusuri_istoric');
        Schema::dropIfExists('bonusuri');
    }

    public function down(): void
    {
        if (!Schema::hasTable('bonusuri')) {
            Schema::create('bonusuri', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('fisa_caz_id');
                $table->unsignedInteger('oferta_id');
                $table->unsignedInteger('user_id');
                $table->string('rol_in_fisa', 30);
                $table->unsignedInteger('lucrare_id');
                $table->unsignedInteger('interval_id')->nullable();
                $table->unsignedInteger('valoare_oferta')->default(0);
                $table->unsignedInteger('bonus_fix')->default(0);
                $table->unsignedInteger('bonus_procent')->default(0);
                $table->unsignedInteger('bonus_total')->default(0);
                $table->string('status', 30)->default('calculat');
                $table->date('luna_merit');
                $table->dateTime('calculated_at')->nullable();
                $table->dateTime('approved_at')->nullable();
                $table->date('data_plata')->nullable();
                $table->unsignedInteger('platit_de_user_id')->nullable();
                $table->text('observatii')->nullable();
                $table->timestamps();

                $table->unique(['fisa_caz_id', 'oferta_id', 'user_id', 'rol_in_fisa'], 'bonusuri_unique_per_rol');
                $table->index(['user_id', 'status']);
                $table->index(['luna_merit', 'status']);
                $table->index('data_plata');
            });
        }

        if (!Schema::hasTable('bonusuri_istoric')) {
            Schema::create('bonusuri_istoric', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('bonus_id');
                $table->string('actiune', 100);
                $table->string('status', 30)->nullable();
                $table->unsignedInteger('bonus_total')->nullable();
                $table->date('data_plata')->nullable();
                $table->unsignedInteger('user_id')->nullable();
                $table->string('detalii', 500)->nullable();
                $table->timestamps();

                $table->index('bonus_id');
                $table->index('user_id');
            });
        }

        Schema::dropIfExists('fise_caz_luna_bonus_istoric');

        if (Schema::hasTable('fise_caz')) {
            Schema::table('fise_caz', function (Blueprint $table) {
                if (!Schema::hasColumn('fise_caz', 'facturat')) {
                    $table->boolean('facturat')->default(false)->after('protezare');
                }
                if (!Schema::hasColumn('fise_caz', 'bonusat')) {
                    $table->boolean('bonusat')->default(false)->after('facturat');
                }
            });

            Schema::table('fise_caz', function (Blueprint $table) {
                if (Schema::hasColumn('fise_caz', 'luna_bonus')) {
                    $table->dropColumn('luna_bonus');
                }
            });
        }
    }
};
