<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonusIstoric extends Model
{
    use HasFactory;

    protected $table = 'bonusuri_istoric';
    protected $guarded = [];

    protected $casts = [
        'bonus_total' => 'integer',
        'data_plata' => 'date',
    ];

    public function bonus(): BelongsTo
    {
        return $this->belongsTo(Bonus::class, 'bonus_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
