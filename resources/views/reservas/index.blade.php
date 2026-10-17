@extends('layouts.app', ['title' => 'Reservas de Libros'])

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-cream-300">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold font-heading text-brand-900 tracking-tight">
                {{ Auth::user()->esEstudiante() ? 'Mis Reservas de Libros' : 'Gestión de Reservas' }}
            </h1>
            <p class="text-sm text-cream-900/70 mt-0.5">
                {{ Auth::user()->esEstudiante() ? 'Apartados temporales de ejemplares para recoger en mostrador.' : 'Control y confirmación de apartados temporales solicitados por estudiantes.' }}
            </p>
        </div>

        <a href="{{ route('catalogo') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-accent-400 text-brand-950 font-bold text-xs uppercase tracking-wider shadow hover:bg-accent-300 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            <span>Explorar Catálogo</span>
        </a>
    </div>

    <!-- Filtros de Estado -->
    <div class="bg-white p-4 rounded-2xl border border-cream-300 shadow-soft flex items-center gap-2">
        <a href="{{ route('reservas.index') }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ empty($estado) ? 'bg-brand-800 text-white shadow' : 'bg-cream-100 text-cream-900 hover:bg-cream-200' }}">
            Todas
        </a>
        <a href="{{ route('reservas.index', ['estado' => 'pendiente']) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ ($estado ?? '') === 'pendiente' ? 'bg-amber-600 text-white shadow' : 'bg-amber-50 text-amber-800 hover:bg-amber-100' }}">
            Pendientes
        </a>
        <a href="{{ route('reservas.index', ['estado' => 'confirmada']) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ ($estado ?? '') === 'confirmada' ? 'bg-emerald-600 text-white shadow' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100' }}">
            Confirmadas
        </a>
        <a href="{{ route('reservas.index', ['estado' => 'cancelada']) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ ($estado ?? '') === 'cancelada' ? 'bg-gray-600 text-white shadow' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
            Canceladas
        </a>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-2xl border border-cream-300 shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-cream-900">
                <thead class="bg-cream-100 text-[11px] font-bold uppercase tracking-wider text-cream-400 border-b border-cream-200">
                    <tr>
                        <th class="px-5 py-3.5">ID</th>
                        <th class="px-5 py-3.5">Libro</th>
                        @if(Auth::user()->esPersonal())
                        <th class="px-5 py-3.5">Estudiante</th>
                        @endif
                        <th class="px-5 py-3.5">Fecha Reserva</th>
                        <th class="px-5 py-3.5">Fecha Expiración</th>
                        <th class="px-5 py-3.5">Estado</th>
                        <th class="px-5 py-3.5 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-200">
                    @forelse($reservas as $reserva)
                    <tr class="hover:bg-cream-50 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-xs font-bold text-brand-800">
                            #{{ $reserva->id }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-cream-900">{{ $reserva->libro->titulo }}</div>
                            <div class="text-xs text-cream-400">{{ $reserva->libro->autor }}</div>
                        </td>
                        @if(Auth::user()->esPersonal())
                        <td class="px-5 py-3.5">
                            <div class="font-semibold text-cream-900">{{ $reserva->usuario->nombre }}</div>
                            <div class="text-xs text-cream-400">{{ $reserva->usuario->email }}</div>
                        </td>
                        @endif
                        <td class="px-5 py-3.5 text-xs text-cream-900/80">
                            {{ $reserva->fecha_reserva->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-5 py-3.5 text-xs font-semibold">
                            {{ $reserva->fecha_expiracion->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-5 py-3.5">
                            @if($reserva->estado === 'pendiente')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                    Pedido Pendiente
                                </span>
                            @elseif($reserva->estado === 'confirmada')
                                @if($reserva->prestamo && in_array($reserva->prestamo->estado, ['activo', 'vencido']))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                        Libro en Préstamo
                                    </span>
                                    <div class="text-[11px] text-cream-400 mt-0.5 font-mono">
                                        Préstamo #{{ $reserva->prestamo->id }} (Vence: {{ $reserva->prestamo->fecha_devolucion_esperada->format('d/m/Y') }})
                                    </div>
                                    @if($reserva->prestamo->solicitud_devolucion)
                                        <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300 animate-pulse">
                                            🔔 Alumno indicó devolución
                                        </span>
                                    @endif
                                @elseif($reserva->prestamo && $reserva->prestamo->estado === 'devuelto')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        Libro Devuelto ✓
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        Confirmada
                                    </span>
                                @endif
                            @elseif($reserva->estado === 'cancelada')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-800">
                                    Cancelada
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                    Expirada
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                @if(Auth::user()->esPersonal())
                                    {{-- Atender pedido: Entregar libro e iniciar préstamo sin formulario manual --}}
                                    @if($reserva->estado === 'pendiente')
                                    <form action="{{ route('reservas.confirmar', $reserva->id) }}" method="POST" onsubmit="return confirm('¿Confirmar el pedido y entregar el libro al estudiante? El préstamo se creará automáticamente.');" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-accent-400 hover:bg-accent-300 text-brand-950 font-bold text-xs shadow-sm transition-all">
                                            <svg class="w-4 h-4 text-brand-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            <span>Confirmar y Entregar Libro</span>
                                        </button>
                                    </form>
                                    @endif

                                    {{-- Botón: Confirmar que devolvieron el libro --}}
                                    @if($reserva->prestamo && in_array($reserva->prestamo->estado, ['activo', 'vencido']))
                                    <form action="{{ route('reservas.devolver', $reserva->id) }}" method="POST" onsubmit="return confirm('¿Confirmar que el estudiante devolvió físicamente el libro? Se restaurará el stock en el inventario.');" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            <span>Confirmar que devolvieron el libro</span>
                                        </button>
                                    </form>
                                    @endif

                                    @if($reserva->estado === 'pendiente')
                                    <form action="{{ route('reservas.cancelar', $reserva->id) }}" method="POST" onsubmit="return confirm('¿Cancelar esta reserva?');" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1.5 rounded-xl bg-cream-200 hover:bg-red-100 hover:text-red-700 text-cream-800 font-bold text-xs transition-colors" title="Cancelar reserva">
                                            Cancelar
                                        </button>
                                    </form>
                                    @endif
                                @else
                                    {{-- Vista del Estudiante --}}
                                    @if($reserva->estado === 'pendiente')
                                    <form action="{{ route('reservas.cancelar', $reserva->id) }}" method="POST" onsubmit="return confirm('¿Deseas cancelar tu pedido de reserva?');" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-red-100 hover:bg-red-200 text-red-700 font-bold text-xs transition-colors">
                                            Cancelar pedido
                                        </button>
                                    </form>
                                    @elseif($reserva->prestamo && in_array($reserva->prestamo->estado, ['activo', 'vencido']))
                                        @if($reserva->prestamo->solicitud_devolucion)
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                                ⏳ Entrega notificada
                                            </span>
                                        @else
                                            <form action="{{ route('prestamos.solicitarDevolucion', $reserva->prestamo->id) }}" method="POST" onsubmit="return confirm('¿Notificar que estás entregando el libro en la biblioteca?');" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-accent-400 hover:bg-accent-300 text-brand-950 font-bold text-xs shadow-sm transition-all">
                                                    <span>Solicitar Devolución</span>
                                                </button>
                                            </form>
                                        @endif
                                    @elseif($reserva->prestamo && $reserva->prestamo->estado === 'devuelto')
                                        <span class="inline-flex items-center gap-1 text-xs text-emerald-700 font-bold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Completado
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-cream-400">
                            No se encontraron reservas registradas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-cream-200">
            {{ $reservas->links() }}
        </div>
    </div>

</div>
@endsection
