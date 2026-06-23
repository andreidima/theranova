<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProdusProspectare extends Model
{
    use HasFactory;

    protected $table = 'produse_prospectare';
    protected $guarded = [];
    protected $casts = [
        'activ' => 'boolean',
    ];

    public function path(): string
    {
        return "/oferte-prospectare/produse/{$this->id}";
    }

    public function liniiOferta(): HasMany
    {
        return $this->hasMany(OfertaProspectareLinie::class, 'produs_prospectare_id');
    }
}
