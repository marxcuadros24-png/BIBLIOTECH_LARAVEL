@extends('layouts.app', ['title' => 'Panel de Control'])

@section('content')
<div class="space-y-6">

    <!-- Encabezado del Panel y Acciones Rápidas -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-cream-300">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold font-heading text-brand-900 tracking-tight">
                Panel de Control
            </h1>
            <p class="text-sm text-cream-900/70 mt-0.5">
                Resumen operativo y métricas de la biblioteca en tiempo real.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @if(Auth::user()->tienePermiso('prestamos', 'crear'))
            <a href="{{ route('prestamos.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-accent-400 text-brand-950 font-bold text-xs uppercase tracking-wider shadow hover:bg-accent-300 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Nuevo Préstamo</span>
            </a>
            @endif

            @if(Auth::user()->tienePermiso('libros', 'crear'))
            <a href="{{ route('libros.create') }}" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-brand-800 text-white font-bold text-xs uppercase tracking-wider shadow hover:bg-brand-700 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span>Nuevo Libro</span>
            </a>
            @endif
        </div>
    </div>

    <!-- Tarjetas de Métricas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Libros -->
        <div class="bg-white p-5 rounded-2xl border border-cream-300 shadow-soft flex items-center justify-between">
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-cream-400">Títulos / Stock</div>
                <div class="text-2xl font-bold font-heading text-brand-900 mt-1">
                    {{ $totalLibros }} <span class="text-sm font-normal text-cream-400">/ {{ $stockTotal }} unid.</span>
                </div>
                <div class="text-xs font-semibold text-emerald-600 mt-1 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    {{ $stockDisponible }} disponibles
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-800 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
        </div>

        <!-- Préstamos Activos -->
        <div class="bg-white p-5 rounded-2xl border border-cream-300 shadow-soft flex items-center justify-between">
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-cream-400">Préstamos Activos</div>
                <div class="text-2xl font-bold font-heading text-blue-700 mt-1">
                    {{ $prestamosActivos }}
                </div>
                <div class="text-xs text-cream-400 mt-1">
                    Ejemplares en posesión
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>

        <!-- Préstamos Vencidos -->
        <div class="bg-white p-5 rounded-2xl border border-cream-300 shadow-soft flex items-center justify-between">
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-cream-400">Préstamos Vencidos</div>
                <div class="text-2xl font-bold font-heading text-red-600 mt-1">
                    {{ $prestamosVencidos }}
                </div>
                <div class="text-xs font-semibold text-red-600 mt-1">
                    {{ $prestamosVencidos > 0 ? 'Requieren seguimiento' : 'Sin moras activas' }}
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Multas Pendientes -->
        <div class="bg-white p-5 rounded-2xl border border-cream-300 shadow-soft flex items-center justify-between">
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-cream-400">Multas por Cobrar</div>
                <div class="text-2xl font-bold font-heading text-accent-600 mt-1">
                    {{ config('biblioteca.moneda', 'S/') }} {{ number_format($multasPendientesTotal, 2) }}
                </div>
                <div class="text-xs text-cream-400 mt-1">
                    {{ $multasPendientesCount }} {{ Str::plural('multa', $multasPendientesCount) }} pendientes
                </div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-accent-50 text-accent-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

    </div>

    <!-- Alerta de Préstamos Próximos a Vencer -->
    @if($prestamosPorVencer->isNotEmpty())
    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 shadow-sm">
        <div class="flex items-center gap-2 text-amber-900 font-bold text-sm mb-2">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span>Préstamos que vencen en los próximos {{ config('biblioteca.dias_alerta_mora', 3) }} días ({{ $prestamosPorVencer->count() }})</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 mt-2">
            @foreach($prestamosPorVencer as $pv)
            <div class="p-2.5 bg-white rounded-xl border border-amber-200 text-xs flex items-center justify-between">
                <div>
                    <div class="font-bold text-cream-900 truncate max-w-[200px]">{{ $pv->libro->titulo }}</div>
                    <div class="text-cream-400 text-[11px]">{{ $pv->usuario->nombre }}</div>
                </div>
                <div class="text-right">
                    <span class="inline-block px-2 py-0.5 rounded font-bold bg-amber-100 text-amber-800 text-[10px]">
                        Vence: {{ $pv->fecha_devolucion_esperada->format('d/m/Y') }}
                    </span>
                    <a href="{{ route('prestamos.show', $pv->id) }}" class="block text-[11px] text-brand-800 font-semibold hover:underline mt-0.5">
                        Ver &rarr;
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Tabla de Préstamos Recientes -->
    <div class="bg-white rounded-2xl border border-cream-300 shadow-soft overflow-hidden">
        <div class="p-5 border-b border-cream-200 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold font-heading text-brand-900">Actividad Reciente de Préstamos</h2>
                <p class="text-xs text-cream-400">Últimos movimientos registrados en el sistema</p>
            </div>
            <a href="{{ route('prestamos.index') }}" class="text-xs font-bold text-brand-800 hover:text-accent-600">
                Ver todos &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-cream-900">
                <thead class="bg-cream-100 text-[11px] font-bold uppercase tracking-wider text-cream-400 border-b border-cream-200">
                    <tr>
                        <th class="px-5 py-3">ID</th>
                        <th class="px-5 py-3">Libro</th>
                        <th class="px-5 py-3">Usuario</th>
                        <th class="px-5 py-3">Fecha Préstamo</th>
                        <th class="px-5 py-3">Devolución Esperada</th>
                        <th class="px-5 py-3">Estado</th>
                        <th class="px-5 py-3 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-200">
                    @forelse($prestamosRecientes as $prestamo)
                    <tr class="hover:bg-cream-50 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-xs font-semibold text-brand-800">
                            #{{ $prestamo->id }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-cream-900 truncate max-w-xs">{{ $prestamo->libro->titulo }}</div>
                            <div class="text-xs text-cream-400">{{ $prestamo->libro->autor }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="font-medium text-cream-900">{{ $prestamo->usuario->nombre }}</div>
                            <div class="text-xs text-cream-400">{{ $prestamo->usuario->email }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-cream-900/80">
                            {{ $prestamo->fecha_prestamo->format('d/m/Y') }}
                        </td>
                        <td class="px-5 py-3.5 text-xs font-semibold">
                            {{ $prestamo->fecha_devolucion_esperada->format('d/m/Y') }}
                            @if($prestamo->estado === 'vencido')
                                <span class="text-red-600 block text-[11px] font-bold">({{ $prestamo->dias_atraso }} días de atraso)</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            @if($prestamo->estado === 'activo')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                    Activo
                                </span>
                            @elseif($prestamo->estado === 'devuelto')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                    Devuelto
                                </span>
                            @elseif($prestamo->estado === 'vencido')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                    Vencido
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-800">
                                    {{ ucfirst($prestamo->estado) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                @if(Auth::user()->tienePermiso('prestamos', 'devolver') && in_array($prestamo->estado, ['activo', 'vencido']))
                                <form action="{{ route('prestamos.devolver', $prestamo->id) }}" method="POST" onsubmit="return confirm('¿Confirmar que el estudiante devolvió el libro físicamente? Se repondrá el stock en el inventario.');" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition-all" title="Confirmar devolución y reponer stock">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span>Devolución</span>
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('prestamos.show', $prestamo->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-cream-200 hover:bg-brand-800 hover:text-white text-brand-900 font-bold text-xs transition-colors">
                                    Detalle
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-cream-400">
                            No hay préstamos registrados recientemente.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
