<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Libro;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        $busqueda = $request->input('q');
        $categoriaId = $request->input('categoria');
        $disponibilidad = $request->input('disponibilidad');

        $query = Libro::with('categoria');

        if (!empty($busqueda)) {
            $query->where(function ($q) use ($busqueda) {
                $q->where('titulo', 'LIKE', "%{$busqueda}%")
                  ->orWhere('autor', 'LIKE', "%{$busqueda}%")
                  ->orWhere('isbn', 'LIKE', "%{$busqueda}%")
                  ->orWhere('editorial', 'LIKE', "%{$busqueda}%");
            });
        }

        if (!empty($categoriaId)) {
            $query->where('id_categoria', $categoriaId);
        }

        if ($disponibilidad === 'disponible') {
            $query->where('stock_disponible', '>', 0);
        } elseif ($disponibilidad === 'agotado') {
            $query->where('stock_disponible', '<=', 0);
        }

        $libros = $query->orderBy('titulo', 'asc')->paginate(15)->withQueryString();
        $categorias = Categoria::withCount('libros')->get();

        $misLibrosReservados = [];
        $misLibrosPrestados = [];
        if (auth()->check() && auth()->user()->esEstudiante()) {
            $misLibrosReservados = \App\Models\Reserva::where('id_usuario', auth()->id())
                ->whereIn('estado', ['pendiente', 'confirmada'])
                ->pluck('id_libro')
                ->toArray();

            $misLibrosPrestados = \App\Models\Prestamo::where('id_usuario', auth()->id())
                ->whereIn('estado', ['activo', 'vencido'])
                ->pluck('id_libro')
                ->toArray();
        }

        return view('catalogo.index', compact('libros', 'categorias', 'busqueda', 'categoriaId', 'disponibilidad', 'misLibrosReservados', 'misLibrosPrestados'));
    }
}
