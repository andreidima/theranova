<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getSchemaBuilder()->hasTable('lucrari_bonus_intervale')) {
            DB::statement('UPDATE lucrari_bonus_intervale SET min_valoare = ROUND(min_valoare), max_valoare = CASE WHEN max_valoare IS NULL THEN NULL ELSE ROUND(max_valoare) END, bonus_fix = ROUND(bonus_fix), bonus_procent = ROUND(bonus_procent)');
            DB::statement('ALTER TABLE lucrari_bonus_intervale MODIFY min_valoare INT UNSIGNED NOT NULL DEFAULT 0');
            DB::statement('ALTER TABLE lucrari_bonus_intervale MODIFY max_valoare INT UNSIGNED NULL');
            DB::statement('ALTER TABLE lucrari_bonus_intervale MODIFY bonus_fix INT UNSIGNED NOT NULL DEFAULT 0');
            DB::statement('ALTER TABLE lucrari_bonus_intervale MODIFY bonus_procent INT UNSIGNED NOT NULL DEFAULT 0');
        }

        if (DB::getSchemaBuilder()->hasTable('bonusuri')) {
            DB::statement('UPDATE bonusuri SET valoare_oferta = ROUND(valoare_oferta), bonus_fix = ROUND(bonus_fix), bonus_procent = ROUND(bonus_procent), bonus_total = ROUND(bonus_total)');
            DB::statement('ALTER TABLE bonusuri MODIFY valoare_oferta INT UNSIGNED NOT NULL DEFAULT 0');
            DB::statement('ALTER TABLE bonusuri MODIFY bonus_fix INT UNSIGNED NOT NULL DEFAULT 0');
            DB::statement('ALTER TABLE bonusuri MODIFY bonus_procent INT UNSIGNED NOT NULL DEFAULT 0');
            DB::statement('ALTER TABLE bonusuri MODIFY bonus_total INT UNSIGNED NOT NULL DEFAULT 0');
        }

        if (DB::getSchemaBuilder()->hasTable('bonusuri_istoric')) {
            DB::statement('UPDATE bonusuri_istoric SET bonus_total = CASE WHEN bonus_total IS NULL THEN NULL ELSE ROUND(bonus_total) END');
            DB::statement('ALTER TABLE bonusuri_istoric MODIFY bonus_total INT UNSIGNED NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getSchemaBuilder()->hasTable('lucrari_bonus_intervale')) {
            DB::statement('ALTER TABLE lucrari_bonus_intervale MODIFY min_valoare DECIMAL(12,2) NOT NULL DEFAULT 0');
            DB::statement('ALTER TABLE lucrari_bonus_intervale MODIFY max_valoare DECIMAL(12,2) NULL');
            DB::statement('ALTER TABLE lucrari_bonus_intervale MODIFY bonus_fix DECIMAL(12,2) NOT NULL DEFAULT 0');
            DB::statement('ALTER TABLE lucrari_bonus_intervale MODIFY bonus_procent DECIMAL(8,4) NOT NULL DEFAULT 0');
        }

        if (DB::getSchemaBuilder()->hasTable('bonusuri')) {
            DB::statement('ALTER TABLE bonusuri MODIFY valoare_oferta DECIMAL(12,2) NOT NULL DEFAULT 0');
            DB::statement('ALTER TABLE bonusuri MODIFY bonus_fix DECIMAL(12,2) NOT NULL DEFAULT 0');
            DB::statement('ALTER TABLE bonusuri MODIFY bonus_procent DECIMAL(8,4) NOT NULL DEFAULT 0');
            DB::statement('ALTER TABLE bonusuri MODIFY bonus_total DECIMAL(12,2) NOT NULL DEFAULT 0');
        }

        if (DB::getSchemaBuilder()->hasTable('bonusuri_istoric')) {
            DB::statement('ALTER TABLE bonusuri_istoric MODIFY bonus_total DECIMAL(12,2) NULL');
        }
    }
};

