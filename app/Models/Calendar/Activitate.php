<?php

namespace App\Models\Calendar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activitate extends Model
{
    use HasFactory;

    protected $table = 'calendar_activitati';
    protected $guarded = [];

    public function path()
    {
        return "/calendar/activitati/{$this->id}";
    }

    /**
     * Get the calendar that owns the Activitate
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function calendar(): BelongsTo
    {
        return $this->belongsTo(Calendar::class, 'calendar_id');
    }

    /**
     * Get the fisaCaz that owns the Activitate
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fisaCaz(): BelongsTo
    {
        return $this->belongsTo(\App\Models\FisaCaz::class, 'fisa_caz_id');
    }
}
