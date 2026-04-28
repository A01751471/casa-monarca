<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = ['id', 'nombre'];
    
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function solicitudesMembresia()
    {
        return $this->hasMany(AreaSolicitud::class)->where('status', 'pendiente');
    }
}
