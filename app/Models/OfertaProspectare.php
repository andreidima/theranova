<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OfertaProspectare extends Model
{
    use HasFactory;

    public const APROBARE_DRAFT = 'draft';
    public const APROBARE_IN_ASTEPTARE = 'in_asteptare';
    public const APROBARE_MODIFICARI = 'modificari';
    public const APROBARE_APROBATA = 'aprobata';
    public const APROBARE_RESPINSA = 'respinsa';

    public const CLIENT_NESTRIMISA = 'nestrimisa';
    public const CLIENT_TRIMISA = 'trimisa';
    public const CLIENT_APROBATA = 'aprobata';
    public const CLIENT_RESPINSA = 'respinsa';
    public const CLIENT_EXPIRATA = 'expirata';
    public const CLIENT_CONVERTITA = 'convertita';

    protected $table = 'oferte_prospectare';
    protected $guarded = [];
    protected $casts = [
        'data_ofertei' => 'date',
        'valabila_pana_la' => 'date',
        'decontare_cas' => 'boolean',
        'trimisa_la' => 'datetime',
        'aprobata_la' => 'datetime',
        'raspuns_client_la' => 'datetime',
        'convertita_la' => 'datetime',
    ];

    public function path(): string
    {
        return "/oferte-prospectare/{$this->id}";
    }

    public static function statusuriAprobare(): array
    {
        return [
            self::APROBARE_DRAFT => 'Draft',
            self::APROBARE_IN_ASTEPTARE => 'In asteptare aprobare',
            self::APROBARE_MODIFICARI => 'Necesita modificari',
            self::APROBARE_APROBATA => 'Aprobata intern',
            self::APROBARE_RESPINSA => 'Respinsa intern',
        ];
    }

    public static function statusuriClient(): array
    {
        return [
            self::CLIENT_NESTRIMISA => 'Netrimisa',
            self::CLIENT_TRIMISA => 'Trimisa / in asteptare',
            self::CLIENT_APROBATA => 'Cerere aprobata',
            self::CLIENT_RESPINSA => 'Cerere respinsa',
            self::CLIENT_EXPIRATA => 'Expirata',
            self::CLIENT_CONVERTITA => 'Convertita in fisa caz',
        ];
    }

    public function emitent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_emitent_id');
    }

    public function aprobator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_aprobator_id');
    }

    public function pacient(): BelongsTo
    {
        return $this->belongsTo(Pacient::class, 'pacient_id');
    }

    public function fisaCaz(): BelongsTo
    {
        return $this->belongsTo(FisaCaz::class, 'fisa_caz_id');
    }

    public function linii(): HasMany
    {
        return $this->hasMany(OfertaProspectareLinie::class, 'oferta_prospectare_id');
    }

    public function amputatii(): HasMany
    {
        return $this->hasMany(OfertaProspectareAmputatie::class, 'oferta_prospectare_id');
    }

    public function trimiteri(): HasMany
    {
        return $this->hasMany(OfertaProspectareTrimitere::class, 'oferta_prospectare_id');
    }

    public function recalculeazaTotaluri(): void
    {
        $subtotal = (int) $this->linii()->sum('valoare_linie');
        $bugetCas = $this->decontare_cas ? (int) ($this->buget_disponibil ?? 0) : 0;
        $dupaDecontare = max(0, $subtotal - $bugetCas);
        $total = max(0, $dupaDecontare - (int) ($this->discount_aditional ?? 0));

        $this->forceFill([
            'subtotal' => $subtotal,
            'valoare_dupa_decontare' => $dupaDecontare,
            'valoare_totala' => $total,
            'valoare_avans' => (int) round($total * 0.7),
        ])->save();
    }
}
