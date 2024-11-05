<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Incasare extends Model
{
    use HasFactory;

    protected $table = 'incasari';
    protected $guarded = [];

    // Specify the data fields that should be treated as Carbon instances
    protected $dates = ['date'];
    // Mutator to format the data before saving it to the database
    public function setDataAttribute($value)
    {
        // Assuming the input format is 'd/m/Y' (day/month/year)
        $this->attributes['data'] = Carbon::createFromFormat('d.m.Y', $value)->format('Y-m-d');
    }
    // Optionally, you can define an accessor to format the date when retrieving it
    public function getDataAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.Y');
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
