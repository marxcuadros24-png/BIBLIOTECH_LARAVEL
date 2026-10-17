<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\Libro;
use App\Models\Multa;
use App\Models\Prestamo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PrestamoController extends Controller
{
    public function index(Request $request)
    {
        Prestamo::sincronizarVencidos();

        $user = Auth::user();
        $estado = $request->input('estado');
        $busqueda = $request->input('q');

        $query = Prestamo::with(['libro', 'usuario']);

        // Si es estudiante, restringir a sus propios préstamos
        if ($user->esEstudiante()) {
            $query->where('id_usuario', $user->id);
        } elseif (!empty($busqueda)) {
            $query->where(function ($q) use ($busqueda) {
                $q->whereHas('libro', function ($ql) use ($busqueda) {
                    $ql->where('titulo', 'LIKE', "%{$busqueda}%")
                       ->orWhere('autor', 'LIKE', "%{$busqueda}%");
                })->orWhereHas('usuario', function ($qu) use ($busqueda) {
                    $qu->where('nombre', 'LIKE', "%{$busqueda}%")
                       ->orWhere('email', 'LIKE', "%{$busqueda}%");
                });
            });
        }

        if (!empty($estado)) {
            $query->where('estado', $estado);
        }

        $prestamos = $query->orderBy('fecha_devolucion_esperada', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('prestamos.index', compact('prestamos', 'estado', 'busqueda'));
    }

    public function create(Request $request)
    {
        $idLibro = $request->input('libro_id');
        $idUsuario = $request->input('usuario_id');

        $usuarios = User::where('estado', 'activo')
            ->orderBy('nombre', 'asc')
            ->get();

        $librosDisponibles = Libro::where('stock_disponible', '>', 0)
            ->orderBy('titulo', 'asc')
            ->get();

        $diasPorDefecto = (int) Configuracion::get('dias_prestamo', 14);
        $fechaEsperadaDefault = Carbon::today()->addDays($diasPorDefecto)->toDateString();

        return view('prestamos.create', compact('usuarios', 'librosDisponibles', 'idLibro', 'idUsuario', 'fechaEsperadaDefault'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_usuario' => ['required', 'exists:usuarios,id'],
            'id_libro' => ['required', 'exists:libros,id'],
            'fecha_devolucion_esperada' => ['required', 'date', 'after:today'],
            'notas' => ['nullable', 'string', 'max:500'],
        ], [
            'id_usuario.required' => 'Debe seleccionar un usuario.',
            'id_libro.required' => 'Debe seleccionar un libro.',
            'fecha_devolucion_esperada.required' => 'La fecha esperada de devolución es obligatoria.',
            'fecha_devolucion_esperada.after' => 'La fecha de devolución debe ser posterior al día de hoy.',
        ]);

        return DB::transaction(function () use ($validated) {
            $usuario = User::where('id', $validated['id_usuario'])->lockForUpdate()->firstOrFail();
            $libro = Libro::where('id', $validated['id_libro'])->lockForUpdate()->firstOrFail();

            if (!$usuario->estaActivo()) {
                return back()->withInput()->with('error', 'El usuario seleccionado está bloqueado y no puede recibir préstamos.');
            }

            // Regla: Bloquear si tiene multas pendientes
            $bloquearMorosos = filter_var(Configuracion::get('bloquear_morosos', true), FILTER_VALIDATE_BOOLEAN);
            if ($bloquearMorosos && Multa::where('id_usuario', $usuario->id)->where('estado', 'pendiente')->exists()) {
                return back()->withInput()->with('error', "El usuario {$usuario->nombre} tiene multas pendientes de pago y las políticas del sistema bloquean nuevos préstamos.");
            }

            // Regla: Máximo de préstamos activos
            $maxPrestamos = (int) Configuracion::get('max_prestamos_activos', 3);
            $prestamosActivos = Prestamo::where('id_usuario', $usuario->id)->whereIn('estado', ['activo', 'vencido'])->count();
            if ($prestamosActivos >= $maxPrestamos) {
                return back()->withInput()->with('error', "El usuario ya ha alcanzado el límite máximo de préstamos activos ({$maxPrestamos}).");
            }

            if ($libro->stock_disponible < 1) {
                return back()->withInput()->with('error', "No quedan ejemplares disponibles de '{$libro->titulo}'.");
            }

            $prestamo = Prestamo::create([
                'id_usuario' => $usuario->id,
                'id_libro' => $libro->id,
                'fecha_prestamo' => Carbon::today()->toDateString(),
                'fecha_devolucion_esperada' => $validated['fecha_devolucion_esperada'],
                'estado' => 'activo',
                'notas' => $validated['notas'] ?? null,
            ]);

            // Descontar stock disponible
            $libro->decrement('stock_disponible');

            return redirect()->route('prestamos.show', $prestamo->id)
                ->with('exito', "Préstamo #{$prestamo->id} registrado correctamente a favor de {$usuario->nombre}.");
        });
    }

    public function show($id)
    {
        $prestamo = Prestamo::with(['libro', 'usuario', 'multas'])->findOrFail($id);

        if (Auth::user()->esEstudiante() && $prestamo->id_usuario !== Auth::id()) {
            abort(403, 'No tienes permiso para ver este préstamo.');
        }

        $tarifaDiaria = (float) Configuracion::get('multa_por_dia', 0.20);
        $diasGracia = (int) Configuracion::get('dias_gracia', 0);
        $diasAtrasoTotal = $prestamo->dias_atraso;
        $diasAtrasoCobrar = max(0, $diasAtrasoTotal - $diasGracia);
        $moraEstimada = $diasAtrasoCobrar * $tarifaDiaria;

        return view('prestamos.show', compact('prestamo', 'diasAtrasoTotal', 'diasAtrasoCobrar', 'tarifaDiaria', 'moraEstimada'));
    }

    public function solicitarDevolucion($id)
    {
        $prestamo = Prestamo::with('libro')->findOrFail($id);
        $user = Auth::user();

        if ($user->esEstudiante() && $prestamo->id_usuario !== $user->id) {
            abort(403, 'No tienes permiso para gestionar este préstamo.');
        }

        if (!in_array($prestamo->estado, ['activo', 'vencido'], true)) {
            return back()->with('error', 'El préstamo no se encuentra activo.');
        }

        $prestamo->update(['solicitud_devolucion' => true]);

        return back()->with('exito', "Has notificado la entrega del libro '{$prestamo->libro->titulo}'. Acércate a mostrador para que el bibliotecario confirme la recepción física.");
    }

    public function devolver(Request $request, $id)
    {
        $prestamo = Prestamo::with('libro')->findOrFail($id);

        if (!in_array($prestamo->estado, ['activo', 'vencido'], true)) {
            return back()->with('error', 'Este préstamo ya fue cerrado anteriormente.');
        }

        $fechaReal = $request->input('fecha_devolucion_real')
            ? Carbon::parse($request->input('fecha_devolucion_real'))
            : Carbon::today();

        return DB::transaction(function () use ($prestamo, $fechaReal) {
            $prestamo->update([
                'fecha_devolucion_real' => $fechaReal->toDateString(),
                'estado' => 'devuelto',
                'solicitud_devolucion' => false,
            ]);

            // Reponer ejemplar sin superar el stock total
            $libro = Libro::where('id', $prestamo->id_libro)->lockForUpdate()->first();
            if ($libro) {
                $nuevoStock = min($libro->stock_disponible + 1, $libro->stock_total);
                $libro->update(['stock_disponible' => $nuevoStock]);
            }

            $mensaje = "Confirmado: El libro '{$prestamo->libro->titulo}' fue devuelto físicamente y se repuso el stock disponible.";
            $fechaEsperada = Carbon::parse($prestamo->fecha_devolucion_esperada);

            if ($fechaReal->gt($fechaEsperada)) {
                $diasMoraTotal = (int) $fechaEsperada->diffInDays($fechaReal);
                $diasGracia = (int) Configuracion::get('dias_gracia', 0);
                $diasMoraCobrar = max(0, $diasMoraTotal - $diasGracia);

                if ($diasMoraCobrar > 0) {
                    $tarifaDiaria = (float) Configuracion::get('multa_por_dia', 0.20);
                    $montoMulta = round($diasMoraCobrar * $tarifaDiaria, 2);

                    Multa::create([
                        'id_prestamo' => $prestamo->id,
                        'id_usuario' => $prestamo->id_usuario,
                        'monto' => $montoMulta,
                        'dias_mora' => $diasMoraCobrar,
                        'tarifa_diaria' => $tarifaDiaria,
                        'estado' => 'pendiente',
                        'fecha_generacion' => Carbon::now(),
                        'motivo' => "Mora de {$diasMoraCobrar} días en la devolución esperada ({$fechaEsperada->format('d/m/Y')}).",
                    ]);

                    $simbolo = config('biblioteca.moneda', 'S/');
                    $mensaje .= " Se ha generado una multa automática de {$simbolo} " . number_format($montoMulta, 2) . " por {$diasMoraCobrar} días de mora.";
                }
            }

            return back()->with('exito', $mensaje);
        });
    }
}