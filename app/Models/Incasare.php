<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Incasare extends Model
{
    use HasFactory;

    public const TIP_INCASARE = 'incasare';
    public const TIP_DECIZIE_CAS = 'decizie_cas';

    protected $table = 'incasari';
    protected $guarded = [];

    protected $dates = ['data', 'data_inregistrare', 'data_validare'];

    // Mutator to format the data before saving it to the database
    public function setDataAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['data'] = null;

            return;
        }

        $this->attributes['data'] = Carbon::createFromFormat('d.m.Y', $value)->format('Y-m-d');
    }
    // Optionally, you can define an accessor to format the date when retrieving it
    public function getDataAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d.m.Y') : null;
    }

    public function setDataInregistrareAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['data_inregistrare'] = null;

            return;
        }

        $this->attributes['data_inregistrare'] = Carbon::createFromFormat('d.m.Y', $value)->format('Y-m-d');
    }

    public function getDataInregistrareAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d.m.Y') : null;
    }

    public function setDataValidareAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['data_validare'] = null;

            return;
        }

        $this->attributes['data_validare'] = Carbon::createFromFormat('d.m.Y', $value)->format('Y-m-d');
    }

    public function getDataValidareAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d.m.Y') : null;
    }

    /**
     * Get the oferta that owns the incasare
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function oferta(): BelongsTo
    {
        return $this->belongsTo(Oferta::class, 'oferta_id');
    }
}
