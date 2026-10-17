@extends('layouts.app', ['title' => 'Reporte Financiero y Auditoría'])

@section('content')
<div class="space-y-6">

    <!-- Encabezado del Reporte -->
    <div class="flex items-center justify-between pb-4 border-b border-cream-300">
        <div>
            <div class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-purple-700 bg-purple-100 px-3 py-1 rounded-full mb-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Módulo Exclusivo de Administrador
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold font-heading text-brand-900 tracking-tight">
                Reporte Financiero y Estadísticas
            </h1>
            <p class="text-sm text-cream-900/70 mt-0.5">
                Auditoría global de ingresos por multas, demanda de libros y actividad de usuarios.
            </p>
        </div>

        <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-white border border-cream-300 text-cream-900 text-xs font-bold shadow-sm hover:bg-cream-100 transition-colors flex items-center gap-2">
            <svg class="w-4 h-4 text-cream-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Imprimir Reporte
        </button>
    </div>

    <!-- Balance Financiero de Multas -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        
        <div class="bg-white p-5 rounded-2xl border border-cream-300 shadow-soft">
            <div class="text-xs font-bold uppercase tracking-wider text-emerald-600">Total Recaudado (Pagadas)</div>
            <div class="text-3xl font-bold font-heading text-emerald-700 mt-2">
                {{ config('biblioteca.moneda', 'S/') }} {{ number_format($recaudadoMultas, 2) }}
            </div>
            <div class="text-xs text-cream-400 mt-1">
                Ingresos efectivos confirmados en caja
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-cream-300 shadow-soft">
            <div class="text-xs font-bold uppercase tracking-wider text-amber-600">Mora Pendiente de Cobro</div>
            <div class="text-3xl font-bold font-heading text-amber-600 mt-2">
                {{ config('biblioteca.moneda', 'S/') }} {{ number_format($pendienteMultas, 2) }}
            </div>
            <div class="text-xs text-cream-400 mt-1">
                Sanciones por devoluciones tardías
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-cream-300 shadow-soft">
            <div class="text-xs font-bold uppercase tracking-wider text-gray-500">Multas Condonadas</div>
            <div class="text-3xl font-bold font-heading text-gray-700 mt-2">
                {{ config('biblioteca.moneda', 'S/') }} {{ number_format($condonadoMultas, 2) }}
            </div>
            <div class="text-xs text-cream-400 mt-1">
                Importes perdonados por justificativo
            </div>
        </div>

    </div>

    <!-- Rankings en Dos Columnas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Libros más prestados -->
        <div class="bg-white rounded-2xl border border-cream-300 shadow-soft p-5">
            <h2 class="text-lg font-bold font-heading text-brand-900 mb-1">Top Libros Más Solicitados</h2>
            <p class="text-xs text-cream-400 mb-4">Títulos con mayor número de préstamos históricos</p>

            <div class="space-y-3">
                @forelse($librosMasPrestados as $idx => $libro)
                <div class="flex items-center justify-between p-3 rounded-xl bg-cream-50 border border-cream-200">
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-brand-800 text-white flex items-center justify-center text-xs font-bold">
                            {{ $idx + 1 }}
                        </span>
                        <div>
                            <div class="font-bold text-sm text-cream-900 line-clamp-1">{{ $libro->titulo }}</div>
                            <div class="text-xs text-cream-400">{{ $libro->autor }}</div>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-brand-100 text-brand-800 font-bold text-xs shrink-0">
                        {{ $libro->total_prestamos }} préstamos
                    </span>
                </div>
                @empty
                <div class="text-center text-cream-400 py-6 text-xs">Sin registros de préstamos históricos.</div>
                @endforelse
            </div>
        </div>

        <!-- Usuarios con mayor actividad -->
        <div class="bg-white rounded-2xl border border-cream-300 shadow-soft p-5">
            <h2 class="text-lg font-bold font-heading text-brand-900 mb-1">Usuarios Más Activos</h2>
            <p class="text-xs text-cream-400 mb-4">Lectores con mayor cantidad de libros solicitados</p>

            <div class="space-y-3">
                @forelse($usuariosMasActivos as $idx => $u)
                <div class="flex items-center justify-between p-3 rounded-xl bg-cream-50 border border-cream-200">
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-accent-400 text-brand-950 flex items-center justify-center text-xs font-bold">
                            {{ $idx + 1 }}
                        </span>
                        <div>
                            <div class="font-bold text-sm text-cream-900">{{ $u->nombre }}</div>
                            <div class="text-xs text-cream-400">{{ $u->email }} · <span class="uppercase font-semibold">{{ $u->rol }}</span></div>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-accent-100 text-accent-800 font-bold text-xs shrink-0">
                        {{ $u->total_prestamos }} solicitudes
                    </span>
                </div>
                @empty
                <div class="text-center text-cream-400 py-6 text-xs">Sin actividad registrada.</div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
