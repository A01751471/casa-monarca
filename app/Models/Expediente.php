<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expediente extends Model
{
    protected $fillable = [
        'folio',
        'migrante_perfil_id',
        'colaborador_id',
        'area_id',
        'status',
        'notas',
        'resuelto_por',
        'resuelto_at',
    ];

    protected $casts = [
        'resuelto_at' => 'datetime',
    ];

    public function migrantePerfil(): BelongsTo
    {
        return $this->belongsTo(MigrantePerfil::class);
    }

    public function colaborador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'colaborador_id');
    }

    public function resueltoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resuelto_por');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class);
    }

    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class);
    }

    public static function generarFolio(): string
    {
        $anio = now()->year;
        $ultimo = static::whereYear('created_at', $anio)->max('id') ?? 0;
        return 'CM-' . $anio . '-' . str_pad($ultimo + 1, 4, '0', STR_PAD_LEFT);
    }
}
