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
        $now = now();

        $roles = [
            ['user_id' => 1, 'nume' => 'bonusat_edit'],
            ['user_id' => 2, 'nume' => 'bonusat_edit'],
        ];

        foreach ($roles as $role) {
            DB::table('users_roles')->updateOrInsert(
                [
                    'user_id' => $role['user_id'],
                    'nume' => $role['nume'],
                ],
                [
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users_roles')
            ->where('nume', 'bonusat_edit')
            ->whereIn('user_id', [1, 2])
            ->delete();
    }
};

