@extends('layouts.app', ['title' => 'Gestión de Usuarios'])

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-cream-300">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold font-heading text-brand-900 tracking-tight">
                Gestión de Usuarios y Accesos
            </h1>
            <p class="text-sm text-cream-900/70 mt-0.5">
                Administración de cuentas de administradores, bibliotecarios y estudiantes.
            </p>
        </div>

        @if(Auth::user()->tienePermiso('usuarios', 'crear'))
        <a href="{{ route('usuarios.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-accent-400 text-brand-950 font-bold text-xs uppercase tracking-wider shadow hover:bg-accent-300 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            <span>Crear Nuevo Usuario</span>
        </a>
        @endif
    </div>

    <!-- Filtros -->
    <div class="bg-white p-4 rounded-2xl border border-cream-300 shadow-soft">
        <form action="{{ route('usuarios.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="w-5 h-5 text-cream-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="q" value="{{ $busqueda ?? '' }}" placeholder="Buscar por nombre o correo electrónico..." class="w-full pl-10 pr-4 py-2 rounded-xl border border-cream-300 text-sm focus:border-brand-800">
            </div>

            <div class="sm:w-48">
                <select name="rol" class="w-full py-2 px-3 rounded-xl border border-cream-300 text-sm focus:border-brand-800">
                    <option value="">Todos los roles</option>
                    <option value="administrador" {{ ($rol ?? '') === 'administrador' ? 'selected' : '' }}>Administrador</option>
                    <option value="bibliotecario" {{ ($rol ?? '') === 'bibliotecario' ? 'selected' : '' }}>Bibliotecario</option>
                    <option value="estudiante" {{ ($rol ?? '') === 'estudiante' ? 'selected' : '' }}>Estudiante</option>
                </select>
            </div>

            <div class="sm:w-40">
                <select name="estado" class="w-full py-2 px-3 rounded-xl border border-cream-300 text-sm focus:border-brand-800">
                    <option value="">Todos los estados</option>
                    <option value="activo" {{ ($estado ?? '') === 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="bloqueado" {{ ($estado ?? '') === 'bloqueado' ? 'selected' : '' }}>Bloqueado</option>
                </select>
            </div>

            <button type="submit" class="px-5 py-2 rounded-xl bg-brand-800 text-white font-bold text-xs uppercase tracking-wider hover:bg-brand-700">
                Filtrar
            </button>
        </form>
    </div>

    <!-- Tabla de Usuarios -->
    <div class="bg-white rounded-2xl border border-cream-300 shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-cream-900">
                <thead class="bg-cream-100 text-[11px] font-bold uppercase tracking-wider text-cream-400 border-b border-cream-200">
                    <tr>
                        <th class="px-5 py-3.5">ID</th>
                        <th class="px-5 py-3.5">Usuario</th>
                        <th class="px-5 py-3.5">Rol</th>
                        <th class="px-5 py-3.5">Estado</th>
                        <th class="px-5 py-3.5 text-center">Préstamos</th>
                        <th class="px-5 py-3.5 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-200">
                    @forelse($usuarios as $u)
                    <tr class="hover:bg-cream-50 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-xs font-bold text-brand-800">#{{ $u->id }}</td>
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-cream-900">{{ $u->nombre }}</div>
                            <div class="text-xs text-cream-400">{{ $u->email }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold {{ $u->esAdmin() ? 'bg-purple-100 text-purple-800' : ($u->esBibliotecario() ? 'bg-blue-100 text-blue-800' : 'bg-emerald-100 text-emerald-800') }}">
                                {{ strtoupper($u->rol) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $u->estaActivo() ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $u->estaActivo() ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                {{ ucfirst($u->estado) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-center font-bold text-cream-900">
                            {{ $u->prestamos_count }}
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                
                                <!-- Bloquear / Desbloquear -->
                                @if($u->id !== Auth::id())
                                <form action="{{ route('usuarios.toggle', $u->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $u->estaActivo() ? 'bg-amber-100 text-amber-800 hover:bg-amber-200' : 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' }}" title="{{ $u->estaActivo() ? 'Bloquear acceso' : 'Desbloquear acceso' }}">
                                        {{ $u->estaActivo() ? 'Bloquear' : 'Activar' }}
                                    </button>
                                </form>
                                @endif

                                <!-- Editar -->
                                <a href="{{ route('usuarios.edit', $u->id) }}" class="p-1.5 rounded-lg bg-cream-200 hover:bg-accent-400 hover:text-brand-950 text-cream-900 transition-colors" title="Editar datos">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>

                                <!-- Eliminar -->
                                @if($u->id !== Auth::id())
                                <form action="{{ route('usuarios.destroy', $u->id) }}" method="POST" onsubmit="return confirm('¿Eliminar al usuario {{ $u->nombre }}?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg bg-red-100 hover:bg-red-600 hover:text-white text-red-700 transition-colors" title="Eliminar usuario">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-cream-400">
                            No se encontraron usuarios bajo este criterio.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-cream-200">
            {{ $usuarios->links() }}
        </div>
    </div>

</div>
@endsection
