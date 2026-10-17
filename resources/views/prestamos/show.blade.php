@extends('layouts.app', ['title' => 'Préstamo #' . $prestamo->id])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Navegación y Cabecera -->
    <div class="flex items-center justify-between pb-4 border-b border-cream-300">
        <div>
            <div class="flex items-center gap-2">
                <span class="font-mono text-sm font-bold text-brand-800">Préstamo #{{ $prestamo->id }}</span>
                @if($prestamo->estado === 'activo')
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">Activo</span>
                @elseif($prestamo->estado === 'devuelto')
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">Devuelto</span>
                @elseif($prestamo->estado === 'vencido')
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">Vencido</span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-800">{{ ucfirst($prestamo->estado) }}</span>
                @endif
            </div>
            <h1 class="text-2xl font-bold font-heading text-brand-900 mt-1">
                Ficha del Préstamo
            </h1>
        </div>

        <a href="{{ route('prestamos.index') }}" class="text-xs font-bold uppercase tracking-wider text-cream-400 hover:text-brand-800 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Volver
        </a>
    </div>

    <!-- Tarjetas de Información -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Tarjeta Libro -->
        <div class="bg-white rounded-2xl border border-cream-300 shadow-soft p-5">
            <span class="text-xs font-bold uppercase tracking-wider text-cream-400 block mb-2">Libro Prestado</span>
            <h3 class="text-lg font-bold text-brand-900 leading-snug">
                <a href="{{ route('libros.show', $prestamo->libro->id) }}" class="hover:underline">
                    {{ $prestamo->libro->titulo }}
                </a>
            </h3>
            <p class="text-xs text-cream-400 mt-0.5 font-medium">Por: {{ $prestamo->libro->autor }}</p>

            <div class="mt-4 pt-3 border-t border-cream-200 text-xs space-y-1 text-cream-900/80">
                <div><span class="text-cream-400">Categoría:</span> {{ $prestamo->libro->categoria->nombre ?? 'Sin categoría' }}</div>
                <div><span class="text-cream-400">ISBN:</span> <span class="font-mono">{{ $prestamo->libro->isbn ?? 'No especificado' }}</span></div>
                <div><span class="text-cream-400">Disponibilidad en almacén:</span> {{ $prestamo->libro->stock_disponible }} de {{ $prestamo->libro->stock_total }}</div>
            </div>
        </div>

        <!-- Tarjeta Lector -->
        <div class="bg-white rounded-2xl border border-cream-300 shadow-soft p-5">
            <span class="text-xs font-bold uppercase tracking-wider text-cream-400 block mb-2">Lector / Prestatario</span>
            <h3 class="text-lg font-bold text-brand-900 leading-snug">
                {{ $prestamo->usuario->nombre }}
            </h3>
            <p class="text-xs text-cream-400 mt-0.5 font-medium">{{ $prestamo->usuario->email }}</p>

            <div class="mt-4 pt-3 border-t border-cream-200 text-xs space-y-1 text-cream-900/80">
                <div><span class="text-cream-400">Rol:</span> <span class="font-bold uppercase text-brand-800">{{ $prestamo->usuario->rol }}</span></div>
                <div><span class="text-cream-400">Teléfono:</span> {{ $prestamo->usuario->telefono ?? 'No registrado' }}</div>
                <div><span class="text-cream-400">Estado de cuenta:</span> <span class="font-semibold {{ $prestamo->usuario->estaActivo() ? 'text-emerald-700' : 'text-red-700' }}">{{ ucfirst($prestamo->usuario->estado) }}</span></div>
            </div>
        </div>

    </div>

    <!-- Cronología y Fechas -->
    <div class="bg-white rounded-2xl border border-cream-300 shadow-soft p-5">
        <h3 class="text-sm font-bold uppercase tracking-wider text-cream-400 mb-4">Cronología de Fechas</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
            
            <div class="p-4 rounded-xl bg-cream-50 border border-cream-200">
                <span class="text-[11px] font-bold uppercase tracking-wider text-cream-400 block">Fecha de Préstamo</span>
                <span class="text-lg font-bold text-cream-900 mt-1 block">
                    {{ $prestamo->fecha_prestamo->format('d/m/Y') }}
                </span>
            </div>

            <div class="p-4 rounded-xl {{ $prestamo->estado === 'vencido' ? 'bg-red-50 border-red-200 text-red-900' : 'bg-cream-50 border-cream-200 text-cream-900' }} border">
                <span class="text-[11px] font-bold uppercase tracking-wider text-cream-400 block">Fecha Esperada</span>
                <span class="text-lg font-bold mt-1 block">
                    {{ $prestamo->fecha_devolucion_esperada->format('d/m/Y') }}
                </span>
                @if($prestamo->estado === 'vencido')
                    <span class="text-xs font-bold text-red-600 block mt-0.5">¡Atrasado {{ $diasAtraso }} días!</span>
                @endif
            </div>

            <div class="p-4 rounded-xl bg-cream-50 border border-cream-200">
                <span class="text-[11px] font-bold uppercase tracking-wider text-cream-400 block">Fecha Devolución Real</span>
                <span class="text-lg font-bold text-cream-900 mt-1 block">
                    {{ $prestamo->fecha_devolucion_real ? $prestamo->fecha_devolucion_real->format('d/m/Y') : 'Pendiente' }}
                </span>
            </div>

        </div>

        @if($prestamo->notas)
        <div class="mt-4 pt-4 border-t border-cream-200 text-xs">
            <span class="font-bold text-cream-400 uppercase text-[10px] block mb-1">Observaciones registradas:</span>
            <div class="text-cream-900/80 bg-cream-50 p-3 rounded-lg border border-cream-200">
                {{ $prestamo->notas }}
            </div>
        </div>
        @endif
    </div>

    <!-- Acción: Registrar Devolución (Solo Personal) -->
    @if(Auth::user()->tienePermiso('prestamos', 'devolver') && in_array($prestamo->estado, ['activo', 'vencido']))
    <div class="bg-white rounded-2xl border-2 border-emerald-400 shadow-soft p-6">
        <h3 class="text-lg font-bold font-heading text-brand-900 mb-1">Confirmar Devolución de Ejemplar</h3>
        <p class="text-xs text-cream-400 mb-4">Confirmar que el lector ha entregado el libro físicamente en la biblioteca para reponer el stock.</p>

        @if($diasAtrasoTotal > 0)
        <div class="mb-4 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs">
            <div class="font-bold text-sm text-amber-800 mb-1">Mora Detectada en la Devolución</div>
            El préstamo superó la fecha esperada por <strong>{{ $diasAtrasoTotal }} días</strong>.
            Al confirmar la devolución hoy, el sistema creará automáticamente una multa de:
            <div class="text-lg font-bold text-accent-700 mt-1">
                {{ config('biblioteca.moneda', 'S/') }} {{ number_format($moraEstimada, 2) }}
                <span class="text-xs font-normal text-amber-800">({{ $diasAtrasoCobrar }} días a cobrar &times; {{ config('biblioteca.moneda', 'S/') }} {{ number_format($tarifaDiaria, 2) }}/día)</span>
            </div>
        </div>
        @endif

        <form action="{{ route('prestamos.devolver', $prestamo->id) }}" method="POST" class="flex flex-col sm:flex-row items-end gap-3">
            @csrf

            <div class="w-full sm:w-64">
                <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Fecha de recepción física</label>
                <input type="date" name="fecha_devolucion_real" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 rounded-xl border border-cream-300 text-sm focus:border-brand-800">
            </div>

            <button type="submit" onclick="return confirm('¿Confirmar que el estudiante devolvió el libro físicamente?');" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-xs uppercase tracking-wider shadow hover:bg-emerald-700 transition-all flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Confirmar que devolvieron el libro
            </button>
        </form>
    </div>
    @elseif(Auth::user()->esEstudiante() && in_array($prestamo->estado, ['activo', 'vencido']))
    <div class="bg-white rounded-2xl border-2 border-accent-300 shadow-soft p-6">
        <h3 class="text-lg font-bold font-heading text-brand-900 mb-1">Entrega y Devolución del Libro</h3>
        <p class="text-xs text-cream-400 mb-4">¿Ya estás en la biblioteca para entregar este libro? Notifica al personal para que confirmen tu recepción.</p>

        @if($prestamo->solicitud_devolucion)
            <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-300 text-amber-900 text-xs font-bold flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Has notificado la entrega. Acércate al mostrador de biblioteca para que el personal confirme la recepción física.</span>
            </div>
        @else
            <form action="{{ route('prestamos.solicitarDevolucion', $prestamo->id) }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('¿Confirmar aviso de devolución al bibliotecario?');" class="px-6 py-2.5 rounded-xl bg-accent-400 text-brand-950 font-bold text-xs uppercase tracking-wider shadow hover:bg-accent-300 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Solicitar / Notificar Devolución de Ejemplar</span>
                </button>
            </form>
        @endif
    </div>
    @endif

    <!-- Multas Ligadas al Préstamo -->
    @if($prestamo->multas->isNotEmpty())
    <div class="bg-white rounded-2xl border border-cream-300 shadow-soft p-5">
        <h3 class="text-lg font-bold font-heading text-brand-900 mb-2">Multas Asociadas a este Préstamo</h3>
        <div class="space-y-2">
            @foreach($prestamo->multas as $m)
            <div class="p-3.5 rounded-xl border border-cream-200 bg-cream-50 flex items-center justify-between text-xs">
                <div>
                    <div class="font-bold text-sm text-brand-900">
                        Multa #{{ $m->id }} — {{ config('biblioteca.moneda', 'S/') }} {{ number_format($m->monto, 2) }}
                    </div>
                    <div class="text-cream-400 text-[11px] mt-0.5">
                        {{ $m->dias_mora }} días de atraso a {{ config('biblioteca.moneda', 'S/') }} {{ number_format($m->tarifa_diaria, 2) }}/día · Generada: {{ $m->fecha_generacion->format('d/m/Y') }}
                    </div>
                </div>
                <div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $m->estado === 'pagada' ? 'bg-emerald-100 text-emerald-800' : ($m->estado === 'condonada' ? 'bg-gray-200 text-gray-800' : 'bg-red-100 text-red-800') }}">
                        {{ strtoupper($m->estado) }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
