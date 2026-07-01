<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('clienti_prospectare')) {
            Schema::create('clienti_prospectare', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nume', 255);
                $table->string('telefon', 100)->nullable();
                $table->string('email', 255)->nullable();
                $table->string('localitate', 200)->nullable();
                $table->string('judet', 200)->nullable();
                $table->string('sursa', 100)->nullable();
                $table->boolean('activ')->default(true);
                $table->timestamps();

                $table->index(['activ', 'nume']);
            });
        }

        if (Schema::hasTable('oferte_prospectare')) {
            Schema::table('oferte_prospectare', function (Blueprint $table) {
                if (!Schema::hasColumn('oferte_prospectare', 'client_prospectare_id')) {
                    $table->unsignedInteger('client_prospectare_id')->nullable()->after('fisa_caz_id');
                    $table->index('client_prospectare_id');
                }
                if (!Schema::hasColumn('oferte_prospectare', 'discount_tip')) {
                    $table->string('discount_tip', 20)->default('valoare')->after('discount_aditional');
                }
            });
        }

        if (Schema::hasTable('oferte_prospectare_adaos_intervale')) {
            Schema::table('oferte_prospectare_adaos_intervale', function (Blueprint $table) {
                if (!Schema::hasColumn('oferte_prospectare_adaos_intervale', 'categorie')) {
                    $table->string('categorie', 150)->nullable()->after('id');
                }
                if (!Schema::hasColumn('oferte_prospectare_adaos_intervale', 'valoare_adaos')) {
                    $table->unsignedInteger('valoare_adaos')->default(0)->after('valoare_max');
                }
            });
        }

        if (!Schema::hasTable('prospectare_configuratoare')) {
            Schema::create('prospectare_configuratoare', function (Blueprint $table) {
                $table->increments('id');
                $table->string('denumire', 255);
                $table->string('categorie', 150)->nullable();
                $table->text('text_pdf')->nullable();
                $table->boolean('activ')->default(true);
                $table->timestamps();

                $table->index(['activ', 'denumire']);
                $table->index('categorie');
            });
        }

        if (!Schema::hasTable('prospectare_configurator_grupuri')) {
            Schema::create('prospectare_configurator_grupuri', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('configurator_id');
                $table->string('denumire', 255);
                $table->unsignedInteger('ordine')->default(0);
                $table->timestamps();

                $table->index('configurator_id');
            });
        }

        if (!Schema::hasTable('prospectare_configurator_componente')) {
            Schema::create('prospectare_configurator_componente', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('grup_id');
                $table->string('denumire', 255);
                $table->string('producator', 255)->nullable();
                $table->unsignedInteger('pret')->default(0);
                $table->boolean('activ')->default(true);
                $table->unsignedInteger('ordine')->default(0);
                $table->timestamps();

                $table->index('grup_id');
                $table->index(['activ', 'denumire']);
            });
        }

        if (!Schema::hasTable('oferte_prospectare_variante')) {
            Schema::create('oferte_prospectare_variante', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('oferta_prospectare_id');
                $table->unsignedInteger('configurator_id')->nullable();
                $table->string('titlu', 255)->nullable();
                $table->string('configurator_denumire', 255)->nullable();
                $table->string('categorie', 150)->nullable();
                $table->unsignedInteger('subtotal_calculat')->default(0);
                $table->unsignedInteger('total_manual')->nullable();
                $table->unsignedInteger('valoare_adaos')->default(0);
                $table->string('discount_tip', 20)->default('valoare');
                $table->unsignedInteger('discount_valoare')->default(0);
                $table->unsignedInteger('valoare_dupa_decontare')->default(0);
                $table->unsignedInteger('valoare_totala')->default(0);
                $table->unsignedInteger('valoare_avans')->default(0);
                $table->unsignedInteger('ordine')->default(0);
                $table->timestamps();

                $table->index('oferta_prospectare_id');
                $table->index('configurator_id');
            });
        }

        if (!Schema::hasTable('oferte_prospectare_variante_componente')) {
            Schema::create('oferte_prospectare_variante_componente', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('varianta_id');
                $table->unsignedInteger('componenta_id')->nullable();
                $table->string('denumire', 255);
                $table->string('producator', 255)->nullable();
                $table->unsignedInteger('pret')->default(0);
                $table->timestamps();

                $table->index('varianta_id');
                $table->index('componenta_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('oferte_prospectare_variante_componente');
        Schema::dropIfExists('oferte_prospectare_variante');
        Schema::dropIfExists('prospectare_configurator_componente');
        Schema::dropIfExists('prospectare_configurator_grupuri');
        Schema::dropIfExists('prospectare_configuratoare');

        if (Schema::hasTable('oferte_prospectare_adaos_intervale')) {
            Schema::table('oferte_prospectare_adaos_intervale', function (Blueprint $table) {
                if (Schema::hasColumn('oferte_prospectare_adaos_intervale', 'valoare_adaos')) {
                    $table->dropColumn('valoare_adaos');
                }
                if (Schema::hasColumn('oferte_prospectare_adaos_intervale', 'categorie')) {
                    $table->dropColumn('categorie');
                }
            });
        }

        if (Schema::hasTable('oferte_prospectare')) {
            Schema::table('oferte_prospectare', function (Blueprint $table) {
                if (Schema::hasColumn('oferte_prospectare', 'discount_tip')) {
                    $table->dropColumn('discount_tip');
                }
                if (Schema::hasColumn('oferte_prospectare', 'client_prospectare_id')) {
                    $table->dropIndex(['client_prospectare_id']);
                    $table->dropColumn('client_prospectare_id');
                }
            });
        }

        Schema::dropIfExists('clienti_prospectare');
    }
};
