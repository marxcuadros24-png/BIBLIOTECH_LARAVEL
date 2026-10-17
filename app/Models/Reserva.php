<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $table = 'reservas';

    protected $fillable = [
        'id_usuario',
        'id_libro',
        'fecha_reserva',
        'fecha_expiracion',
        'estado',
    ];

    protected $casts = [
        'fecha_reserva' => 'datetime',
        'fecha_expiracion' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function libro()
    {
        return $this->belongsTo(Libro::class, 'id_libro');
    }

    public function prestamo()
    {
        return $this->hasOne(Prestamo::class, 'id_reserva');
    }
}
