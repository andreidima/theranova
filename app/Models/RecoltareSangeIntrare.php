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
     * Get all of the recoltariSange for the RecoltareSangeIntrare
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recoltariSange()
    {
        return $this->hasMany(RecoltareSange::class, 'intrare_id', 'id');
    }

    /**
     * Get the expeditor that owns the RecoltareSangeIntrare
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function expeditor()
    {
        return $this->belongsTo(RecoltareSangeExpeditor::class, 'recoltari_sange_expeditor_id');
    }
}
