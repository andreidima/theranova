<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartinator extends Model
{
    use HasFactory;

    protected $table = 'apartinatori';
    protected $guarded = [];

    public function path()
    {
        return "/apartinatori/{$this->id}";
    }
}
