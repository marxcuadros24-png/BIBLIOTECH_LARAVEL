<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $rol = $request->input('rol');
        $estado = $request->input('estado');
        $busqueda = $request->input('q');

        $query = User::withCount('prestamos');

        if (!empty($busqueda)) {
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'LIKE', "%{$busqueda}%")
                  ->orWhere('email', 'LIKE', "%{$busqueda}%");
            });
        }

        if (!empty($rol)) {
            $query->where('rol', $rol);
        }

        if (!empty($estado)) {
            $query->where('estado', $estado);
        }

        $usuarios = $query->orderBy('nombre', 'asc')->paginate(15)->withQueryString();

        return view('usuarios.index', compact('usuarios', 'rol', 'estado', 'busqueda'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:180', 'unique:usuarios,email'],
            'password' => ['required', 'string', 'min:6'],
            'rol' => ['required', 'in:administrador,bibliotecario,estudiante'],
            'estado' => ['required', 'in:activo,bloqueado'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'direccion' => ['nullable', 'string', 'max:255'],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'rol.in' => 'El rol seleccionado no es válido.',
        ]);

        $user = User::create([
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
            'password_hash' => Hash::make($validated['password']),
            'rol' => $validated['rol'],
            'estado' => $validated['estado'],
            'telefono' => $validated['telefono'] ?? null,
            'direccion' => $validated['direccion'] ?? null,
        ]);

        return redirect()->route('usuarios.index')
            ->with('exito', "Usuario '{$user->nombre}' creado exitosamente.");
    }

    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:180', 'unique:usuarios,email,' . $usuario->id],
            'password' => ['nullable', 'string', 'min:6'],
            'rol' => ['required', 'in:administrador,bibliotecario,estudiante'],
            'estado' => ['required', 'in:activo,bloqueado'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'direccion' => ['nullable', 'string', 'max:255'],
        ]);

        // REGLA: No quitar rol de admin o bloquear al último administrador activo
        if ($usuario->esAdmin() && ($validated['rol'] !== 'administrador' || $validated['estado'] !== 'activo')) {
            $totalAdminsActivos = User::where('rol', 'administrador')->where('estado', 'activo')->count();
            if ($totalAdminsActivos <= 1) {
                return back()->withInput()->with('error', 'No se puede modificar ni bloquear al último administrador activo del sistema.');
            }
        }

        $datosActualizar = [
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
            'rol' => $validated['rol'],
            'estado' => $validated['estado'],
            'telefono' => $validated['telefono'] ?? null,
            'direccion' => $validated['direccion'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $datosActualizar['password_hash'] = Hash::make($validated['password']);
        }

        $usuario->update($datosActualizar);

        return redirect()->route('usuarios.index')
            ->with('exito', "Usuario '{$usuario->nombre}' actualizado con éxito.");
    }

    public function destroy($id)
    {
        $usuario = User::findOrFail($id);

        // REGLA 1: No eliminar al usuario autenticado
        if ($usuario->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminar la cuenta con la que has iniciado sesión actualmente.');
        }

        // REGLA 2: No eliminar al último administrador activo
        if ($usuario->esAdmin()) {
            $totalAdminsActivos = User::where('rol', 'administrador')->where('estado', 'activo')->count();
            if ($totalAdminsActivos <= 1) {
                return back()->with('error', 'No se puede eliminar al único administrador activo del sistema.');
            }
        }

        // REGLA 3: No eliminar usuario con préstamos registrados
        $tienePrestamos = Prestamo::where('id_usuario', $usuario->id)->exists();
        if ($tienePrestamos) {
            return back()->with('error', "No se puede eliminar al usuario '{$usuario->nombre}' porque tiene préstamos en su historial. Puedes bloquearlo en su lugar.");
        }

        $nombre = $usuario->nombre;
        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('exito', "Usuario '{$nombre}' eliminado satisfactoriamente.");
    }

    public function toggleEstado($id)
    {
        $usuario = User::findOrFail($id);

        if ($usuario->id === Auth::id()) {
            return back()->with('error', 'No puedes bloquear tu propia cuenta en uso.');
        }

        if ($usuario->esAdmin() && $usuario->estado === 'activo') {
            $totalAdminsActivos = User::where('rol', 'administrador')->where('estado', 'activo')->count();
            if ($totalAdminsActivos <= 1) {
                return back()->with('error', 'No se puede bloquear al último administrador activo del sistema.');
            }
        }

        $nuevoEstado = $usuario->estado === 'activo' ? 'bloqueado' : 'activo';
        $usuario->update(['estado' => $nuevoEstado]);

        $accion = $nuevoEstado === 'activo' ? 'desbloqueado' : 'bloqueado';
        return back()->with('exito', "El usuario '{$usuario->nombre}' ha sido {$accion}.");
    }
}
