<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\MultaController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

// ---------------------------------------------------------------------------
// Rutas Públicas
// ---------------------------------------------------------------------------
Route::get('/', [InicioController::class, 'index'])->name('inicio');
Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo');

// ---------------------------------------------------------------------------
// Autenticación (Invitados)
// ---------------------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register'])->name('register.post');
});

// ---------------------------------------------------------------------------
// Rutas Autenticadas
// ---------------------------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Panel principal
    Route::get('/panel', [DashboardController::class, 'index'])->name('dashboard');

    // Reporte financiero y de auditoría (solo administrador)
    Route::get('/reporte', [DashboardController::class, 'reporte'])
        ->middleware('rol:administrador')
        ->name('dashboard.reporte');

    // Configuración del Sistema (solo administrador)
    Route::get('/configuracion', [ConfiguracionController::class, 'index'])->middleware('rol:administrador')->name('configuracion.index');
    Route::get('/admin/configuracion', [ConfiguracionController::class, 'index'])->middleware('rol:administrador');
    Route::post('/configuracion', [ConfiguracionController::class, 'update'])->middleware('rol:administrador')->name('configuracion.update');

    // Libros
    Route::get('/libros', [LibroController::class, 'index'])->middleware('permiso:libros,ver')->name('libros.index');
    Route::get('/libros/crear', [LibroController::class, 'create'])->middleware('permiso:libros,crear')->name('libros.create');
    Route::post('/libros', [LibroController::class, 'store'])->middleware('permiso:libros,crear')->name('libros.store');
    Route::get('/libros/{id}', [LibroController::class, 'show'])->middleware('permiso:libros,ver')->name('libros.show');
    Route::get('/libros/{id}/editar', [LibroController::class, 'edit'])->middleware('permiso:libros,editar')->name('libros.edit');
    Route::match(['post', 'put'], '/libros/{id}', [LibroController::class, 'update'])->middleware('permiso:libros,editar')->name('libros.update');
    Route::match(['post', 'delete'], '/libros/{id}/eliminar', [LibroController::class, 'destroy'])->middleware('permiso:libros,eliminar')->name('libros.destroy');

    // Categorías
    Route::get('/categorias', [CategoriaController::class, 'index'])->middleware('permiso:categorias,ver')->name('categorias.index');
    Route::get('/categorias/crear', [CategoriaController::class, 'create'])->middleware('permiso:categorias,crear')->name('categorias.create');
    Route::post('/categorias', [CategoriaController::class, 'store'])->middleware('permiso:categorias,crear')->name('categorias.store');
    Route::get('/categorias/{id}/editar', [CategoriaController::class, 'edit'])->middleware('permiso:categorias,editar')->name('categorias.edit');
    Route::match(['post', 'put'], '/categorias/{id}', [CategoriaController::class, 'update'])->middleware('permiso:categorias,editar')->name('categorias.update');
    Route::match(['post', 'delete'], '/categorias/{id}/eliminar', [CategoriaController::class, 'destroy'])->middleware('permiso:categorias,eliminar')->name('categorias.destroy');

    // Préstamos
    Route::get('/prestamos', [PrestamoController::class, 'index'])->name('prestamos.index');
    Route::get('/prestamos/crear', [PrestamoController::class, 'create'])->middleware('permiso:prestamos,crear')->name('prestamos.create');
    Route::post('/prestamos', [PrestamoController::class, 'store'])->middleware('permiso:prestamos,crear')->name('prestamos.store');
    Route::get('/prestamos/{id}', [PrestamoController::class, 'show'])->name('prestamos.show');
    Route::post('/prestamos/{id}/solicitar-devolucion', [PrestamoController::class, 'solicitarDevolucion'])->name('prestamos.solicitarDevolucion');
    Route::post('/prestamos/{id}/devolver', [PrestamoController::class, 'devolver'])->middleware('permiso:prestamos,devolver')->name('prestamos.devolver');

    // Multas
    Route::get('/multas', [MultaController::class, 'index'])->name('multas.index');
    Route::post('/multas/{id}/pagar', [MultaController::class, 'pagar'])->middleware('permiso:multas,pagar')->name('multas.pagar');
    Route::post('/multas/{id}/condonar', [MultaController::class, 'condonar'])->middleware('permiso:multas,condonar')->name('multas.condonar');

    // Usuarios (solo administrador)
    Route::get('/usuarios', [UsuarioController::class, 'index'])->middleware('permiso:usuarios,ver')->name('usuarios.index');
    Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->middleware('permiso:usuarios,crear')->name('usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->middleware('permiso:usuarios,crear')->name('usuarios.store');
    Route::get('/usuarios/{id}/editar', [UsuarioController::class, 'edit'])->middleware('permiso:usuarios,editar')->name('usuarios.edit');
    Route::match(['post', 'put'], '/usuarios/{id}', [UsuarioController::class, 'update'])->middleware('permiso:usuarios,editar')->name('usuarios.update');
    Route::post('/usuarios/{id}/toggle-estado', [UsuarioController::class, 'toggleEstado'])->middleware('permiso:usuarios,editar')->name('usuarios.toggle');
    Route::match(['post', 'delete'], '/usuarios/{id}/eliminar', [UsuarioController::class, 'destroy'])->middleware('permiso:usuarios,eliminar')->name('usuarios.destroy');

    // Reservas
    Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas.index');
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
    Route::post('/reservas/{id}/confirmar', [ReservaController::class, 'confirmar'])->middleware('rol:administrador,bibliotecario')->name('reservas.confirmar');
    Route::post('/reservas/{id}/devolver', [ReservaController::class, 'devolver'])->middleware('rol:administrador,bibliotecario')->name('reservas.devolver');
    Route::post('/reservas/{id}/cancelar', [ReservaController::class, 'cancelar'])->name('reservas.cancelar');

    // Endpoint API para autocompletar libros
    Route::get('/api/libros', [LibroController::class, 'apiIndex'])->middleware('permiso:libros,ver')->name('api.libros');
});