<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pacient extends Model
{
    use HasFactory;

    protected $table = 'pacienti';
    protected $guarded = [];

    public function path()
    {
        return "/pacienti/{$this->id}";
    }

    /**
     * Get all of the apartinatori for the Pacient
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function apartinatori(): HasMany
    {
        return $this->hasMany(Apartinator::class, 'pacient_id');
    }

    /**
     * Get all of the fiseCaz for the Pacient
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fiseCaz(): HasMany
    {
        return $this->hasMany(FisaCaz::class, 'pacient_id');
    }
    /**
     * Get the pacient's latest fisaCaz.
     */
    public function latestFisaCaz(): HasOne
    {
        return $this->hasOne(FisaCaz::class)->latestOfMany();
    }

    /**
     * Get the responsabil that owns the Pacient
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function responsabil(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_responsabil');
    }
}
