<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProspectareConfigurator extends Model
{
    use HasFactory;

    protected $table = 'prospectare_configuratoare';
    protected $guarded = [];
    protected $casts = [
        'activ' => 'boolean',
    ];

    public function grupuri(): HasMany
    {
        return $this->hasMany(ProspectareConfiguratorGrup::class, 'configurator_id')->orderBy('ordine')->orderBy('id');
    }
}
