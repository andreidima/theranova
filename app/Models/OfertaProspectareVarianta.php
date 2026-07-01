<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OfertaProspectareVarianta extends Model
{
    use HasFactory;

    protected $table = 'oferte_prospectare_variante';
    protected $guarded = [];

    public function oferta(): BelongsTo
    {
        return $this->belongsTo(OfertaProspectare::class, 'oferta_prospectare_id');
    }

    public function configurator(): BelongsTo
    {
        return $this->belongsTo(ProspectareConfigurator::class, 'configurator_id');
    }

    public function componente(): HasMany
    {
        return $this->hasMany(OfertaProspectareVariantaComponenta::class, 'varianta_id');
    }
}
