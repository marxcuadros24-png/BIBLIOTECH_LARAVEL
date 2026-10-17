@extends('layouts.app', ['title' => 'Gestión de Libros'])

@section('content')
<div class="space-y-6">

    <!-- Encabezado y Acción -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-cream-300">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold font-heading text-brand-900 tracking-tight">
                Gestión de Libros
            </h1>
            <p class="text-sm text-cream-900/70 mt-0.5">
                Administración del catálogo, control de existencias e inventario físico.
            </p>
        </div>

        @if(Auth::user()->tienePermiso('libros', 'crear'))
        <a href="{{ route('libros.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-accent-400 text-brand-950 font-bold text-xs uppercase tracking-wider shadow hover:bg-accent-300 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Registrar Nuevo Libro</span>
        </a>
        @endif
    </div>

    <!-- Filtros -->
    <div class="bg-white p-4 rounded-2xl border border-cream-300 shadow-soft">
        <form action="{{ route('libros.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="w-5 h-5 text-cream-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="q" value="{{ $busqueda ?? '' }}" placeholder="Buscar por título, autor, ISBN o editorial..." class="w-full pl-10 pr-4 py-2 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
            </div>

            <div class="sm:w-64">
                <select name="categoria" class="w-full py-2 px-3 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ ($categoriaId ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="px-5 py-2 rounded-xl bg-brand-800 text-white font-bold text-xs uppercase tracking-wider hover:bg-brand-700 transition-colors">
                Buscar
            </button>
            @if(!empty($busqueda) || !empty($categoriaId))
            <a href="{{ route('libros.index') }}" class="px-3 py-2 rounded-xl bg-cream-200 text-cream-900 text-sm font-bold hover:bg-cream-300 flex items-center justify-center">
                &times;
            </a>
            @endif
        </form>
    </div>

    <!-- Tabla de Libros -->
    <div class="bg-white rounded-2xl border border-cream-300 shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-cream-900">
                <thead class="bg-cream-100 text-[11px] font-bold uppercase tracking-wider text-cream-400 border-b border-cream-200">
                    <tr>
                        <th class="px-5 py-3.5">Libro</th>
                        <th class="px-5 py-3.5">Categoría</th>
                        <th class="px-5 py-3.5">ISBN</th>
                        <th class="px-5 py-3.5 text-center">Stock Disp.</th>
                        <th class="px-5 py-3.5 text-center">Stock Total</th>
                        <th class="px-5 py-3.5 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-200">
                    @forelse($libros as $libro)
                    <tr class="hover:bg-cream-50 transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-cream-900 flex items-center gap-2">
                                <a href="{{ route('libros.show', $libro->id) }}" class="hover:text-brand-800 hover:underline">
                                    {{ $libro->titulo }}
                                </a>
                            </div>
                            <div class="text-xs text-cream-400 mt-0.5">
                                {{ $libro->autor }} · <span class="italic">{{ $libro->editorial ?? 'Sin editorial' }} ({{ $libro->anio ?? 's/f' }})</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-brand-50 text-brand-800 border border-brand-200/50">
                                {{ $libro->categoria->nombre ?? 'Sin categoría' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 font-mono text-xs text-cream-900/80">
                            {{ $libro->isbn ?? '-' }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($libro->stock_disponible > 0)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                    {{ $libro->stock_disponible }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                    0
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-center font-bold text-cream-900">
                            {{ $libro->stock_total }}
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('libros.show', $libro->id) }}" class="p-1.5 rounded-lg bg-cream-200 hover:bg-brand-800 hover:text-white text-cream-900 transition-colors" title="Ver ficha">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>

                                @if(Auth::user()->tienePermiso('libros', 'editar'))
                                <a href="{{ route('libros.edit', $libro->id) }}" class="p-1.5 rounded-lg bg-cream-200 hover:bg-accent-400 hover:text-brand-950 text-cream-900 transition-colors" title="Editar libro">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                @endif

                                @if(Auth::user()->tienePermiso('prestamos', 'crear') && $libro->stock_disponible > 0)
                                <a href="{{ route('prestamos.create', ['libro_id' => $libro->id]) }}" class="p-1.5 rounded-lg bg-emerald-100 hover:bg-emerald-600 hover:text-white text-emerald-800 transition-colors" title="Prestar libro">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </a>
                                @endif

                                @if(Auth::user()->tienePermiso('libros', 'eliminar'))
                                <form action="{{ route('libros.destroy', $libro->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este libro?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg bg-red-100 hover:bg-red-600 hover:text-white text-red-700 transition-colors" title="Eliminar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-cream-400">
                            No se encontraron libros que coincidan con la búsqueda.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-cream-200">
            {{ $libros->links() }}
        </div>
    </div>

</div>
@endsection
