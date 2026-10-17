@extends('layouts.app', ['title' => 'Gestión de Multas'])

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-cream-300">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold font-heading text-brand-900 tracking-tight">
                {{ Auth::user()->esEstudiante() ? 'Mis Multas y Sanciones' : 'Gestión de Multas y Cobranza' }}
            </h1>
            <p class="text-sm text-cream-900/70 mt-0.5">
                Control de penalizaciones por mora en devoluciones y estado de pago.
            </p>
        </div>
    </div>

    <!-- Totales / Métricas de Multas -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-cream-300 shadow-soft">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-600 block">Total Pendiente</span>
            <div class="text-2xl font-bold font-heading text-amber-700 mt-1">
                {{ config('biblioteca.moneda', 'S/') }} {{ number_format($totales['pendiente'], 2) }}
            </div>
            <span class="text-[11px] text-cream-400">Por cobrar en caja</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-cream-300 shadow-soft">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 block">Total Pagado</span>
            <div class="text-2xl font-bold font-heading text-emerald-700 mt-1">
                {{ config('biblioteca.moneda', 'S/') }} {{ number_format($totales['pagada'], 2) }}
            </div>
            <span class="text-[11px] text-cream-400">Recaudado efectivamente</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-cream-300 shadow-soft">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-500 block">Total Condonado</span>
            <div class="text-2xl font-bold font-heading text-gray-700 mt-1">
                {{ config('biblioteca.moneda', 'S/') }} {{ number_format($totales['condonada'], 2) }}
            </div>
            <span class="text-[11px] text-cream-400">Exonerado administrativamente</span>
        </div>
    </div>

    <!-- Filtros de Estado -->
    <div class="bg-white p-4 rounded-2xl border border-cream-300 shadow-soft flex items-center gap-2">
        <a href="{{ route('multas.index') }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ empty($estado) ? 'bg-brand-800 text-white shadow' : 'bg-cream-100 text-cream-900 hover:bg-cream-200' }}">
            Todas
        </a>
        <a href="{{ route('multas.index', ['estado' => 'pendiente']) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ ($estado ?? '') === 'pendiente' ? 'bg-amber-600 text-white shadow' : 'bg-amber-50 text-amber-800 hover:bg-amber-100' }}">
            Pendientes
        </a>
        <a href="{{ route('multas.index', ['estado' => 'pagada']) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ ($estado ?? '') === 'pagada' ? 'bg-emerald-600 text-white shadow' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100' }}">
            Pagadas
        </a>
        <a href="{{ route('multas.index', ['estado' => 'condonada']) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ ($estado ?? '') === 'condonada' ? 'bg-gray-600 text-white shadow' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
            Condonadas
        </a>
    </div>

    <!-- Tabla de Multas -->
    <div class="bg-white rounded-2xl border border-cream-300 shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-cream-900">
                <thead class="bg-cream-100 text-[11px] font-bold uppercase tracking-wider text-cream-400 border-b border-cream-200">
                    <tr>
                        <th class="px-5 py-3.5">ID</th>
                        <th class="px-5 py-3.5">Lector / Usuario</th>
                        <th class="px-5 py-3.5">Libro Asociado</th>
                        <th class="px-5 py-3.5">Detalle de Mora</th>
                        <th class="px-5 py-3.5">Importe</th>
                        <th class="px-5 py-3.5">Estado</th>
                        @if(Auth::user()->esPersonal())
                        <th class="px-5 py-3.5 text-right">Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-200">
                    @forelse($multas as $multa)
                    <tr class="hover:bg-cream-50 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-xs font-bold text-brand-800">
                            #{{ $multa->id }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-cream-900">{{ $multa->usuario->nombre ?? 'Usuario' }}</div>
                            <div class="text-xs text-cream-400">{{ $multa->usuario->email ?? '-' }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="font-medium text-cream-900 line-clamp-1">
                                {{ $multa->prestamo->libro->titulo ?? 'Libro' }}
                            </div>
                            <div class="text-[11px] text-cream-400">Préstamo #{{ $multa->id_prestamo }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-xs">
                            <span class="font-bold text-red-600">{{ $multa->dias_mora }} días</span> a {{ config('biblioteca.moneda', 'S/') }} {{ number_format($multa->tarifa_diaria, 2) }}/día
                            <span class="text-cream-400 block text-[11px]">Generada: {{ $multa->fecha_generacion->format('d/m/Y') }}</span>
                        </td>
                        <td class="px-5 py-3.5 font-bold text-base text-brand-900">
                            {{ config('biblioteca.moneda', 'S/') }} {{ number_format($multa->monto, 2) }}
                        </td>
                        <td class="px-5 py-3.5">
                            @if($multa->estado === 'pendiente')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                    Pendiente
                                </span>
                            @elseif($multa->estado === 'pagada')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                    Pagada
                                </span>
                                @if($multa->fecha_pago)
                                <span class="text-[10px] text-cream-400 block">{{ $multa->fecha_pago->format('d/m/Y') }}</span>
                                @endif
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-800">
                                    Condonada
                                </span>
                            @endif
                        </td>
                        @if(Auth::user()->esPersonal())
                        <td class="px-5 py-3.5 text-right">
                            @if(!$multa->esta_cerrada)
                            <div class="flex items-center justify-end gap-1.5">
                                @if(Auth::user()->tienePermiso('multas', 'pagar'))
                                <form action="{{ route('multas.pagar', $multa->id) }}" method="POST" onsubmit="return confirm('¿Registrar el pago de esta multa?');">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow transition-colors">
                                        Cobrar
                                    </button>
                                </form>
                                @endif

                                @if(Auth::user()->tienePermiso('multas', 'condonar'))
                                <form action="{{ route('multas.condonar', $multa->id) }}" method="POST" onsubmit="return confirm('¿Exonerar/condonar esta multa?');">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-cream-200 hover:bg-cream-300 text-cream-900 font-bold text-xs transition-colors">
                                        Condonar
                                    </button>
                                </form>
                                @endif
                            </div>
                            @else
                            <span class="text-xs text-cream-400 italic font-medium">Cerrada</span>
                            @endif
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-cream-400">
                            No existen multas registradas bajo este criterio.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-cream-200">
            {{ $multas->links() }}
        </div>
    </div>

</div>
@endsection
