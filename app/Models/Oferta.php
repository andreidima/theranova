<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Oferta extends Model
{
    use HasFactory;

    protected $table = 'oferte';
    protected $guarded = [];

    public function path()
    {
        return "{$this->fisaCaz->path()}/oferte/{$this->id}";
    }

    /**
     * Get the fisaCaz that owns the Oferta
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fisaCaz(): BelongsTo
    {
        return $this->belongsTo(FisaCaz::class, 'fisa_caz_id',);
    }

    /**
     * Get all of the fisiere for the Oferta
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fisiere(): HasMany
    {
        return $this->hasMany(Fisier::class, 'referinta_id')->where('referinta', 'oferta');
    }
}
