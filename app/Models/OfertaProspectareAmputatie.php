<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfertaProspectareAmputatie extends Model
{
    use HasFactory;

    protected $table = 'oferte_prospectare_amputatii';
    protected $guarded = [];

    public function oferta(): BelongsTo
    {
        return $this->belongsTo(OfertaProspectare::class, 'oferta_prospectare_id');
    }
}
