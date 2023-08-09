<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecoltareSangeExpeditor extends Model
{
    use HasFactory;

    protected $table = 'recoltari_sange_expeditori';
    protected $guarded = [];

    public function path()
    {
        return "/recoltari-sange-expeditori/{$this->id}";
    }
}
