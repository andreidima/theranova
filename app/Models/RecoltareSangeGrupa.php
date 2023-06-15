<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecoltareSangeGrupa extends Model
{
    use HasFactory;

    protected $table = 'recoltari_sange_grupe';
    protected $guarded = [];

    public function path()
    {
        return "/recoltari-sange-grupe/{$this->id}";
    }
}
