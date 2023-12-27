<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FisaCaz extends Model
{
    use HasFactory;

    protected $table = 'fise_caz';
    protected $guarded = [];

    public function path()
    {
        return "/fise-caz/{$this->id}";
    }
}
