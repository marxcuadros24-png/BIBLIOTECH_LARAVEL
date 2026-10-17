<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Libro;
use App\Models\Prestamo;

class InicioController extends Controller
{
    public function index()
    {
        $totalLibros = Libro::count();
        $totalStock = Libro::sum('stock_total');
        $disponibles = Libro::where('stock_disponible', '>', 0)->count();
        $categorias = Categoria::withCount('libros')->get();

        $librosDestacados = Libro::with('categoria')
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        // 5 libros para el ramillete de rosas animado
        $librosRamillete = Libro::with('categoria')
            ->whereNotNull('portada_url')
            ->take(5)
            ->get();

        if ($librosRamillete->count() < 5) {
            $librosRamillete = Libro::with('categoria')->take(5)->get();
        }

        return view('inicio', compact(
            'totalLibros',
            'totalStock',
            'disponibles',
            'categorias',
            'librosDestacados',
            'librosRamillete'
        ));
    }
}