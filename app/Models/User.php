<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $fillable = [
        'name', 'email', 'password',
        'area_id', 'role_id', 'status',
        'approved_by',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function certificados()
    {
        return $this->hasMany(Certificado::class)->orderByDesc('emitido_at');
    }

    public function certificadoActivo()
    {
        return $this->hasOne(Certificado::class)->where('status', 'activo')->latestOfMany('emitido_at');
    }

    public function migrantePerfil()
    {
        return $this->hasOne(MigrantePerfil::class);
    }
}
