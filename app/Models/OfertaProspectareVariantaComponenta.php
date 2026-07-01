<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfertaProspectareVariantaComponenta extends Model
{
    use HasFactory;

    protected $table = 'oferte_prospectare_variante_componente';
    protected $guarded = [];

    public function varianta(): BelongsTo
    {
        return $this->belongsTo(OfertaProspectareVarianta::class, 'varianta_id');
    }

    public function componenta(): BelongsTo
    {
        return $this->belongsTo(ProspectareConfiguratorComponenta::class, 'componenta_id');
    }
}
