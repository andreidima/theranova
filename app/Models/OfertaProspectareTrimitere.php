<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfertaProspectareTrimitere extends Model
{
    use HasFactory;

    protected $table = 'oferte_prospectare_trimiteri';
    protected $guarded = [];

    public function oferta(): BelongsTo
    {
        return $this->belongsTo(OfertaProspectare::class, 'oferta_prospectare_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
