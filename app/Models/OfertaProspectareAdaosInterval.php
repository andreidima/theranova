<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfertaProspectareAdaosInterval extends Model
{
    use HasFactory;

    protected $table = 'oferte_prospectare_adaos_intervale';
    protected $guarded = [];
    protected $casts = [
        'activ' => 'boolean',
        'procent' => 'decimal:2',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('activ', true);
    }

    public function scopeForTotal(Builder $query, int $total): Builder
    {
        return $query
            ->active()
            ->where('valoare_min', '<=', $total)
            ->where(function (Builder $query) use ($total) {
                $query->whereNull('valoare_max')
                    ->orWhere('valoare_max', '>=', $total);
            })
            ->orderByDesc('valoare_min');
    }
}
