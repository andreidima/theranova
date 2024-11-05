<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incasare extends Model
{
    use HasFactory;

    protected $table = 'incasari';
    protected $guarded = [];

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
