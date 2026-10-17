<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Libro;
use App\Models\Prestamo;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->check() && auth()->user()->esEstudiante()) {
            return redirect()->route('catalogo');
        }

        $busqueda = $request->input('q');
        $categoriaId = $request->input('categoria');

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

        $libros = $query->orderBy('titulo', 'asc')->paginate(15)->withQueryString();
        $categorias = Categoria::all();

        return view('libros.index', compact('libros', 'categorias', 'busqueda', 'categoriaId'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nombre', 'asc')->get();
        return view('libros.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'autor' => ['required', 'string', 'max:180'],
            'isbn' => ['nullable', 'string', 'max:20', 'unique:libros,isbn'],
            'id_categoria' => ['nullable', 'exists:categorias,id'],
            'editorial' => ['nullable', 'string', 'max:150'],
            'anio' => ['nullable', 'integer', 'min:1000', 'max:' . (date('Y') + 1)],
            'portada_url' => ['nullable', 'url', 'max:500'],
            'sinopsis' => ['nullable', 'string'],
            'stock_total' => ['required', 'integer', 'min:0'],
        ], [
            'titulo.required' => 'El título del libro es obligatorio.',
            'autor.required' => 'El autor del libro es obligatorio.',
            'isbn.unique' => 'Este código ISBN ya está registrado para otro libro.',
            'stock_total.required' => 'Debe indicar la cantidad de stock total.',
            'stock_total.min' => 'El stock no puede ser negativo.',
        ]);

        $validated['stock_disponible'] = $validated['stock_total'];

        $libro = Libro::create($validated);

        return redirect()->route('libros.show', $libro->id)
            ->with('exito', "El libro '{$libro->titulo}' fue registrado con éxito.");
    }

    public function show($id)
    {
        $libro = Libro::with(['categoria', 'prestamos' => function ($q) {
            $q->with('usuario')->orderBy('id', 'desc')->take(5);
        }])->findOrFail($id);

        $prestamosActivos = Prestamo::where('id_libro', $libro->id)
            ->whereIn('estado', ['activo', 'vencido'])
            ->with('usuario')
            ->get();

        $miReserva = null;
        $miPrestamo = null;
        if (auth()->check() && auth()->user()->esEstudiante()) {
            $miReserva = \App\Models\Reserva::where('id_usuario', auth()->id())
                ->where('id_libro', $libro->id)
                ->whereIn('estado', ['pendiente', 'confirmada'])
                ->first();

            $miPrestamo = Prestamo::where('id_usuario', auth()->id())
                ->where('id_libro', $libro->id)
                ->whereIn('estado', ['activo', 'vencido'])
                ->first();
        }

        return view('libros.show', compact('libro', 'prestamosActivos', 'miReserva', 'miPrestamo'));
    }

    public function edit($id)
    {
        $libro = Libro::findOrFail($id);
        $categorias = Categoria::orderBy('nombre', 'asc')->get();
        return view('libros.edit', compact('libro', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $libro = Libro::findOrFail($id);

        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'autor' => ['required', 'string', 'max:180'],
            'isbn' => ['nullable', 'string', 'max:20', 'unique:libros,isbn,' . $libro->id],
            'id_categoria' => ['nullable', 'exists:categorias,id'],
            'editorial' => ['nullable', 'string', 'max:150'],
            'anio' => ['nullable', 'integer', 'min:1000', 'max:' . (date('Y') + 1)],
            'portada_url' => ['nullable', 'url', 'max:500'],
            'sinopsis' => ['nullable', 'string'],
            'stock_total' => ['required', 'integer', 'min:0'],
        ]);

        $diferencia = $validated['stock_total'] - $libro->stock_total;
        $nuevoDisponible = max(0, $libro->stock_disponible + $diferencia);
        $nuevoDisponible = min($nuevoDisponible, $validated['stock_total']);

        $validated['stock_disponible'] = $nuevoDisponible;
        $libro->update($validated);

        return redirect()->route('libros.show', $libro->id)
            ->with('exito', "El libro '{$libro->titulo}' fue actualizado correctamente.");
    }

    public function destroy($id)
    {
        $libro = Libro::findOrFail($id);

        $tienePrestamosActivos = Prestamo::where('id_libro', $libro->id)
            ->whereIn('estado', ['activo', 'vencido'])
            ->exists();

        if ($tienePrestamosActivos) {
            return back()->with('error', "No se puede eliminar el libro '{$libro->titulo}' porque tiene préstamos en curso.");
        }

        $titulo = $libro->titulo;
        $libro->delete();

        return redirect()->route('libros.index')
            ->with('exito', "El libro '{$titulo}' ha sido eliminado del sistema.");
    }

    public function apiIndex(Request $request)
    {
        $busqueda = $request->input('q');
        $libros = Libro::select('id', 'titulo', 'autor', 'isbn', 'stock_disponible')
            ->when($busqueda, function ($q) use ($busqueda) {
                $q->where('titulo', 'LIKE', "%{$busqueda}%")
                  ->orWhere('autor', 'LIKE', "%{$busqueda}%");
            })
            ->limit(20)
            ->get();

        return response()->json($libros);
    }
}
