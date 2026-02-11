<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Incasare;

class Oferta extends Model
{
    use HasFactory;

    public const STATUS_NEACCEPTATA = 0;
    public const STATUS_ACCEPTATA = 1;
    public const STATUS_IN_ASTEPTARE = 2;
    public const STATUS_ARHIVATA = 3;

    protected $table = 'oferte';
    protected $guarded = [];

    public static function statusLabels(): array
    {
        return [
            self::STATUS_NEACCEPTATA => 'Neacceptata',
            self::STATUS_ACCEPTATA => 'Acceptata',
            self::STATUS_IN_ASTEPTARE => 'In asteptare',
            self::STATUS_ARHIVATA => 'Arhivata',
        ];
    }

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
        return $this->hasMany(Fisier::class, 'referinta_id')->where('referinta', 1);
    }

    /**
     * Get all of the incasari for the oferta
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function incasari(): HasMany
    {
        return $this->hasMany(Incasare::class, 'oferta_id');
    }

    public function deciziiCas(): HasMany
    {
        return $this->hasMany(Incasare::class, 'oferta_id')->where('tip', Incasare::TIP_DECIZIE_CAS);
    }
}
