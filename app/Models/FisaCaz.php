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

    // public function emailuriFisaCazUserVanzari(): HasMany
    // {
    //     return $this->hasMany(MesajTrimisEmail::class, 'referinta_id')->where('referinta2_id', $this->user_vanzari)->where('tip', 1);
    // }

    // public function emailuriFisaCazUserComercial()
    // {
    //     return $this->hasMany(MesajTrimisEmail::class, 'referinta_id')->where('referinta2_id', $this->user_Comercial->id ?? null)->where('tip', 1);
    // }

    // public function emailuriFisaCazUserTehnic()
    // {
    //     return $this->hasMany(MesajTrimisEmail::class, 'referinta_id')->where('referinta2_id', $this->user_Tehnic->id ?? null)->where('tip', 1);
    // }

    // public function emailuriOfertaUserVanzari()
    // {
    //     return $this->hasMany(MesajTrimisEmail::class, 'referinta_id')->where('referinta2_id', $this->user_Vanzari->id ?? null)->where('tip', 2);
    // }

    // public function emailuriOfertaUserComercial()
    // {
    //     return $this->hasMany(MesajTrimisEmail::class, 'referinta_id')->where('referinta2_id', $this->user_Comercial->id ?? null)->where('tip', 2);
    // }

    // public function emailuriOfertaUserTehnic()
    // {
    //     return $this->hasMany(MesajTrimisEmail::class, 'referinta_id')->where('referinta2_id', $this->user_Tehnic->id ?? null)->where('tip', 2);
    // }

    // public function emailuriComandaUserVanzari()
    // {
    //     return $this->hasMany(MesajTrimisEmail::class, 'referinta_id')->where('referinta2_id', $this->user_Vanzari->id ?? null)->where('tip', 3);
    // }

    // public function emailuriComandaUserComercial()
    // {
    //     return $this->hasMany(MesajTrimisEmail::class, 'referinta_id')->where('referinta2_id', $this->user_Comercial->id ?? null)->where('tip', 3);
    // }

    // public function emailuriComandaUserTehnic()
    // {
    //     return $this->hasMany(MesajTrimisEmail::class, 'referinta_id')->where('referinta2_id', $this->user_Tehnic->id ?? null)->where('tip', 3);
    // }
}
