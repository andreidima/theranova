<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecoltareSangeCerere extends Model
{
    use HasFactory;

    protected $table = 'recoltari_sange_cereri';
    protected $guarded = [];

    /**
     * Get the grupa that owns the RecoltareSange
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function grupa()
    {
        return $this->belongsTo(RecoltareSangeGrupa::class, 'recoltari_sange_grupa_id');
    }

    /**
     * Get the produs that owns the RecoltareSange
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function produs()
    {
        return $this->belongsTo(RecoltareSangeProdus::class, 'recoltari_sange_produs_id');
    }
}
