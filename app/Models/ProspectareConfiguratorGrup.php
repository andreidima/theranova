<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProspectareConfiguratorGrup extends Model
{
    use HasFactory;

    protected $table = 'prospectare_configurator_grupuri';
    protected $guarded = [];

    public function configurator(): BelongsTo
    {
        return $this->belongsTo(ProspectareConfigurator::class, 'configurator_id');
    }

    public function componente(): HasMany
    {
        return $this->hasMany(ProspectareConfiguratorComponenta::class, 'grup_id')->orderBy('ordine')->orderBy('id');
    }
}
