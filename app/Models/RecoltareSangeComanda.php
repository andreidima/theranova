<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecoltareSangeComanda extends Model
{
    use HasFactory;

    protected $table = 'recoltari_sange_comenzi';
    protected $guarded = [];

    public function path()
    {
        return "/recoltari-sange/comenzi/{$this->id}";
    }

    /**
     * The recoltariSange that belong to the RecoltareSangeComanda
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    // public function recoltariSange()
    // {
    //     return $this->belongsToMany(RecoltareSange::class, 'recoltari_sange', 'id', 'comanda_id');
    // }

    /**
     * Get all of the recoltariSange for the RecoltareSangeComanda
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recoltariSange()
    {
        return $this->hasMany(RecoltareSange::class, 'comanda_id', 'id');
    }

    /**
     * The recoltariSange that belong to the RecoltareSangeComanda
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    // public function recoltariSange()
    // {
    //     return $this->belongsToMany(RecoltareSange::class, 'recoltari_sange', 'comanda_id', 'id');
    // }
}
