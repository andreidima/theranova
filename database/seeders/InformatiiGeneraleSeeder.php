<?php

namespace Database\Seeders;

use App\Models\InformatiiGenerale;
use Illuminate\Database\Seeder;

class InformatiiGeneraleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coduriApartamente = [
            'cod_apartament_1' => 'COD-AP1',
            'cod_apartament_2' => 'COD-AP2',
            'cod_apartament_3' => 'COD-AP3',
        ];

        foreach ($coduriApartamente as $variabila => $valoare) {
            InformatiiGenerale::firstOrCreate(
                ['variabila' => $variabila],
                ['valoare' => $valoare]
            );
        }
    }
}
