<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Postulacion extends Model
{
    protected $table = 'postulaciones';

    protected $fillable = ['solicitud_id', 'user_id', 'nota'];

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function colaborador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
