<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecoltareSangeIntrare extends Model
{
    use HasFactory;

    protected $table = 'recoltari_sange_intrari';
    protected $guarded = [];

    public function path()
    {
        return "/recoltari-sange/intrari/{$this->id}";
    }

    /**
     * The recoltariSange that belong to the RecoltareSangeIntrare
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    // public function recoltariSange()
    // {
    //     return $this->belongsToMany(RecoltareSange::class, 'recoltari_sange', 'id', 'comanda_id');
    // }

    /**
     * Get all of the recoltariSange for the RecoltareSangeIntrare
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recoltariSange()
    {
        return $this->hasMany(RecoltareSange::class, 'intrare_id', 'id');
    }

    /**
     * Get the beneficiar that owns the RecoltareSangeIntrare
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function beneficiar()
    {
        return $this->belongsTo(RecoltareSangeBeneficiar::class, 'recoltari_sange_beneficiar_id');
    }
}
