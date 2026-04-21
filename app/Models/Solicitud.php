<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Solicitud extends Model
{
    protected $table = 'solicitudes';

    protected $fillable = [
        'migrante_perfil_id',
        'user_id',
        'area_id',
        'expediente_id',
        'tipo',
        'descripcion',
        'status',
        'atendida_por',
    ];

    public function migrantePerfil(): BelongsTo
    {
        return $this->belongsTo(MigrantePerfil::class);
    }

    public function solicitante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class);
    }

    public function atendioPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'atendida_por');
    }
}
