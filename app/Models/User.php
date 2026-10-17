<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'password_hash',
        'rol',
        'estado',
        'telefono',
        'direccion',
        'remember_token',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password_hash'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
        }
    }

    public function getNameAttribute()
    {
        return $this->nombre;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['nombre'] = $value;
    }

    public function esAdmin(): bool
    {
        return $this->rol === 'administrador';
    }

    public function esBibliotecario(): bool
    {
        return $this->rol === 'bibliotecario';
    }

    public function esEstudiante(): bool
    {
        return $this->rol === 'estudiante';
    }

    public function esPersonal(): bool
    {
        return in_array($this->rol, ['administrador', 'bibliotecario']);
    }

    public function estaActivo(): bool
    {
        return $this->estado === 'activo';
    }

    public function tienePermiso(string $modulo, string $accion): bool
    {
        $permisos = config('permisos.' . $this->rol . '.' . $modulo, []);
        return in_array($accion, $permisos, true);
    }

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'id_usuario');
    }

    public function multas()
    {
        return $this->hasMany(Multa::class, 'id_usuario');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'id_usuario');
    }
}
