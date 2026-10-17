<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    use HasFactory;

    protected $table = 'prestamos';

    protected $fillable = [
        'id_usuario',
        'id_libro',
        'id_reserva',
        'fecha_prestamo',
        'fecha_devolucion_esperada',
        'fecha_devolucion_real',
        'estado',
        'solicitud_devolucion',
        'notas',
    ];

    protected $casts = [
        'fecha_prestamo' => 'date',
        'fecha_devolucion_esperada' => 'date',
        'fecha_devolucion_real' => 'date',
        'solicitud_devolucion' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function libro()
    {
        return $this->belongsTo(Libro::class, 'id_libro');
    }

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'id_reserva');
    }

    public function multas()
    {
        return $this->hasMany(Multa::class, 'id_prestamo');
    }

    public static function sincronizarVencidos(): int
    {
        return static::where('estado', 'activo')
            ->where('fecha_devolucion_esperada', '<', Carbon::today()->toDateString())
            ->update(['estado' => 'vencido']);
    }

    public function getDiasAtrasoAttribute(): int
    {
        $fechaComparacion = $this->fecha_devolucion_real ?? Carbon::today();
        if ($this->fecha_devolucion_esperada && $fechaComparacion->gt($this->fecha_devolucion_esperada)) {
            return (int) $this->fecha_devolucion_esperada->diffInDays($fechaComparacion, false);
        }
        return 0;
    }
}
