<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comanda extends Model
{
    use HasFactory;

    protected $table = 'comenzi';
    protected $guarded = [];

    public function path()
    {
        return "{$this->fisaCaz->path()}/comenzi/{$this->id}";
    }

    /**
     * Get the fisaCaz that owns the FisaComanda
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fisaCaz(): BelongsTo
    {
        return $this->belongsTo(FisaCaz::class, 'fisa_caz_id',);
    }

    /**
     * Get all of the comenziComponente for the FisaCaz
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function componente(): HasMany
    {
        return $this->hasMany(ComandaComponenta::class, 'comanda_id');
    }

    /**
     * Get all of the fisiere for the FisaComanda
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fisiere(): HasMany
    {
        return $this->hasMany(Fisier::class, 'referinta_id')->where('referinta', 4);
    }

    public function emailuriTrimise(): HasMany
    {
        return $this->hasMany(MesajTrimisEmail::class, 'referinta_id')->where('tip', 7);
    }
}
