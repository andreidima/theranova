<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComandaComponenta extends Model
{
    use HasFactory;

    protected $table = 'comenzi_componente';
    protected $guarded = [];

    public function path()
    {
        return "{$this->fisaCaz->path()}/comenzi-componente/{$this->id}";
    }

    /**
     * Get the fisaCaz that owns the ComandaComponenta
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fisaCaz(): BelongsTo
    {
        return $this->belongsTo(FisaCaz::class, 'fisa_caz_id',);
    }
}
