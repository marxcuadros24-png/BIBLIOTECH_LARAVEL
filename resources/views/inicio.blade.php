@extends('layouts.guest', ['title' => 'Inicio · Biblioteca Digital'])

@section('content')
<!-- Hero Section con Ramillete de Libros a la Derecha -->
<section class="relative bg-gradient-to-br from-brand-900 via-brand-800 to-brand-950 text-white py-12 lg:py-20 overflow-hidden shadow-2xl">
    <!-- Patrón sutil de fondo -->
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#d59b34_1px,transparent_1px)] [background-size:20px_20px]"></div>
    
    <!-- Luces ambientales de fondo -->
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-accent-400/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 right-1/4 w-96 h-96 bg-brand-500/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-8 items-center">
            
            <!-- Columna Izquierda: Información y Búsqueda -->
            <div class="lg:col-span-7 text-center lg:text-left space-y-6">
                
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-accent-400/20 text-accent-300 text-xs font-bold uppercase tracking-wider border border-accent-400/30 backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-accent-400 animate-pulse"></span>
                    Plataforma Oficial de Préstamos Bibliotecarios
                </div>

                <h1 class="text-3xl sm:text-5xl lg:text-5xl font-bold font-heading tracking-tight text-white leading-tight">
                    Descubre, consulta y <br class="hidden sm:inline">
                    <span class="text-accent-400 drop-shadow-sm">solicita libros</span> al instante
                </h1>

                <p class="text-base sm:text-lg text-brand-100 max-w-xl mx-auto lg:mx-0 font-light leading-relaxed">
                    Bienvenido a <strong>BiblioTech</strong>. Gestiona préstamos, consulta la disponibilidad de ejemplares en tiempo real y explora nuestro catálogo académico y literario.
                </p>

                <!-- Buscador Central con Botón Comenzar Ahora -->
                <div class="max-w-xl mx-auto lg:mx-0 bg-white p-2 rounded-2xl shadow-2xl border border-cream-300 flex items-center gap-2">
                    <form action="{{ route('catalogo') }}" method="GET" class="w-full flex items-center gap-2">
                        <div class="relative flex-1">
                            <svg class="w-5 h-5 text-cream-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="q" placeholder="Buscar por título, autor, ISBN o editorial..." class="w-full pl-11 pr-4 py-2.5 bg-transparent text-cream-900 placeholder-cream-400 border-none focus:ring-0 text-sm">
                        </div>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-accent-400 to-accent-500 hover:from-accent-300 hover:to-accent-400 text-brand-950 font-bold text-xs uppercase tracking-wider shadow-lg hover:shadow-glow hover:scale-[1.02] active:scale-[0.98] transition-all shrink-0 flex items-center gap-2">
                            <span>Comenzar Ahora</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </button>
                    </form>
                </div>

                <!-- Métricas Rápidas -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4 text-center lg:text-left border-t border-brand-700/60 max-w-xl mx-auto lg:mx-0">
                    <div>
                        <div class="text-2xl sm:text-3xl font-bold font-heading text-accent-400">{{ $totalLibros }}</div>
                        <div class="text-[11px] text-brand-200 uppercase font-semibold">Títulos</div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-bold font-heading text-white">{{ $totalStock }}</div>
                        <div class="text-[11px] text-brand-200 uppercase font-semibold">Ejemplares</div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-bold font-heading text-emerald-400">{{ $disponibles }}</div>
                        <div class="text-[11px] text-brand-200 uppercase font-semibold">Disponibles</div>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-bold font-heading text-accent-300">{{ $categorias->count() }}</div>
                        <div class="text-[11px] text-brand-200 uppercase font-semibold">Categorías</div>
                    </div>
                </div>

            </div>

            <!-- Columna Derecha: Vitrina 3D Interactiva de Libros Destacados -->
            <div class="lg:col-span-5 flex flex-col items-center justify-center pt-6 lg:pt-0" 
                 x-data="{ 
                    active: 0,
                    total: {{ max($librosRamillete->count(), 1) }},
                    next() { this.active = (this.active + 1) % this.total; },
                    prev() { this.active = (this.active - 1 + this.total) % this.total; }
                 }">
                
                <div class="relative w-full max-w-[430px] h-[410px] sm:h-[440px] flex items-center justify-center perspective-1000">
                    
                    <!-- Resplandor ambiental de fondo -->
                    <div class="absolute w-72 h-72 bg-gradient-to-tr from-accent-400/30 via-brand-500/25 to-accent-300/20 rounded-full blur-3xl animate-pulse-glow pointer-events-none"></div>

                    <!-- Insignia flotante: Disponibilidad en vivo -->
                    <div class="absolute -top-3 -right-2 z-40 glass-pill px-3 py-1.5 rounded-full text-[11px] font-bold text-accent-300 shadow-xl flex items-center gap-1.5 border border-accent-400/30 animate-float-card">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        <span>Catálogo en Tiempo Real</span>
                    </div>

                    <!-- Insignia flotante: Títulos Destacados -->
                    <div class="absolute -bottom-2 -left-2 z-40 glass-pill px-3 py-1.5 rounded-full text-[11px] font-bold text-white/90 shadow-xl flex items-center gap-1.5 border border-white/20">
                        <svg class="w-3.5 h-3.5 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span>Top Ejemplares Recomendados</span>
                    </div>

                    <!-- Tarjetas de Libros en Perspectiva 3D -->
                    @foreach($librosRamillete as $index => $libro)
                    <div class="absolute card-3d-showcase cursor-pointer select-none"
                         :class="{
                             'z-30 scale-100 opacity-100 translate-x-0 translate-y-0 rotate-0 shadow-2xl': active === {{ $index }},
                             'z-20 scale-95 opacity-80 translate-x-10 sm:translate-x-12 translate-y-2 rotate-3 shadow-xl': active === ({{ $index }} - 1 + total) % total,
                             'z-10 scale-90 opacity-50 translate-x-20 sm:translate-x-24 translate-y-5 rotate-6 shadow-md': active === ({{ $index }} - 2 + total) % total,
                             'z-20 scale-95 opacity-80 -translate-x-10 sm:-translate-x-12 translate-y-2 -rotate-3 shadow-xl': active === ({{ $index }} + 1) % total,
                             'z-10 scale-90 opacity-50 -translate-x-20 sm:-translate-x-24 translate-y-5 -rotate-6 shadow-md': active === ({{ $index }} + 2) % total,
                         }"
                         @click="active = {{ $index }}">
                        
                        <div class="w-64 sm:w-72 rounded-3xl bg-gradient-to-b from-brand-900/95 via-brand-950 to-brand-950 p-4 border-2 transition-all duration-300"
                             :class="active === {{ $index }} ? 'border-accent-400 shadow-[0_0_35px_rgba(213,155,52,0.35)]' : 'border-white/10 hover:border-white/30'">
                            
                            <!-- Portada del Libro -->
                            <div class="relative h-48 sm:h-52 rounded-2xl overflow-hidden bg-brand-900 flex items-center justify-center group/card">
                                @if($libro->portada_url)
                                    <img src="{{ $libro->portada_url }}" alt="{{ $libro->titulo }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover/card:scale-105" onerror="this.style.display='none'">
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-brand-950 via-brand-950/30 to-transparent"></div>

                                <!-- Badge de Categoría -->
                                <div class="absolute top-3 left-3 z-10">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-brand-950/80 text-accent-300 border border-accent-400/40 backdrop-blur-xs">
                                        {{ Str::limit($libro->categoria->nombre ?? 'Colección', 14) }}
                                    </span>
                                </div>

                                <!-- Badge de Stock -->
                                <div class="absolute top-3 right-3 z-10">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $libro->stock_disponible > 0 ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white' }} shadow-md backdrop-blur-xs">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $libro->stock_disponible > 0 ? 'bg-white' : 'bg-red-200' }}"></span>
                                        {{ $libro->stock_disponible > 0 ? $libro->stock_disponible . ' disp.' : 'Agotado' }}
                                    </span>
                                </div>

                                <!-- ISBN en pie de portada -->
                                @if($libro->isbn)
                                <div class="absolute bottom-2.5 left-3 z-10 text-[10px] font-mono text-white/80 bg-black/50 px-2 py-0.5 rounded backdrop-blur-xs">
                                    ISBN: {{ $libro->isbn }}
                                </div>
                                @endif
                            </div>

                            <!-- Información del Libro -->
                            <div class="pt-3.5 space-y-1.5">
                                <h4 class="text-sm sm:text-base font-bold text-white line-clamp-1 leading-snug group-hover:text-accent-300 transition-colors">
                                    {{ $libro->titulo }}
                                </h4>
                                <p class="text-xs text-accent-400 font-medium truncate flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span>{{ $libro->autor }}</span>
                                </p>

                                <!-- Enlace y editorial -->
                                <div class="pt-2 flex items-center justify-between border-t border-white/10 text-xs">
                                    <span class="text-[11px] text-brand-200">{{ $libro->editorial ?? 'Editorial oficial' }}</span>
                                    <a href="{{ route('catalogo', ['q' => $libro->titulo]) }}" class="inline-flex items-center gap-1 font-bold text-accent-400 hover:text-accent-300 transition-colors">
                                        <span>Consultar</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endforeach

                </div>

                <!-- Controles interactivos de navegación -->
                <div class="flex items-center gap-4 mt-5 z-30">
                    <button type="button" @click="prev()" class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-white transition-all hover:scale-105 active:scale-95 border border-white/10 shadow-sm" title="Anterior">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>

                    <div class="flex items-center gap-1.5">
                        @foreach($librosRamillete as $index => $libro)
                        <button type="button" @click="active = {{ $index }}" 
                                class="h-2 rounded-full transition-all duration-300"
                                :class="active === {{ $index }} ? 'w-6 bg-accent-400 shadow-glow' : 'w-2 bg-white/30 hover:bg-white/60'">
                        </button>
                        @endforeach
                    </div>

                    <button type="button" @click="next()" class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-white transition-all hover:scale-105 active:scale-95 border border-white/10 shadow-sm" title="Siguiente">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

            </div>

        </div>
    </div>
