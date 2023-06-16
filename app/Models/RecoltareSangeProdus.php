<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecoltareSangeProdus extends Model
{
    use HasFactory;

    protected $table = 'recoltari_sange_produse';
    protected $guarded = [];

    public function path()
    {
        return "/recoltari-sange-produse/{$this->id}";
    }
}
