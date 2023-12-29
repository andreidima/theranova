<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\HasMany;

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
}