</section>

<!-- Categorías Populares -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold font-heading text-brand-900">Categorías de la Biblioteca</h2>
            <p class="text-sm text-cream-900/70">Navega por las colecciones y materias especializadas</p>
        </div>
        <a href="{{ route('catalogo') }}" class="text-sm font-semibold text-brand-800 hover:text-accent-600 flex items-center gap-1">
            Ver todas &rarr;
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        @foreach($categorias as $cat)
        <a href="{{ route('catalogo', ['categoria' => $cat->id]) }}" class="p-4 rounded-2xl bg-white border border-cream-300 shadow-soft hover:shadow-md hover:border-brand-700/40 hover:-translate-y-0.5 transition-all group text-center">
            <div class="w-10 h-10 mx-auto rounded-xl bg-brand-50 text-brand-800 flex items-center justify-center font-bold text-sm mb-2 group-hover:bg-brand-800 group-hover:text-white transition-colors">
                {{ strtoupper(substr($cat->nombre, 0, 2)) }}
            </div>
            <div class="text-sm font-bold text-cream-900 group-hover:text-brand-800 transition-colors line-clamp-1">
                {{ $cat->nombre }}
            </div>
            <div class="text-xs text-cream-400 mt-0.5 font-medium">
                {{ $cat->libros_count }} {{ Str::plural('libro', $cat->libros_count) }}
            </div>
        </a>
        @endforeach
    </div>
