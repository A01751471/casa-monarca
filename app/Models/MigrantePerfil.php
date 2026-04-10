<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MigrantePerfil extends Model
{
    protected $table = 'migrante_perfiles';

    protected $fillable = [
        'user_id',
        'fecha_atencion',
        'nombre',
        'primer_apellido',
        'segundo_apellido',
        'telefono',
        'genero',
        'pais_origen',
        'departamento_estado',
        'estado_civil',
        'fecha_nacimiento',
        'rango_edad',
        'grupo_poblacion',
        'motivo_salida',
        'num_acompanantes',
        'integrantes_grupo',
        'documentacion',
        'necesidades_especiales',
        'destino_final',
        'status',
        'registrado_por',
    ];

    protected $casts = [
        'fecha_atencion'  => 'date',
        'fecha_nacimiento' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}
