<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::withCount('libros')->orderBy('nombre', 'asc')->get();
        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('categorias.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'unique:categorias,nombre'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.unique' => 'Ya existe una categoría con este nombre.',
        ]);

        Categoria::create($validated);

        return redirect()->route('categorias.index')
            ->with('exito', "Categoría '{$validated['nombre']}' creada exitosamente.");
    }

    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'unique:categorias,nombre,' . $categoria->id],
            'descripcion' => ['nullable', 'string', 'max:1000'],
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.unique' => 'Ya existe una categoría con este nombre.',
        ]);

        $categoria->update($validated);

        return redirect()->route('categorias.index')
            ->with('exito', "Categoría '{$categoria->nombre}' actualizada exitosamente.");
    }

    public function destroy($id)
    {
        $categoria = Categoria::withCount('libros')->findOrFail($id);

        if ($categoria->libros_count > 0) {
            return back()->with('error', "No se puede eliminar la categoría '{$categoria->nombre}' porque tiene {$categoria->libros_count} libros asignados. Reasigne o elimine los libros primero.");
        }

        $nombre = $categoria->nombre;
        $categoria->delete();

        return redirect()->route('categorias.index')
            ->with('exito', "La categoría '{$nombre}' ha sido eliminada.");
    }
}
