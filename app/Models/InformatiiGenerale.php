<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformatiiGenerale extends Model
{
    use HasFactory;

    protected $table = 'informatii_generale';

    protected $fillable = [
        'variabila',
        'valoare',
    ];

    public function path(): string
    {
        return "/informatii-generale/{$this->id}";
    }
}
