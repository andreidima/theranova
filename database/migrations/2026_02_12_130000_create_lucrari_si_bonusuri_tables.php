<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('lucrari')) {
            Schema::create('lucrari', function (Blueprint $table) {
                $table->increments('id');
                $table->string('denumire', 200);
                $table->string('cod', 120)->unique();
                $table->boolean('activ')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('lucrari_bonus_intervale')) {
            Schema::create('lucrari_bonus_intervale', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('lucrare_id');
                $table->decimal('min_valoare', 12, 2)->default(0);
                $table->decimal('max_valoare', 12, 2)->nullable();
                $table->decimal('bonus_fix', 12, 2)->default(0);
                $table->decimal('bonus_procent', 8, 4)->default(0);
                $table->date('valid_from')->nullable();
                $table->date('valid_to')->nullable();
                $table->boolean('activ')->default(true);
                $table->timestamps();

                $table->index('lucrare_id');
                $table->index(['lucrare_id', 'activ']);
            });
        }

        if (!Schema::hasTable('bonusuri')) {
            Schema::create('bonusuri', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('fisa_caz_id');
                $table->unsignedInteger('oferta_id');
                $table->unsignedInteger('user_id');
                $table->string('rol_in_fisa', 30);
                $table->unsignedInteger('lucrare_id');
                $table->unsignedInteger('interval_id')->nullable();
                $table->decimal('valoare_oferta', 12, 2)->default(0);
                $table->decimal('bonus_fix', 12, 2)->default(0);
                $table->decimal('bonus_procent', 8, 4)->default(0);
                $table->decimal('bonus_total', 12, 2)->default(0);
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
                $table->decimal('bonus_total', 12, 2)->nullable();
                $table->date('data_plata')->nullable();
                $table->unsignedInteger('user_id')->nullable();
                $table->string('detalii', 500)->nullable();
                $table->timestamps();

                $table->index('bonus_id');
                $table->index('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonusuri_istoric');
        Schema::dropIfExists('bonusuri');
        Schema::dropIfExists('lucrari_bonus_intervale');
        Schema::dropIfExists('lucrari');
    }
};

