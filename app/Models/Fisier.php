<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fisier extends Model
{
    use HasFactory;

    protected $table = 'fisiere';
    protected $guarded = [];

    public function path()
    {
        return "/fisiere";
    }
}
