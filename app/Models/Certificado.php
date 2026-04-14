<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Certificado extends Model
{
    protected $table = 'certificados';

    protected $fillable = [
        'user_id',
        'emitido_por',
        'public_key',
        'fingerprint',
        'algoritmo',
        'emitido_at',
        'vence_at',
        'revocado_at',
        'status',
    ];

    protected $casts = [
        'emitido_at'  => 'datetime',
        'vence_at'    => 'datetime',
        'revocado_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function emisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'emitido_por');
    }

    public function firmas(): HasMany
    {
        return $this->hasMany(Firma::class);
    }

    public function estaActivo(): bool
    {
        return $this->status === 'activo' && $this->vence_at->isFuture();
    }
}
