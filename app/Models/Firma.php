<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Firma extends Model
{
    protected $fillable = [
        'documento_id',
        'firmado_por',
        'certificado_id',
        'firma_b64',
        'firmado_at',
    ];

    protected $casts = [
        'firmado_at' => 'datetime',
    ];

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class);
    }

    // Puede ser null si el usuario fue borrado — el rastro se preserva igual
    public function firmante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'firmado_por');
    }

    public function certificado(): BelongsTo
    {
        return $this->belongsTo(Certificado::class);
    }
}
