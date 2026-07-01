<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientProspectare extends Model
{
    use HasFactory;

    protected $table = 'clienti_prospectare';
    protected $guarded = [];
    protected $casts = [
        'activ' => 'boolean',
    ];
}
