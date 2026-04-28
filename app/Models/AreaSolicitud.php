<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AreaSolicitud extends Model
{
    protected $table = 'area_solicitudes';

    protected $fillable = ['user_id', 'area_id', 'nota', 'status', 'revisado_por', 'revisado_at'];

    protected $casts = ['revisado_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function revisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }
}
