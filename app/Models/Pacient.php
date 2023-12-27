<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pacient extends Model
{
    use HasFactory;

    protected $table = 'pacienti';
    protected $guarded = [];

    public function path()
    {
        return "/pacienti/{$this->id}";
    }
}
