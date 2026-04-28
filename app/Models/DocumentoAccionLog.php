<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentoAccionLog extends Model
{
    protected $table = 'documento_acciones_log';

    protected $fillable = ['documento_id', 'expediente_id', 'user_id', 'accion', 'detalle'];

    protected $casts = ['detalle' => 'array'];

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class);
    }

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
