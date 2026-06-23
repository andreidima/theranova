<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfertaProspectareLinie extends Model
{
    use HasFactory;

    protected $table = 'oferte_prospectare_linii';
    protected $guarded = [];

    public function oferta(): BelongsTo
    {
        return $this->belongsTo(OfertaProspectare::class, 'oferta_prospectare_id');
    }

    public function produs(): BelongsTo
    {
        return $this->belongsTo(ProdusProspectare::class, 'produs_prospectare_id');
    }
}
