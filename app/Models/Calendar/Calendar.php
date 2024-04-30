<?php

namespace App\Models\Calendar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Calendar extends Model
{
    use HasFactory;

    protected $table = 'calendar_calendare';
    protected $guarded = [];

    public function path()
    {
        return "/calendar/calendare/{$this->id}";
    }

    /**
     * Get all of the activitati for the Calendar
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activitati(): HasMany
    {
        return $this->hasMany(Activitate::class, 'calendar_id');
    }
}
