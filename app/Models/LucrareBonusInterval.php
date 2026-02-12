<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LucrareBonusInterval extends Model
{
    use HasFactory;

    protected $table = 'lucrari_bonus_intervale';
    protected $guarded = [];
    protected $casts = [
        'min_valoare' => 'integer',
        'max_valoare' => 'integer',
        'bonus_fix' => 'integer',
        'bonus_procent' => 'integer',
        'activ' => 'boolean',
        'valid_from' => 'date',
        'valid_to' => 'date',
    ];

    public function lucrare(): BelongsTo
    {
        return $this->belongsTo(Lucrare::class, 'lucrare_id');
    }

    public function bonusuri(): HasMany
    {
        return $this->hasMany(Bonus::class, 'interval_id');
    }
}
