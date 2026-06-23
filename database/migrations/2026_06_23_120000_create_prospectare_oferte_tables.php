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
        if (!Schema::hasTable('produse_prospectare')) {
            Schema::create('produse_prospectare', function (Blueprint $table) {
                $table->increments('id');
                $table->string('denumire', 255);
                $table->string('cod', 100)->nullable();
                $table->unsignedInteger('pret_end_user')->default(0);
                $table->boolean('activ')->default(true);
                $table->text('observatii')->nullable();
                $table->timestamps();

                $table->index(['activ', 'denumire']);
            });
        }

        if (!Schema::hasTable('oferte_prospectare')) {
            Schema::create('oferte_prospectare', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_emitent_id')->nullable();
                $table->unsignedInteger('user_aprobator_id')->nullable();
                $table->unsignedInteger('pacient_id')->nullable();
                $table->unsignedInteger('fisa_caz_id')->nullable();

                $table->string('nume_client', 255);
                $table->string('telefon', 100);
                $table->string('email', 255)->nullable();
                $table->string('localitate', 200)->nullable();
                $table->string('judet', 200);
                $table->string('sursa', 200)->nullable();

                $table->date('data_ofertei')->nullable();
                $table->date('valabila_pana_la')->nullable();
                $table->string('tip_lucrare_solicitata', 200)->nullable();

                $table->unsignedTinyInteger('greutate')->nullable();
                $table->string('parte_amputata', 100)->nullable();
                $table->string('amputatie', 100)->nullable();
                $table->string('nivel_de_activitate', 100)->nullable();
                $table->string('cauza_amputatiei', 100)->nullable();
                $table->unsignedTinyInteger('a_mai_purtat_proteza')->nullable();
                $table->text('descriere_amputatie')->nullable();

                $table->boolean('decontare_cas')->default(false);
                $table->unsignedInteger('buget_disponibil')->nullable();
                $table->unsignedInteger('discount_aditional')->default(0);
                $table->unsignedInteger('subtotal')->default(0);
                $table->unsignedInteger('valoare_dupa_decontare')->default(0);
                $table->unsignedInteger('valoare_totala')->default(0);
                $table->unsignedInteger('valoare_avans')->default(0);

                $table->string('status_aprobare', 40)->default('draft');
                $table->string('status_client', 40)->default('nestrimisa');
                $table->text('observatii_interne')->nullable();
                $table->text('observatii_admin')->nullable();
                $table->timestamp('trimisa_la')->nullable();
                $table->timestamp('aprobata_la')->nullable();
                $table->timestamp('raspuns_client_la')->nullable();
                $table->timestamp('convertita_la')->nullable();
                $table->timestamps();

                $table->index(['status_aprobare', 'status_client']);
                $table->index('user_emitent_id');
                $table->index('user_aprobator_id');
                $table->index('pacient_id');
                $table->index('fisa_caz_id');
            });
        }

        if (!Schema::hasTable('oferte_prospectare_linii')) {
            Schema::create('oferte_prospectare_linii', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('oferta_prospectare_id');
                $table->unsignedInteger('produs_prospectare_id')->nullable();
                $table->string('denumire_produs', 255);
                $table->unsignedInteger('cantitate')->default(1);
                $table->unsignedInteger('pret_unitar')->default(0);
                $table->unsignedInteger('valoare_linie')->default(0);
                $table->timestamps();

                $table->index('oferta_prospectare_id');
                $table->index('produs_prospectare_id');
            });
        }

        if (!Schema::hasTable('oferte_prospectare_trimiteri')) {
            Schema::create('oferte_prospectare_trimiteri', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('oferta_prospectare_id');
                $table->unsignedInteger('user_id')->nullable();
                $table->string('canal', 40);
                $table->string('destinatar', 255)->nullable();
                $table->string('status', 40)->default('trimis');
                $table->text('mesaj')->nullable();
                $table->timestamps();

                $table->index('oferta_prospectare_id');
                $table->index('user_id');
                $table->index('canal');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oferte_prospectare_trimiteri');
        Schema::dropIfExists('oferte_prospectare_linii');
        Schema::dropIfExists('oferte_prospectare');
        Schema::dropIfExists('produse_prospectare');
    }
};
