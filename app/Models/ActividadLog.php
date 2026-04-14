<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActividadLog extends Model
{
    protected $table = 'actividad_log';

    protected $fillable = [
        'actor_id',
        'actor_nombre',
        'accion',
        'modelo_tipo',
        'modelo_id',
        'payload',
        'ip',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    /**
     * Registra una acción en el log de forma estática.
     * Uso: ActividadLog::registrar('aprobó_usuario', $user, ['antes' => ..., 'despues' => ...]);
     */
    public static function registrar(string $accion, ?Model $modelo = null, array $payload = []): self
    {
        $actor = auth()->user();

        return self::create([
            'actor_id'     => $actor?->id,
            'actor_nombre' => $actor?->name ?? 'Sistema',
            'accion'       => $accion,
            'modelo_tipo'  => $modelo ? get_class($modelo) : null,
            'modelo_id'    => $modelo?->id,
            'payload'      => $payload,
            'ip'           => request()->ip(),
        ]);
    }
}
