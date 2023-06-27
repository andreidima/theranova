<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecoltareSangeRebut extends Model
{
    use HasFactory;

    protected $table = 'recoltari_sange_rebuturi';
    protected $guarded = [];

    public function path()
    {
        return "/recoltari-sange-rebuturi/{$this->id}";
    }
}
