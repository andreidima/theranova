<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FisaCaz extends Model
{
    use HasFactory;

    protected $table = 'fise_caz';
    protected $guarded = [];

    public function path()
    {
        return "/fise-caz/{$this->id}";
    }

    /**
     * Get the pacient that owns the FisaCaz
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pacient(): BelongsTo
    {
        return $this->belongsTo(Pacient::class, 'pacient_id');
    }

    /**
     * Get the user that owns the FisaCaz
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userVanzari(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_vanzari');
    }

    /**
     * Get the user that owns the FisaCaz
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userComercial(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_comercial');
    }

    /**
     * Get the user that owns the FisaCaz
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userTehnic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_tehnic');
    }

    /**
     * Get all of the dateMedicale for the FisaCaz
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dateMedicale(): HasMany
    {
        return $this->hasMany(DataMedicala::class, 'fisa_caz_id');
    }

    /**
     * Get all of the cerinte for the FisaCaz
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cerinte(): HasMany
    {
        return $this->hasMany(Cerinta::class, 'fisa_caz_id');
    }

    /**
     * Get all of the oferte for the FisaCaz
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function oferte(): HasMany
    {
        return $this->hasMany(Oferta::class, 'fisa_caz_id');
    }
}
