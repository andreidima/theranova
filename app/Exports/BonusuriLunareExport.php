<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BonusuriLunareExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(
        protected Collection $rows
    ) {
    }

    public function headings(): array
    {
        return [
            'Pacient nume',
            'Pacient prenume',
            'Fisa caz',
            'Utilizator',
            'Rol',
            'Lucrare',
            'Amputatie',
            'Oferta lei',
            'Bonus fix',
            'Bonus procent',
            'Bonus total',
        ];
    }

    public function collection(): Collection
    {
        return $this->rows->values()->map(function (array $row) {
            return [
                $row['pacient_nume'],
                $row['pacient_prenume'],
                $row['fisa_caz_id'],
                $row['user_name'],
                $row['rol'],
                $row['lucrare_denumire'],
                $row['amputatie'],
                $row['valoare_oferta'],
                $row['bonus_fix'],
                $row['bonus_procent'],
                $row['bonus_total'],
            ];
        });
    }
}
