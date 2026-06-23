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
            ['user_id' => 1, 'nume' => 'prospectare.view_all'],
            ['user_id' => 1, 'nume' => 'prospectare.edit'],
            ['user_id' => 2, 'nume' => 'prospectare.view_all'],
            ['user_id' => 2, 'nume' => 'prospectare.edit'],
        ];

        foreach ($roles as $role) {
            DB::table('users_roles')->updateOrInsert(
                [
                    'user_id' => $role['user_id'],
                    'nume' => $role['nume'],
                ],
                [
                    'created_at' => $now,
                    'updated_at' => $now,
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
            ->whereIn('nume', ['prospectare.view_all', 'prospectare.edit'])
            ->whereIn('user_id', [1, 2])
            ->delete();
    }
};
