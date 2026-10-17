<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\Libro;
use App\Models\Multa;
use App\Models\Prestamo;
use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $estado = $request->input('estado');

        $query = Reserva::with(['libro', 'usuario', 'prestamo']);

        if ($user->esEstudiante()) {
            $query->where('id_usuario', $user->id);
        }

        if (!empty($estado)) {
            $query->where('estado', $estado);
        }

        $reservas = $query->orderBy('fecha_reserva', 'desc')->paginate(15)->withQueryString();

        return view('reservas.index', compact('reservas', 'estado'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_libro' => ['required', 'exists:libros,id'],
        ]);

        $user = Auth::user();

        // Regla: Bloquear morosos si está activo en configuración
        $bloquearMorosos = filter_var(Configuracion::get('bloquear_morosos', true), FILTER_VALIDATE_BOOLEAN);
        if ($bloquearMorosos && Multa::where('id_usuario', $user->id)->where('estado', 'pendiente')->exists()) {
            return back()->with('error', 'Tienes multas pendientes de pago. Acércate a la biblioteca para regularizarlas antes de realizar reservas.');
        }

        // Regla: Máximo de reservas activas
        $maxReservas = (int) Configuracion::get('max_reservas_activas', 3);
        $reservasActivas = Reserva::where('id_usuario', $user->id)->whereIn('estado', ['pendiente', 'confirmada'])->count();
        if ($reservasActivas >= $maxReservas) {
            return back()->with('error', "Has alcanzado el límite máximo permitido de reservas simultáneas ({$maxReservas}).");
        }

        // Verificar si ya tiene una reserva del mismo libro
        $reservaExistente = Reserva::where('id_usuario', $user->id)
            ->where('id_libro', $validated['id_libro'])
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->exists();

        if ($reservaExistente) {
            return back()->with('error', 'Ya tienes una reserva activa o pendiente para este libro.');
        }

        $libro = Libro::findOrFail($validated['id_libro']);
        $horasVigencia = (int) Configuracion::get('horas_vigencia_reserva', 48);

        Reserva::create([
            'id_usuario' => $user->id,
            'id_libro' => $libro->id,
            'fecha_reserva' => Carbon::now(),
            'fecha_expiracion' => Carbon::now()->addHours($horasVigencia),
            'estado' => 'pendiente',
        ]);

        return back()->with('exito', "Has reservado el libro '{$libro->titulo}'. Tienes {$horasVigencia} horas para recogerlo en mostrador.");
    }

    public function confirmar($id)
    {
        $reserva = Reserva::with(['libro', 'usuario'])->findOrFail($id);

        if ($reserva->estado !== 'pendiente') {
            return back()->with('error', "La reserva no está pendiente (estado actual: {$reserva->estado}).");
        }

        $usuario = $reserva->usuario;
        $libro = $reserva->libro;

        if (!$usuario->estaActivo()) {
            return back()->with('error', "El estudiante {$usuario->nombre} tiene su cuenta bloqueada.");
        }

        $bloquearMorosos = filter_var(Configuracion::get('bloquear_morosos', true), FILTER_VALIDATE_BOOLEAN);
        if ($bloquearMorosos && Multa::where('id_usuario', $usuario->id)->where('estado', 'pendiente')->exists()) {
            return back()->with('error', "El estudiante {$usuario->nombre} tiene multas pendientes de pago.");
        }

        if ($libro->stock_disponible < 1) {
            return back()->with('error', "No quedan ejemplares disponibles de '{$libro->titulo}'.");
        }

        return DB::transaction(function () use ($reserva, $usuario, $libro) {
            $diasPrestamo = (int) Configuracion::get('dias_prestamo', 14);

            $prestamo = Prestamo::create([
                'id_usuario' => $usuario->id,
                'id_libro' => $libro->id,
                'id_reserva' => $reserva->id,
                'fecha_prestamo' => Carbon::today()->toDateString(),
                'fecha_devolucion_esperada' => Carbon::today()->addDays($diasPrestamo)->toDateString(),
                'estado' => 'activo',
                'notas' => "Préstamo originado automáticamente del pedido de reserva #{$reserva->id}.",
            ]);

            // Descontar stock disponible
            $libro->decrement('stock_disponible');

            // Marcar reserva como confirmada
            $reserva->update(['estado' => 'confirmada']);

            return back()->with('exito', "Pedido de reserva #{$reserva->id} atendido: Libro '{$libro->titulo}' entregado a {$usuario->nombre}. Se generó el Préstamo #{$prestamo->id} (plazo {$diasPrestamo} días).");
        });
    }

    public function devolver(Request $request, $id)
    {
        $reserva = Reserva::with(['prestamo.libro', 'libro'])->findOrFail($id);

        $prestamo = $reserva->prestamo;
        if (!$prestamo || !in_array($prestamo->estado, ['activo', 'vencido'], true)) {
            return back()->with('error', 'Esta reserva no cuenta con un préstamo activo pendiente de devolución.');
        }

        return app(PrestamoController::class)->devolver($request, $prestamo->id);
    }

    public function cancelar($id)
    {
        $reserva = Reserva::findOrFail($id);
        $user = Auth::user();

        if ($user->esEstudiante() && $reserva->id_usuario !== $user->id) {
            abort(403);
        }

        $reserva->update(['estado' => 'cancelada']);

        return back()->with('exito', "La reserva #{$reserva->id} ha sido cancelada.");
    }
}