</section>

<!-- Catálogo Destacado -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mb-16">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold font-heading text-brand-900">Novedades y Libros Recientes</h2>
            <p class="text-sm text-cream-900/70">Títulos incorporados recientemente al fondo bibliográfico</p>
        </div>
        <a href="{{ route('catalogo') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-brand-800 text-white font-semibold text-sm hover:bg-brand-700 shadow-sm transition-all">
            <span>Explorar Todo el Catálogo</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($librosDestacados as $libro)
        <div class="bg-white rounded-2xl border border-cream-300 shadow-soft overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
            
            <div>
                <!-- Portada / Cabecera visual -->
                <div class="h-44 bg-gradient-to-tr from-brand-900 via-brand-800 to-brand-700 p-4 relative flex items-center justify-center text-center overflow-hidden">
                    @if($libro->portada_url)
                        <img src="{{ $libro->portada_url }}" alt="{{ $libro->titulo }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-80" onerror="this.style.display='none'">
                    @endif
                    <div class="relative z-10 p-3 bg-brand-950/70 backdrop-blur-xs rounded-xl border border-white/10 max-w-[90%]">
                        <span class="text-accent-400 font-bold text-xs uppercase tracking-wider block mb-1">
                            {{ $libro->categoria->nombre ?? 'General' }}
                        </span>
                        <h3 class="text-sm font-bold text-white line-clamp-2 leading-snug">
                            {{ $libro->titulo }}
                        </h3>
                    </div>

                    <!-- Badge de disponibilidad -->
                    <div class="absolute top-3 right-3 z-20">
                        @if($libro->stock_disponible > 0)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500 text-white shadow">
                                <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                {{ $libro->stock_disponible }} disp.
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-500 text-white shadow">
                                Agotado
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Detalles del libro -->
                <div class="p-5">
                    <h4 class="font-bold text-base text-cream-900 line-clamp-1 group-hover:text-brand-800 transition-colors">
                        {{ $libro->titulo }}
                    </h4>
                    <p class="text-xs text-cream-400 mt-1 font-medium flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        {{ $libro->autor }}
                    </p>

                    @if($libro->sinopsis)
                    <p class="text-xs text-cream-900/70 mt-3 line-clamp-2 leading-relaxed">
                        {{ $libro->sinopsis }}
                    </p>
                    @endif
                </div>
            </div>

            <div class="p-5 pt-0">
                <div class="pt-3 border-t border-cream-200 flex items-center justify-between">
                    <span class="text-xs text-cream-400 font-mono">
                        {{ $libro->isbn ?? 'S/N ISBN' }}
                    </span>

                    <a href="{{ route('catalogo', ['q' => $libro->titulo]) }}" class="inline-flex items-center gap-1 text-xs font-bold text-brand-800 hover:text-accent-600 transition-colors">
                        Ver ficha
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

        </div>
        @endforeach
    </div>
</section>
@endsection