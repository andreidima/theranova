<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MesajTrimisEmail extends Model
{
    use HasFactory;

    protected $table = 'mesaje_trimise_email';
    protected $guarded = [];

    public function path()
    {
        return "/mesaje-trimise-email/{$this->id}";
    }
}
