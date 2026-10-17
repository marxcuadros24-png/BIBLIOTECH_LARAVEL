@extends('layouts.app', ['title' => 'Categorías'])

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-cream-300">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold font-heading text-brand-900 tracking-tight">
                Categorías Temáticas
            </h1>
            <p class="text-sm text-cream-900/70 mt-0.5">
                Clasificación del acervo bibliográfico por materias y disciplinas.
            </p>
        </div>

        @if(Auth::user()->tienePermiso('categorias', 'crear'))
        <a href="{{ route('categorias.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-accent-400 text-brand-950 font-bold text-xs uppercase tracking-wider shadow hover:bg-accent-300 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Nueva Categoría</span>
        </a>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($categorias as $cat)
        <div class="bg-white rounded-2xl border border-cream-300 shadow-soft p-5 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div>
                <div class="flex items-start justify-between gap-2 mb-2">
                    <h3 class="font-bold text-base text-brand-900 line-clamp-1">{{ $cat->nombre }}</h3>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-brand-50 text-brand-800 shrink-0 border border-brand-200">
                        {{ $cat->libros_count }} {{ Str::plural('libro', $cat->libros_count) }}
                    </span>
                </div>
                <p class="text-xs text-cream-900/70 line-clamp-3 leading-relaxed">
                    {{ $cat->descripcion ?: 'Sin descripción registrada para esta categoría.' }}
                </p>
            </div>

            <div class="mt-4 pt-4 border-t border-cream-200 flex items-center justify-between text-xs">
                <a href="{{ route('catalogo', ['categoria' => $cat->id]) }}" target="_blank" class="font-bold text-brand-800 hover:underline flex items-center gap-1">
                    Ver en catálogo &rarr;
                </a>

                <div class="flex items-center gap-2">
                    @if(Auth::user()->tienePermiso('categorias', 'editar'))
                    <a href="{{ route('categorias.edit', $cat->id) }}" class="p-1.5 rounded-lg bg-cream-200 hover:bg-accent-400 text-cream-900 hover:text-brand-950 transition-colors" title="Editar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                    @endif

                    @if(Auth::user()->tienePermiso('categorias', 'eliminar'))
                    <form action="{{ route('categorias.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('¿Eliminar la categoría {{ $cat->nombre }}?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 rounded-lg bg-red-100 hover:bg-red-600 hover:text-white text-red-700 transition-colors" title="Eliminar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-2xl border border-cream-300 p-8 text-center text-cream-400">
            No existen categorías registradas aún.
        </div>
        @endforelse
    </div>

</div>
@endsection
