<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bonus extends Model
{
    use HasFactory;

    public const STATUS_CALCULAT = 'calculat';
    public const STATUS_APROBAT = 'aprobat';
    public const STATUS_PLATIT = 'platit';
    public const STATUS_ANULAT = 'anulat';

    protected $table = 'bonusuri';
    protected $guarded = [];

    protected $casts = [
        'luna_merit' => 'date',
        'data_plata' => 'date',
        'calculated_at' => 'datetime',
        'approved_at' => 'datetime',
        'bonus_fix' => 'integer',
        'bonus_procent' => 'integer',
        'bonus_total' => 'integer',
        'valoare_oferta' => 'integer',
    ];

    public static function statusuri(): array
    {
        return [
            self::STATUS_CALCULAT,
            self::STATUS_APROBAT,
            self::STATUS_PLATIT,
            self::STATUS_ANULAT,
        ];
    }

    public function fisaCaz(): BelongsTo
    {
        return $this->belongsTo(FisaCaz::class, 'fisa_caz_id');
    }

    public function oferta(): BelongsTo
    {
        return $this->belongsTo(Oferta::class, 'oferta_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lucrare(): BelongsTo
    {
        return $this->belongsTo(Lucrare::class, 'lucrare_id');
    }

    public function interval(): BelongsTo
    {
        return $this->belongsTo(LucrareBonusInterval::class, 'interval_id');
    }

    public function istoric(): HasMany
    {
        return $this->hasMany(BonusIstoric::class, 'bonus_id')->orderByDesc('created_at');
    }
}
