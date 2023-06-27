<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecoltareSangeBeneficiar extends Model
{
    use HasFactory;

    protected $table = 'recoltari_sange_beneficiari';
    protected $guarded = [];

    public function path()
    {
        return "/recoltari-sange-beneficiari/{$this->id}";
    }
}
