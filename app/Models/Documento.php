<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Documento extends Model
{
    protected $fillable = [
        'expediente_id',
        'subido_por',
        'nombre',
        'tipo',
        'ruta_storage',
        'hash_sha256',
    ];

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class);
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'subido_por');
    }

    public function firmas(): HasMany
    {
        return $this->hasMany(Firma::class);
    }

    public function estaFirmado(): bool
    {
        return $this->firmas()->exists();
    }
}
