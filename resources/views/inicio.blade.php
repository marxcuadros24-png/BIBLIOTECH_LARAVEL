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

                <!-- Buscador Central -->
                <div class="max-w-xl mx-auto lg:mx-0 bg-white p-2 rounded-2xl shadow-2xl border border-cream-300 flex items-center gap-2">
                    <form action="{{ route('catalogo') }}" method="GET" class="w-full flex items-center gap-2">
                        <div class="relative flex-1">
                            <svg class="w-5 h-5 text-cream-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="q" placeholder="Buscar por título, autor, ISBN o editorial..." class="w-full pl-11 pr-4 py-2.5 bg-transparent text-cream-900 placeholder-cream-400 border-none focus:ring-0 text-sm">
                        </div>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-accent-400 text-brand-950 font-bold text-xs uppercase tracking-wider shadow hover:bg-accent-300 transition-all shrink-0 flex items-center gap-1.5">
                            <span>Buscar</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
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

            <!-- Columna Derecha: Ramillete de Rosas de Libros Animado -->
            <div class="lg:col-span-5 flex flex-col items-center justify-center pt-6 lg:pt-0">
                
                <div class="relative w-full max-w-[420px] h-[380px] sm:h-[420px] flex items-end justify-center group/bouquet">
                    
                    <!-- Resplandor floral detrás del ramillete -->
                    <div class="absolute inset-0 m-auto w-64 h-64 bg-accent-400/20 rounded-full blur-2xl group-hover/bouquet:bg-accent-400/35 transition-all duration-700"></div>

                    <!-- Contenedor del Ramillete con Animación de balanceo -->
                    <div class="relative w-full h-full flex items-end justify-center animate-bouquet">
                        
                        @php
                            $petals = ['bouquet-petal-1', 'bouquet-petal-2', 'bouquet-petal-3', 'bouquet-petal-4', 'bouquet-petal-5'];
                        @endphp

                        @foreach($librosRamillete as $index => $libro)
                        @php
                            $petalClass = $petals[$index] ?? 'bouquet-petal-3';
                        @endphp
                        
                        <!-- Tarjeta Pétalo del Ramillete -->
                        <div class="absolute bottom-10 bouquet-card {{ $petalClass }} cursor-pointer">
                            <a href="{{ route('catalogo', ['q' => $libro->titulo]) }}" class="block w-40 sm:w-44 h-56 sm:h-60 rounded-2xl bg-gradient-to-tr from-brand-950 via-brand-900 to-brand-800 p-2.5 shadow-2xl border-2 border-accent-400/40 relative overflow-hidden group/card text-left">
                                
                                <!-- Portada de Libro -->
                                @if($libro->portada_url)
                                    <img src="{{ $libro->portada_url }}" alt="{{ $libro->titulo }}" class="absolute inset-0 w-full h-full object-cover opacity-85 group-hover/card:opacity-100 transition-opacity" onerror="this.style.display='none'">
                                @endif

                                <!-- Gradiente protector sobre la portada -->
                                <div class="absolute inset-0 bg-gradient-to-t from-brand-950 via-brand-950/40 to-transparent"></div>

                                <!-- Badge superior tipo cinta floral -->
                                <div class="relative z-10 flex items-center justify-between">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-brand-950/90 text-accent-400 border border-accent-400/30">
                                        {{ Str::limit($libro->categoria->nombre ?? 'Colección', 12) }}
                                    </span>
                                    <span class="w-2 h-2 rounded-full {{ $libro->stock_disponible > 0 ? 'bg-emerald-400 ring-2 ring-emerald-400/40' : 'bg-red-400' }}"></span>
                                </div>

                                <!-- Título y autor abajo -->
                                <div class="absolute bottom-2.5 left-2.5 right-2.5 z-10">
                                    <h4 class="text-xs sm:text-sm font-bold text-white line-clamp-2 leading-snug drop-shadow-md">
                                        {{ $libro->titulo }}
                                    </h4>
                                    <p class="text-[10px] text-accent-300 font-medium truncate mt-0.5">
                                        {{ $libro->autor }}
                                    </p>
                                    <div class="flex items-center justify-between text-[9px] text-white/80 mt-1 pt-1 border-t border-white/20">
                                        <span>{{ $libro->stock_disponible }} disponibles</span>
                                        <span class="text-accent-400 font-bold group-hover/card:underline">Ver &rarr;</span>
                                    </div>
                                </div>

                                <!-- Brillo de relieve 3D como lomo de libro -->
                                <div class="absolute top-0 bottom-0 left-0 w-1.5 bg-gradient-to-r from-white/30 to-transparent pointer-events-none"></div>
                            </a>
                        </div>
                        @endforeach

                        <!-- Base o Lazo Dorado que ata el Ramillete -->
                        <div class="absolute -bottom-2 z-40 flex items-center justify-center pointer-events-none">
                            <div class="px-4 py-1.5 rounded-full bg-gradient-to-r from-accent-500 via-accent-400 to-accent-500 text-brand-950 font-bold text-[11px] shadow-lg border border-white/40 flex items-center gap-1.5 transform group-hover/bouquet:scale-105 transition-transform">
                                <svg class="w-3.5 h-3.5 text-brand-950" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                </svg>
                                <span>Ramillete de Lecturas</span>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Subtítulo guía del ramillete -->
                <p class="text-[11px] text-accent-300/80 mt-3 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                    Pasa el cursor para abrir el ramillete y explorar los títulos
                </p>

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