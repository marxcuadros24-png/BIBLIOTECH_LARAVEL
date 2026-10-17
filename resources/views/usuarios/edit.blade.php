@extends('layouts.app', ['title' => 'Editar Usuario'])

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between pb-4 border-b border-cream-300">
        <div>
            <h1 class="text-2xl font-bold font-heading text-brand-900 tracking-tight">Editar Usuario</h1>
            <p class="text-sm text-cream-900/70 mt-0.5">Modificar información de <strong class="text-brand-800">{{ $usuario->nombre }}</strong></p>
        </div>
        <a href="{{ route('usuarios.index') }}" class="text-xs font-bold uppercase tracking-wider text-cream-400 hover:text-brand-800">
            &larr; Volver
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-cream-300 shadow-soft p-6 sm:p-8">
        <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Nombre Completo *</label>
                <input type="text" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Correo Electrónico *</label>
                <input type="email" name="email" value="{{ old('email', $usuario->email) }}" required class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Nueva Contraseña (Dejar en blanco para mantener la actual)</label>
                <input type="password" name="password" placeholder="••••••••" class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Rol en el Sistema *</label>
                    <select name="rol" required class="w-full px-3 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800">
                        <option value="estudiante" {{ old('rol', $usuario->rol) === 'estudiante' ? 'selected' : '' }}>Estudiante</option>
                        <option value="bibliotecario" {{ old('rol', $usuario->rol) === 'bibliotecario' ? 'selected' : '' }}>Bibliotecario</option>
                        <option value="administrador" {{ old('rol', $usuario->rol) === 'administrador' ? 'selected' : '' }}>Administrador</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Estado *</label>
                    <select name="estado" required class="w-full px-3 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800">
                        <option value="activo" {{ old('estado', $usuario->estado) === 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="bloqueado" {{ old('estado', $usuario->estado) === 'bloqueado' ? 'selected' : '' }}>Bloqueado</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $usuario->telefono) }}" class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Dirección</label>
                    <input type="text" name="direccion" value="{{ old('direccion', $usuario->direccion) }}" class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800">
                </div>
            </div>

            <div class="pt-4 border-t border-cream-200 flex items-center justify-end gap-3">
                <a href="{{ route('usuarios.index') }}" class="px-4 py-2.5 rounded-xl bg-cream-200 text-cream-900 font-bold text-xs uppercase tracking-wider hover:bg-cream-300">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-accent-400 text-brand-950 font-bold text-xs uppercase tracking-wider shadow hover:bg-accent-300">
                    Actualizar Usuario
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
