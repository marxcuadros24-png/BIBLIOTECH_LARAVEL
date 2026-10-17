@extends('layouts.guest', ['title' => 'Catálogo Completo de Libros'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header del Catálogo -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold font-heading text-brand-900 tracking-tight">Catálogo General de Libros</h1>
        <p class="text-sm text-cream-900/70 mt-1">Explora nuestro acervo bibliográfico, consulta existencias o reserva ejemplares.</p>
    </div>

    <!-- Barra de Filtros y Búsqueda -->
    <div class="bg-white p-5 rounded-2xl shadow-soft border border-cream-300 mb-8">
        <form action="{{ route('catalogo') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
            
            <!-- Campo de Búsqueda -->
            <div class="md:col-span-5 relative">
                <label class="block text-xs font-bold uppercase tracking-wider text-cream-400 mb-1.5">Búsqueda rápida</label>
                <div class="relative">
                    <svg class="w-5 h-5 text-cream-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="q" value="{{ $busqueda ?? '' }}" placeholder="Título, autor, ISBN o editorial..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800 transition-colors">
                </div>
            </div>

            <!-- Filtro por Categoría -->
            <div class="md:col-span-3">
                <label class="block text-xs font-bold uppercase tracking-wider text-cream-400 mb-1.5">Categoría</label>
                <select name="categoria" class="w-full py-2.5 px-3 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800 transition-colors">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ ($categoriaId ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nombre }} ({{ $cat->libros_count }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro por Disponibilidad -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-cream-400 mb-1.5">Disponibilidad</label>
                <select name="disponibilidad" class="w-full py-2.5 px-3 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800 transition-colors">
                    <option value="">Todos</option>
                    <option value="disponible" {{ ($disponibilidad ?? '') == 'disponible' ? 'selected' : '' }}>Con stock</option>
                    <option value="agotado" {{ ($disponibilidad ?? '') == 'agotado' ? 'selected' : '' }}>Agotados</option>
                </select>
            </div>

            <!-- Botones de Acción -->
            <div class="md:col-span-2 flex items-end gap-2">
                <button type="submit" class="flex-1 py-2.5 px-4 rounded-xl bg-brand-800 text-white font-bold text-sm shadow hover:bg-brand-700 transition-all text-center">
                    Filtrar
                </button>
                @if(!empty($busqueda) || !empty($categoriaId) || !empty($disponibilidad))
                <a href="{{ route('catalogo') }}" class="py-2.5 px-3 rounded-xl bg-cream-200 text-cream-900 text-sm font-semibold hover:bg-cream-300 transition-colors" title="Limpiar filtros">
                    &times;
                </a>
                @endif
            </div>

        </form>
    </div>

    <div x-data="{ gridMode: localStorage.getItem('bibliotech_grid') || '3' }">
        <!-- Barra de Modos de Vista: 3 libros por fila vs 5 libros por fila -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-6 bg-white p-3.5 rounded-2xl border border-cream-300 shadow-soft">
            <div class="text-xs font-bold text-cream-900/80 flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                <span>Catálogo de Libros: <strong>{{ $libros->total() }}</strong> {{ Str::plural('título', $libros->total()) }} encontrados</span>
            </div>

            <div class="flex items-center gap-1.5 self-end sm:self-auto bg-cream-100 p-1 rounded-xl border border-cream-200">
                <span class="text-[11px] font-bold text-cream-400 px-2 uppercase tracking-wider">Diseño:</span>
                
                <!-- Opción 3 libros por fila (Vista Grande) -->
                <button type="button" 
                        @click="gridMode = '3'; localStorage.setItem('bibliotech_grid', '3')" 
                        :class="gridMode === '3' ? 'bg-brand-800 text-white shadow-sm' : 'text-cream-900 hover:bg-cream-200/80'"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span>Vista Grande (3 por fila)</span>
                </button>

                <!-- Opción 5 libros por fila -->
                <button type="button" 
                        @click="gridMode = '5'; localStorage.setItem('bibliotech_grid', '5')" 
                        :class="gridMode === '5' ? 'bg-brand-800 text-white shadow-sm' : 'text-cream-900 hover:bg-cream-200/80'"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    <span>5 por fila</span>
                </button>
            </div>
        </div>

        <!-- Grid de Libros -->
        @if($libros->isEmpty())
            <div class="bg-white rounded-2xl border border-cream-300 p-12 text-center my-8 shadow-soft">
                <div class="w-16 h-16 rounded-full bg-cream-200 text-cream-400 mx-auto flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-cream-900">No se encontraron libros con los criterios seleccionados</h3>
                <p class="text-sm text-cream-400 mt-1">Intenta con otros términos de búsqueda o elimina los filtros aplicados.</p>
                <a href="{{ route('catalogo') }}" class="mt-4 inline-block px-4 py-2 rounded-xl bg-brand-800 text-white text-xs font-bold uppercase tracking-wider hover:bg-brand-700 transition-all">
                    Restablecer Catálogo
                </a>
            </div>
        @else
            <div :class="gridMode === '3' ? 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10' : 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-4 mb-10'">
                @foreach($libros as $libro)
                <div class="bg-white rounded-2xl border border-cream-300 shadow-soft overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                    
                    <div>
                        <!-- Portada y Badges -->
                        <div :class="gridMode === '3' ? 'h-52' : 'h-36'" class="bg-gradient-to-tr from-brand-900 via-brand-800 to-brand-700 p-4 relative flex items-center justify-center text-center overflow-hidden transition-all duration-300">
                            @if($libro->portada_url)
                                <img src="{{ $libro->portada_url }}" alt="{{ $libro->titulo }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-80" onerror="this.style.display='none'">
                            @endif
                            <div class="relative z-10 p-3 bg-brand-950/70 backdrop-blur-xs rounded-xl border border-white/10 max-w-[90%]">
                                <span class="text-accent-400 font-bold text-[11px] uppercase tracking-wider block mb-1">
                                    {{ $libro->categoria->nombre ?? 'General' }}
                                </span>
                                <h3 :class="gridMode === '3' ? 'text-base' : 'text-xs'" class="font-bold text-white line-clamp-2 leading-snug">
                                    {{ $libro->titulo }}
                                </h3>
                            </div>

                            <!-- Notificación en el libro para el estudiante -->
                            @if(Auth::check() && Auth::user()->esEstudiante() && in_array($libro->id, $misLibrosReservados ?? []))
                                <div class="absolute top-2.5 left-2.5 z-20">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500 text-brand-950 shadow-md ring-2 ring-amber-300 animate-pulse">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/></svg>
                                        ¡Reservado!
                                    </span>
                                </div>
                            @elseif(Auth::check() && Auth::user()->esEstudiante() && in_array($libro->id, $misLibrosPrestados ?? []))
                                <div class="absolute top-2.5 left-2.5 z-20">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-600 text-white shadow-md ring-2 ring-blue-300">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                        En posesión
                                    </span>
                                </div>
                            @endif

                            <div class="absolute top-2.5 right-2.5 z-20">
                                @if($libro->stock_disponible > 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500 text-white shadow">
                                        {{ $libro->stock_disponible }} disp.
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-500 text-white shadow">
                                        Agotado
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Información del libro -->
                        <div :class="gridMode === '3' ? 'p-5' : 'p-3.5'">
                            <h4 :class="gridMode === '3' ? 'text-base' : 'text-xs'" class="font-bold text-cream-900 line-clamp-2 group-hover:text-brand-800 transition-colors">
                                {{ $libro->titulo }}
                            </h4>
                            <p class="text-xs text-cream-400 mt-1 font-medium flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span class="truncate">{{ $libro->autor }}</span>
                            </p>

                            <div class="mt-2.5 text-xs text-cream-900/70 space-y-0.5">
                                @if($libro->editorial)
                                <div class="truncate text-[11px]"><span class="text-cream-400">Editorial:</span> {{ $libro->editorial }} ({{ $libro->anio ?? 's/f' }})</div>
                                @endif
                                @if($libro->isbn)
                                <div class="font-mono text-[10px] text-cream-500"><span class="text-cream-400 font-sans">ISBN:</span> {{ $libro->isbn }}</div>
                                @endif
                            </div>

                            @if(Auth::check() && Auth::user()->esEstudiante() && in_array($libro->id, $misLibrosReservados ?? []))
                                <div class="mt-2.5 p-2 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-semibold flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span class="text-[11px]">Reservado · En espera de entrega</span>
                                </div>
                            @elseif($libro->sinopsis)
                                <p x-show="gridMode === '3'" class="text-xs text-cream-900/70 mt-3 line-clamp-2 leading-relaxed bg-cream-100 p-2.5 rounded-lg border border-cream-200">
                                    {{ $libro->sinopsis }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div :class="gridMode === '3' ? 'p-5 pt-0' : 'p-3.5 pt-0'">
                        <div class="pt-2.5 border-t border-cream-200 flex items-center gap-2">
                            @auth
                                @if(Auth::user()->esEstudiante())
                                    @if(in_array($libro->id, $misLibrosReservados ?? []))
                                        <a href="{{ route('reservas.index') }}" class="w-full py-2 px-2.5 rounded-xl bg-amber-100 text-amber-950 font-bold text-xs border border-amber-300 hover:bg-amber-200 transition-all text-center flex items-center justify-center gap-1 shadow-sm">
                                            <svg class="w-3.5 h-3.5 text-amber-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span class="truncate">Ver Reserva</span>
                                        </a>
                                    @elseif(in_array($libro->id, $misLibrosPrestados ?? []))
                                        <a href="{{ route('prestamos.index') }}" class="w-full py-2 px-2.5 rounded-xl bg-blue-50 text-blue-900 font-bold text-xs border border-blue-200 hover:bg-blue-100 transition-all text-center flex items-center justify-center gap-1 shadow-sm">
                                            <svg class="w-3.5 h-3.5 text-blue-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                            <span class="truncate">En Préstamo</span>
                                        </a>
                                    @elseif($libro->stock_disponible > 0)
                                        <form action="{{ route('reservas.store') }}" method="POST" class="w-full">
                                            @csrf
                                            <input type="hidden" name="id_libro" value="{{ $libro->id }}">
                                            <button type="submit" class="w-full py-2 px-2.5 rounded-xl bg-accent-400 text-brand-950 font-bold text-xs shadow hover:bg-accent-300 transition-all flex items-center justify-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                                <span class="truncate">Reservar Libro</span>
                                            </button>
                                        </form>
                                    @else
                                        <button disabled class="w-full py-2 px-2 rounded-xl bg-cream-200 text-cream-400 font-bold text-xs cursor-not-allowed">
                                            No disponible
                                        </button>
                                    @endif
                                @else
                                    <a href="{{ route('libros.show', $libro->id) }}" class="w-full py-2 px-2.5 rounded-xl bg-brand-800 text-white font-bold text-xs shadow hover:bg-brand-700 transition-all text-center flex items-center justify-center gap-1.5">
                                        <span class="truncate">Administrar Ficha</span>
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="w-full py-2 px-2 rounded-xl bg-brand-800 text-white font-bold text-xs shadow hover:bg-brand-700 transition-all text-center truncate">
                                    Iniciar sesión
                                </a>
                            @endauth
                        </div>
                    </div>

                </div>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="mt-8">
                {{ $libros->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
