<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Multa extends Model
{
    use HasFactory;

    protected $table = 'multas';

    protected $fillable = [
        'id_prestamo',
        'id_usuario',
        'monto',
        'dias_mora',
        'tarifa_diaria',
        'estado',
        'fecha_generacion',
        'fecha_pago',
        'motivo',
    ];

    protected $casts = [
        'fecha_generacion' => 'datetime',
        'fecha_pago' => 'datetime',
        'monto' => 'decimal:2',
        'tarifa_diaria' => 'decimal:2',
    ];

    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class, 'id_prestamo');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function getEstaCerradaAttribute(): bool
    {
        return in_array($this->estado, ['pagada', 'condonada'], true);
    }
}
