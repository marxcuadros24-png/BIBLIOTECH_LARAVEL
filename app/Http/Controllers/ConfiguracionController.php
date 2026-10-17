<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $config = Configuracion::todas();

        // Valores con respaldo
        $diasPrestamo = $config['dias_prestamo'] ?? 14;
        $maxPrestamos = $config['max_prestamos_activos'] ?? 3;
        $maxReservas = $config['max_reservas_activas'] ?? 3;
        $horasVigenciaReserva = $config['horas_vigencia_reserva'] ?? 48;
        $multaPorDia = $config['multa_por_dia'] ?? 0.20;
        $diasGracia = $config['dias_gracia'] ?? 0;
        $bloquearMorosos = filter_var($config['bloquear_morosos'] ?? true, FILTER_VALIDATE_BOOLEAN);

        return view('configuracion.index', compact(
            'diasPrestamo',
            'maxPrestamos',
            'maxReservas',
            'horasVigenciaReserva',
            'multaPorDia',
            'diasGracia',
            'bloquearMorosos'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'dias_prestamo' => ['required', 'integer', 'min:1', 'max:365'],
            'max_prestamos_activos' => ['required', 'integer', 'min:1', 'max:50'],
            'max_reservas_activas' => ['required', 'integer', 'min:1', 'max:50'],
            'horas_vigencia_reserva' => ['required', 'integer', 'min:1', 'max:720'],
            'multa_por_dia' => ['required', 'numeric', 'min:0'],
            'dias_gracia' => ['required', 'integer', 'min:0', 'max:30'],
            'bloquear_morosos' => ['nullable', 'boolean'],
        ]);

        Configuracion::set('dias_prestamo', $validated['dias_prestamo'], 'Plazo máximo para devolver un libro.');
        Configuracion::set('max_prestamos_activos', $validated['max_prestamos_activos'], 'Máximo de préstamos activos simultáneos por usuario.');
        Configuracion::set('max_reservas_activas', $validated['max_reservas_activas'], 'Máximo de reservas activas por usuario.');
        Configuracion::set('horas_vigencia_reserva', $validated['horas_vigencia_reserva'], 'Horas de vigencia de la reserva antes de expirar.');
        Configuracion::set('multa_por_dia', number_format((float) $validated['multa_por_dia'], 2, '.', ''), 'Mora diaria en moneda local (S/).');
        Configuracion::set('dias_gracia', $validated['dias_gracia'], 'Días de gracia antes de empezar a generar multa.');
        Configuracion::set('bloquear_morosos', $request->has('bloquear_morosos') ? '1' : '0', 'Bloquear usuarios con multas pendientes.');

        return redirect()->route('configuracion.index')
            ->with('exito', 'Configuración del sistema actualizada correctamente.');
    }
}