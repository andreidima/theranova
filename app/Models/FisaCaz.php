<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    /**
     * Get ofertaAcceptata for the FisaCaz
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ofertaAcceptata(): HasOne
    {
        return $this->hasOne(Oferta::class, 'fisa_caz_id')->where('acceptata', 1);
    }

    /**
     * Get all of the fisiereComanda for the FisaCaz
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fisiereComanda(): HasMany
    {
        return $this->hasMany(Fisier::class, 'referinta_id')->where('referinta', 2);
    }

    /**
     * Get all of the oferte for the FisaCaz
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comenzi(): HasMany
    {
        return $this->hasMany(Comanda::class, 'fisa_caz_id');
    }

    /**
     * Get all of the fisiereFisaMasuri for the FisaCaz
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fisiereFisaMasuri(): HasMany
    {
        return $this->hasMany(Fisier::class, 'referinta_id')->where('referinta', 3);
    }

    /**
     * Get all of the comenziComponente for the FisaCaz
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comenziComponente(): HasMany
    {
        return $this->hasMany(ComandaComponenta::class, 'fisa_caz_id');
    }

    public function userCanDelete() {
        return auth()->user()->hasRole("stergere");
    }

    public function emailuriFisaCaz(): HasMany
    {
        return $this->hasMany(MesajTrimisEmail::class, 'referinta_id')->where('tip', 1);
    }
    public function emailuriOferta(): HasMany
    {
        return $this->hasMany(MesajTrimisEmail::class, 'referinta_id')->where('tip', 2);
    }
    public function emailuriComanda(): HasMany
    {
        return $this->hasMany(MesajTrimisEmail::class, 'referinta_id')->where('tip', 3);
    }
    public function emailReminderAKProvizorie(): HasOne
    {
        return $this->hasOne(MesajTrimisEmail::class, 'referinta_id')->where('tip', 5);
    }
    public function emailReminderBKProvizorie(): HasOne
    {
        return $this->hasOne(MesajTrimisEmail::class, 'referinta_id')->where('tip', 6);
    }

    /**
     * Get the activitate associated with the FisaCaz
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function activitate(): HasOne
    {
        return $this->hasOne(Calendar\Activitate::class, 'fisa_caz_id');
    }
}
