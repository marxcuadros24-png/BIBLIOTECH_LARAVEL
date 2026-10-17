@extends('layouts.app', ['title' => 'Mi Panel de Estudiante'])

@section('content')
<div class="space-y-6">

    <!-- Banner de Bienvenida -->
    <div class="p-6 rounded-2xl bg-gradient-to-r from-brand-800 to-brand-900 text-white shadow-soft flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <span class="inline-block text-xs font-bold uppercase tracking-wider text-accent-300 bg-brand-950/60 px-3 py-1 rounded-full mb-2">
                Portal de Estudiante
            </span>
            <h1 class="text-2xl sm:text-3xl font-bold font-heading">
                ¡Hola, {{ Auth::user()->nombre }}!
            </h1>
            <p class="text-sm text-brand-100 mt-1">
                Consulta tus préstamos vigentes, fechas límites de devolución y reservas de libros.
            </p>
        </div>

        <a href="{{ route('catalogo') }}" class="px-4 py-2.5 rounded-xl bg-accent-400 text-brand-950 font-bold text-xs uppercase tracking-wider shadow hover:bg-accent-300 transition-all shrink-0 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            <span>Buscar Nuevos Libros</span>
        </a>
    </div>

    <!-- Alertas si tiene Multas Pendientes -->
    @if($misMultasPendientes->isNotEmpty())
    <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-900 shadow-sm flex items-start gap-3">
        <svg class="w-6 h-6 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <div class="flex-1">
            <h3 class="font-bold text-sm">Tienes {{ $misMultasPendientes->count() }} multa(s) pendiente(s) por pagar</h3>
            <p class="text-xs text-red-700 mt-0.5">
                Monto acumulado: <strong>{{ config('biblioteca.moneda', 'S/') }} {{ number_format($misMultasPendientes->sum('monto'), 2) }}</strong>. Acércate a la biblioteca para regularizar tus pagos y mantener tu cuenta activa.
            </p>
        </div>
        <a href="{{ route('multas.index') }}" class="px-3 py-1.5 rounded-lg bg-red-600 text-white font-bold text-xs hover:bg-red-700 transition-colors shrink-0">
            Ver detalle
        </a>
    </div>
    @endif

    <!-- Mis Préstamos en Curso -->
    <div class="bg-white rounded-2xl border border-cream-300 shadow-soft overflow-hidden">
        <div class="p-5 border-b border-cream-200 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold font-heading text-brand-900">Mis Préstamos en Curso</h2>
                <p class="text-xs text-cream-400">Libros que actualmente tienes en tu posesión</p>
            </div>
            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-cream-200 text-brand-800">
                {{ $misPrestamosActivos->count() }} {{ Str::plural('libro', $misPrestamosActivos->count()) }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-cream-900">
                <thead class="bg-cream-100 text-[11px] font-bold uppercase tracking-wider text-cream-400 border-b border-cream-200">
                    <tr>
                        <th class="px-5 py-3">Libro</th>
                        <th class="px-5 py-3">Fecha de Préstamo</th>
                        <th class="px-5 py-3">Fecha Límite</th>
                        <th class="px-5 py-3">Estado</th>
                        <th class="px-5 py-3 text-right">Ficha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-200">
                    @forelse($misPrestamosActivos as $prestamo)
                    <tr class="hover:bg-cream-50 transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-cream-900">{{ $prestamo->libro->titulo }}</div>
                            <div class="text-xs text-cream-400">{{ $prestamo->libro->autor }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-xs">
                            {{ $prestamo->fecha_prestamo->format('d/m/Y') }}
                        </td>
                        <td class="px-5 py-3.5 text-xs font-semibold">
                            {{ $prestamo->fecha_devolucion_esperada->format('d/m/Y') }}
                            @if($prestamo->estado === 'vencido')
                                <span class="text-red-600 block text-[11px] font-bold">¡Vencido hace {{ $prestamo->dias_atraso }} días!</span>
                            @else
                                <span class="text-cream-400 block text-[11px]">Quedan {{ Carbon\Carbon::today()->diffInDays($prestamo->fecha_devolucion_esperada, false) }} días</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            @if($prestamo->estado === 'activo')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                    En plazo
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                    Vencido
                                </span>
                            @endif

                            @if($prestamo->solicitud_devolucion)
                                <div class="mt-1">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                        ⏳ Entrega notificada
                                    </span>
                                </div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if(!$prestamo->solicitud_devolucion)
                                <form action="{{ route('prestamos.solicitarDevolucion', $prestamo->id) }}" method="POST" onsubmit="return confirm('¿Confirmas que estás entregando el libro en la biblioteca?');" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-lg bg-accent-400 hover:bg-accent-300 text-brand-950 font-bold text-xs shadow-sm transition-all">
                                        Devolver
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('prestamos.show', $prestamo->id) }}" class="inline-flex items-center gap-1 text-xs font-bold text-brand-800 hover:text-accent-600">
                                    Ver &rarr;
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-cream-400">
                            No tienes libros prestados actualmente. ¡Anímate a explorar el catálogo!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Sección de Reservas Activas -->
    <div class="bg-white rounded-2xl border border-cream-300 shadow-soft p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-bold font-heading text-brand-900">Mis Reservas Activas</h2>
                <p class="text-xs text-cream-400">Apartados temporales para recoger en mostrador</p>
            </div>
            <a href="{{ route('reservas.index') }}" class="text-xs font-bold text-brand-800 hover:text-accent-600">
                Ver historial &rarr;
            </a>
        </div>

        @if($misReservas->isEmpty())
            <div class="p-6 text-center text-cream-400 text-xs">
                No tienes reservas activas por recoger.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($misReservas as $reserva)
                <div class="p-4 rounded-xl border border-cream-200 bg-cream-50 flex items-start justify-between gap-3">
                    <div>
                        <div class="font-bold text-sm text-cream-900 line-clamp-1">{{ $reserva->libro->titulo }}</div>
                        <div class="text-xs text-cream-400">{{ $reserva->libro->autor }}</div>
                        <div class="text-[11px] text-brand-800 mt-2 font-medium">
                            Expira: {{ $reserva->fecha_expiracion->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <form action="{{ route('reservas.cancelar', $reserva->id) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('¿Deseas cancelar esta reserva?')" class="text-xs font-bold text-red-600 hover:underline">
                            Cancelar
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
