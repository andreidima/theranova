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
            ['user_id' => 1, 'nume' => 'tech.impersonare'],
            ['user_id' => 1, 'nume' => 'tech.migrations'],
            ['user_id' => 1, 'nume' => 'tech.cronjobs'],
            ['user_id' => 2, 'nume' => 'tech.impersonare'],
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
            ->whereIn('nume', ['tech.impersonare', 'tech.migrations', 'tech.cronjobs'])
            ->whereIn('user_id', [1, 2])
            ->delete();
    }
};
