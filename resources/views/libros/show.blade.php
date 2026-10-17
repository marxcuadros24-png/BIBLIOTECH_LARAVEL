@extends('layouts.app', ['title' => $libro->titulo])

@section('content')
<div class="space-y-6">

    <!-- Navegación y Acciones -->
    <div class="flex items-center justify-between pb-4 border-b border-cream-300">
        <a href="{{ route('libros.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-800 hover:text-accent-600 transition-colors uppercase tracking-wider">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Volver a la lista de libros
        </a>

        <div class="flex items-center gap-2">
            @if(Auth::user()->tienePermiso('libros', 'editar'))
            <a href="{{ route('libros.edit', $libro->id) }}" class="px-4 py-2 rounded-xl bg-white border border-cream-300 hover:bg-cream-100 text-cream-900 font-bold text-xs uppercase tracking-wider transition-colors">
                Editar Libro
            </a>
            @endif

            @if(Auth::user()->tienePermiso('prestamos', 'crear') && $libro->stock_disponible > 0)
            <a href="{{ route('prestamos.create', ['libro_id' => $libro->id]) }}" class="px-4 py-2 rounded-xl bg-accent-400 text-brand-950 hover:bg-accent-300 font-bold text-xs uppercase tracking-wider shadow transition-all flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Prestar Este Libro
            </a>
            @elseif(Auth::user()->esEstudiante() && !isset($miReserva) && !isset($miPrestamo) && $libro->stock_disponible > 0)
            <form action="{{ route('reservas.store') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="id_libro" value="{{ $libro->id }}">
                <button type="submit" class="px-5 py-2 rounded-xl bg-accent-400 text-brand-950 hover:bg-accent-300 font-bold text-xs uppercase tracking-wider shadow transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                    Reservar Este Libro
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Notificación en el Libro si el Estudiante lo tiene reservado o en préstamo -->
    @if(isset($miReserva) && $miReserva)
    <div class="p-4 rounded-2xl bg-amber-50 border-2 border-amber-300 text-amber-950 flex items-start justify-between gap-3 shadow-sm animate-fade-in">
        <div class="flex items-start gap-3">
            <div class="p-2 bg-amber-500 text-brand-950 rounded-xl font-bold shrink-0">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/></svg>
            </div>
            <div>
                <div class="font-bold text-sm text-brand-950">¡Has reservado este libro! (Pedido #{{ $miReserva->id }})</div>
                <p class="text-xs text-amber-900 mt-0.5">
                    Estado: <strong class="uppercase text-amber-800">{{ $miReserva->estado }}</strong> · Tienes hasta el <strong>{{ $miReserva->fecha_expiracion->format('d/m/Y H:i') }}</strong> para recoger el ejemplar en el mostrador.
                </p>
            </div>
        </div>
        <a href="{{ route('reservas.index') }}" class="px-3.5 py-2 rounded-xl bg-amber-400 hover:bg-amber-300 text-brand-950 font-bold text-xs shadow-sm transition-all shrink-0">
            Ver en Mis Reservas
        </a>
    </div>
    @elseif(isset($miPrestamo) && $miPrestamo)
    <div class="p-4 rounded-2xl bg-blue-50 border-2 border-blue-300 text-blue-950 flex items-start justify-between gap-3 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="p-2 bg-blue-600 text-white rounded-xl font-bold shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div>
                <div class="font-bold text-sm text-blue-950">¡Tienes este libro en préstamo activo! (Préstamo #{{ $miPrestamo->id }})</div>
                <p class="text-xs text-blue-900 mt-0.5">
                    Fecha límite esperada para devolución: <strong>{{ $miPrestamo->fecha_devolucion_esperada->format('d/m/Y') }}</strong>.
                </p>
            </div>
        </div>
        <a href="{{ route('prestamos.show', $miPrestamo->id) }}" class="px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition-all shrink-0">
            Ver Ficha del Préstamo
        </a>
    </div>
    @endif

    <!-- Tarjeta Principal del Libro -->
    <div class="bg-white rounded-2xl border border-cream-300 shadow-soft overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 p-6 sm:p-8">
            
            <!-- Portada / Visual -->
            <div class="md:col-span-4 flex flex-col items-center">
                <div class="w-full max-w-[260px] aspect-[3/4] rounded-2xl bg-gradient-to-tr from-brand-900 via-brand-800 to-brand-700 p-4 shadow-xl relative overflow-hidden flex flex-col justify-between text-white text-center">
                    @if($libro->portada_url)
                        <img src="{{ $libro->portada_url }}" alt="{{ $libro->titulo }}" class="absolute inset-0 w-full h-full object-cover" onerror="this.style.display='none'">
                    @endif
                    <div class="relative z-10">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-accent-400 block mb-1">
                            {{ $libro->categoria->nombre ?? 'General' }}
                        </span>
                    </div>
                    <div class="relative z-10 bg-brand-950/80 p-3 rounded-xl border border-white/10">
                        <h2 class="text-sm font-bold">{{ $libro->titulo }}</h2>
                        <p class="text-xs text-brand-200 mt-1">{{ $libro->autor }}</p>
                    </div>
                </div>

                <!-- Stock Box -->
                <div class="w-full max-w-[260px] mt-4 p-4 rounded-xl bg-cream-50 border border-cream-200 text-center">
                    <div class="text-xs uppercase font-bold text-cream-400">Estado de Inventario</div>
                    <div class="flex items-center justify-around mt-2">
                        <div>
                            <div class="text-xl font-bold text-emerald-700">{{ $libro->stock_disponible }}</div>
                            <div class="text-[10px] uppercase font-semibold text-cream-400">Disponibles</div>
                        </div>
                        <div class="h-8 w-px bg-cream-200"></div>
                        <div>
                            <div class="text-xl font-bold text-cream-900">{{ $libro->stock_total }}</div>
                            <div class="text-[10px] uppercase font-semibold text-cream-400">Total</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ficha Técnica -->
            <div class="md:col-span-8 flex flex-col justify-between">
                <div>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-brand-50 text-brand-800 border border-brand-200 mb-2">
                        {{ $libro->categoria->nombre ?? 'Sin categoría' }}
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-bold font-heading text-brand-900">{{ $libro->titulo }}</h1>
                    <p class="text-base text-cream-900/80 mt-1 font-medium">Por: <strong class="text-brand-800">{{ $libro->autor }}</strong></p>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 my-6 p-4 rounded-xl bg-cream-50 border border-cream-200 text-xs">
                        <div>
                            <span class="text-cream-400 block uppercase font-bold text-[10px]">ISBN</span>
                            <span class="font-mono font-bold text-cream-900">{{ $libro->isbn ?? 'No registrado' }}</span>
                        </div>
                        <div>
                            <span class="text-cream-400 block uppercase font-bold text-[10px]">Editorial</span>
                            <span class="font-bold text-cream-900">{{ $libro->editorial ?? 'No especificada' }}</span>
                        </div>
                        <div>
                            <span class="text-cream-400 block uppercase font-bold text-[10px]">Año de Publicación</span>
                            <span class="font-bold text-cream-900">{{ $libro->anio ?? 's/f' }}</span>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-cream-400 mb-2">Sinopsis / Descripción</h3>
                        <div class="text-sm text-cream-900/80 leading-relaxed bg-white p-4 rounded-xl border border-cream-200">
                            {{ $libro->sinopsis ?? 'Este ejemplar no cuenta con una sinopsis descriptiva registrada.' }}
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-cream-200 text-xs text-cream-400 flex items-center justify-between">
                    <div>Registrado el: {{ $libro->created_at->format('d/m/Y') }}</div>
                    <div>Última actualización: {{ $libro->updated_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>

        </div>
    </div>

    <!-- Préstamos en curso de este libro -->
    @if(Auth::user()->esPersonal())
    <div class="bg-white rounded-2xl border border-cream-300 shadow-soft p-5">
        <h3 class="text-lg font-bold font-heading text-brand-900 mb-1">Préstamos Activos de este Ejemplar</h3>
        <p class="text-xs text-cream-400 mb-4">Usuarios que actualmente tienen prestada una copia de este libro</p>

        @if($prestamosActivos->isEmpty())
            <div class="p-6 text-center text-xs text-cream-400 bg-cream-50 rounded-xl">
                Actualmente no hay ejemplares prestados de este libro. Todas las copias registradas están disponibles en estantería.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-cream-100 text-[11px] font-bold uppercase tracking-wider text-cream-400 border-b border-cream-200">
                        <tr>
                            <th class="px-4 py-2.5">ID</th>
                            <th class="px-4 py-2.5">Usuario</th>
                            <th class="px-4 py-2.5">Fecha Préstamo</th>
                            <th class="px-4 py-2.5">Devolución Esperada</th>
                            <th class="px-4 py-2.5">Estado</th>
                            <th class="px-4 py-2.5 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-cream-200 text-xs">
                        @foreach($prestamosActivos as $pa)
                        <tr>
                            <td class="px-4 py-3 font-mono font-bold text-brand-800">#{{ $pa->id }}</td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-cream-900">{{ $pa->usuario->nombre }}</div>
                                <div class="text-cream-400 text-[11px]">{{ $pa->usuario->email }}</div>
                            </td>
                            <td class="px-4 py-3">{{ $pa->fecha_prestamo->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 font-semibold">
                                {{ $pa->fecha_devolucion_esperada->format('d/m/Y') }}
                                @if($pa->estado === 'vencido')
                                    <span class="text-red-600 block text-[10px] font-bold">({{ $pa->dias_atraso }} días mora)</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold {{ $pa->estado === 'activo' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($pa->estado) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('prestamos.show', $pa->id) }}" class="font-bold text-brand-800 hover:underline">
                                    Procesar devolución &rarr;
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    @endif

</div>
@endsection
