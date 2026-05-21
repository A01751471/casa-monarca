<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Documento extends Model
{
    protected $fillable = [
        'user_id',
        'expediente_id',
        'subido_por',
        'categoria',
        'etiqueta',
        'nombre',
        'tipo',
        'ruta_storage',
        'hash_sha256',
    ];

    // ── Relationships ────────────────────────────────────────────

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class);
    }

    public function propietario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'subido_por');
    }

    public function firmas(): HasMany
    {
        return $this->hasMany(Firma::class);
    }

    public function accionesLog(): HasMany
    {
        return $this->hasMany(DocumentoAccionLog::class);
    }

    // ── Scopes ───────────────────────────────────────────────────

    public function scopeDeExpediente($query)
    {
        return $query->where('categoria', 'expediente');
    }

    public function scopeDeIdentidad($query)
    {
        return $query->where('categoria', 'identidad');
    }

    // ── Helpers ──────────────────────────────────────────────────

    public function estaFirmado(): bool
    {
        return $this->firmas()->exists();
    }

    public static function etiquetasIdentidad(): array
    {
        return [
            'Acta de nacimiento',
            'Pasaporte',
            'Identificación oficial',
            'Visa',
            'Permiso de tránsito humanitario',
            'Tarjeta de visitante',
            'Documento migratorio extranjero',
            'Otro',
        ];
    }
}
