<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Libro;
use App\Models\Multa;
use App\Models\Prestamo;
use App\Models\Reserva;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Sincronizar préstamos vencidos automáticamente
        Prestamo::sincronizarVencidos();

        $user = Auth::user();

        if ($user->esEstudiante()) {
            // Panel enfocado al estudiante
            $misPrestamosActivos = Prestamo::with('libro')
                ->where('id_usuario', $user->id)
                ->whereIn('estado', ['activo', 'vencido'])
                ->orderBy('fecha_devolucion_esperada', 'asc')
                ->get();

            $misMultasPendientes = Multa::with('prestamo.libro')
                ->where('id_usuario', $user->id)
                ->where('estado', 'pendiente')
                ->get();

            $misReservas = Reserva::with('libro')
                ->where('id_usuario', $user->id)
                ->whereIn('estado', ['pendiente', 'confirmada'])
                ->orderBy('fecha_reserva', 'desc')
                ->get();

            $historialPrestamos = Prestamo::with('libro')
                ->where('id_usuario', $user->id)
                ->where('estado', 'devuelto')
                ->orderBy('fecha_devolucion_real', 'desc')
                ->take(5)
                ->get();

            return view('dashboard.estudiante', compact(
                'misPrestamosActivos',
                'misMultasPendientes',
                'misReservas',
                'historialPrestamos'
            ));
        }

        // Panel para Administrador y Bibliotecario
        $totalLibros = Libro::count();
        $stockDisponible = Libro::sum('stock_disponible');
        $stockTotal = Libro::sum('stock_total');

        $prestamosActivos = Prestamo::where('estado', 'activo')->count();
        $prestamosVencidos = Prestamo::where('estado', 'vencido')->count();

        $multasPendientesTotal = Multa::where('estado', 'pendiente')->sum('monto');
        $multasPendientesCount = Multa::where('estado', 'pendiente')->count();

        $totalUsuarios = User::where('estado', 'activo')->count();
        $reservasPendientes = Reserva::where('estado', 'pendiente')->count();

        // Alerta de préstamos que vencen pronto (en los próximos 3 días)
        $hoy = Carbon::today();
        $limiteAlerta = Carbon::today()->addDays(config('biblioteca.dias_alerta_mora', 3));

        $prestamosPorVencer = Prestamo::with(['libro', 'usuario'])
            ->where('estado', 'activo')
            ->whereBetween('fecha_devolucion_esperada', [$hoy->toDateString(), $limiteAlerta->toDateString()])
            ->orderBy('fecha_devolucion_esperada', 'asc')
            ->get();

        // Préstamos recientes
        $prestamosRecientes = Prestamo::with(['libro', 'usuario'])
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();

        return view('dashboard.index', compact(
            'totalLibros',
            'stockDisponible',
            'stockTotal',
            'prestamosActivos',
            'prestamosVencidos',
            'multasPendientesTotal',
            'multasPendientesCount',
            'totalUsuarios',
            'reservasPendientes',
            'prestamosPorVencer',
            'prestamosRecientes'
        ));
    }

    public function reporte()
    {
        // Solo administradores
        if (!Auth::user()->esAdmin()) {
            abort(403, 'Solo el administrador puede acceder al reporte financiero y de auditoría.');
        }

        $recaudadoMultas = Multa::where('estado', 'pagada')->sum('monto');
        $pendienteMultas = Multa::where('estado', 'pendiente')->sum('monto');
        $condonadoMultas = Multa::where('estado', 'condonada')->sum('monto');

        $librosMasPrestados = DB::table('prestamos')
            ->join('libros', 'libros.id', '=', 'prestamos.id_libro')
            ->select('libros.id', 'libros.titulo', 'libros.autor', DB::raw('COUNT(prestamos.id) as total_prestamos'))
            ->groupBy('libros.id', 'libros.titulo', 'libros.autor')
            ->orderByDesc('total_prestamos')
            ->limit(8)
            ->get();

        $usuariosMasActivos = DB::table('prestamos')
            ->join('usuarios', 'usuarios.id', '=', 'prestamos.id_usuario')
            ->select('usuarios.id', 'usuarios.nombre', 'usuarios.email', 'usuarios.rol', DB::raw('COUNT(prestamos.id) as total_prestamos'))
            ->groupBy('usuarios.id', 'usuarios.nombre', 'usuarios.email', 'usuarios.rol')
            ->orderByDesc('total_prestamos')
            ->limit(8)
            ->get();

        $prestamosPorEstado = DB::table('prestamos')
            ->select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        return view('dashboard.reporte', compact(
            'recaudadoMultas',
            'pendienteMultas',
            'condonadoMultas',
            'librosMasPrestados',
            'usuariosMasActivos',
            'prestamosPorEstado'
        ));
    }
}
