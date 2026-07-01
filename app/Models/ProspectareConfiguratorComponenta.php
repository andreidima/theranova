<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProspectareConfiguratorComponenta extends Model
{
    use HasFactory;

    protected $table = 'prospectare_configurator_componente';
    protected $guarded = [];
    protected $casts = [
        'activ' => 'boolean',
    ];

    public function grup(): BelongsTo
    {
        return $this->belongsTo(ProspectareConfiguratorGrup::class, 'grup_id');
    }
}
