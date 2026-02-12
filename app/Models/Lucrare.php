<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lucrare extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lucrari';
    protected $guarded = [];

    public function intervaleBonus(): HasMany
    {
        return $this->hasMany(LucrareBonusInterval::class, 'lucrare_id')->orderBy('min_valoare');
    }

    public function fiseCaz(): HasMany
    {
        return $this->hasMany(FisaCaz::class, 'tip_lucrare_solicitata_id');
    }

    public function bonusuri(): HasMany
    {
        return $this->hasMany(Bonus::class, 'lucrare_id');
    }
}
