@extends('layouts.app', ['title' => 'Préstamos'])

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-cream-300">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold font-heading text-brand-900 tracking-tight">
                {{ Auth::user()->esEstudiante() ? 'Mis Préstamos' : 'Gestión de Préstamos' }}
            </h1>
            <p class="text-sm text-cream-900/70 mt-0.5">
                {{ Auth::user()->esEstudiante() ? 'Historial y estado de tus préstamos solicitados.' : 'Control de préstamos vigentes, fechas de vencimiento y devoluciones.' }}
            </p>
        </div>

        @if(Auth::user()->tienePermiso('prestamos', 'crear'))
        <a href="{{ route('prestamos.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-accent-400 text-brand-950 font-bold text-xs uppercase tracking-wider shadow hover:bg-accent-300 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Registrar Préstamo</span>
        </a>
        @endif
    </div>

    <!-- Filtros de Estado -->
    <div class="bg-white p-4 rounded-2xl border border-cream-300 shadow-soft flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('prestamos.index') }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ empty($estado) ? 'bg-brand-800 text-white shadow' : 'bg-cream-100 text-cream-900 hover:bg-cream-200' }}">
                Todos
            </a>
            <a href="{{ route('prestamos.index', ['estado' => 'activo']) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ ($estado ?? '') === 'activo' ? 'bg-blue-600 text-white shadow' : 'bg-blue-50 text-blue-800 hover:bg-blue-100' }}">
                Activos
            </a>
            <a href="{{ route('prestamos.index', ['estado' => 'vencido']) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ ($estado ?? '') === 'vencido' ? 'bg-red-600 text-white shadow' : 'bg-red-50 text-red-800 hover:bg-red-100' }}">
                Vencidos
            </a>
            <a href="{{ route('prestamos.index', ['estado' => 'devuelto']) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all {{ ($estado ?? '') === 'devuelto' ? 'bg-emerald-600 text-white shadow' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100' }}">
                Devueltos
            </a>
        </div>

        @if(Auth::user()->esPersonal())
        <form action="{{ route('prestamos.index') }}" method="GET" class="w-full sm:w-auto flex items-center gap-2">
            @if(!empty($estado))
                <input type="hidden" name="estado" value="{{ $estado }}">
            @endif
            <input type="text" name="q" value="{{ $busqueda ?? '' }}" placeholder="Buscar por libro o usuario..." class="w-full sm:w-64 px-3 py-1.5 rounded-xl border border-cream-300 text-xs focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
            <button type="submit" class="px-3 py-1.5 rounded-xl bg-brand-800 text-white font-bold text-xs">
                Buscar
            </button>
        </form>
        @endif
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
                        <th class="px-5 py-3.5">Lector / Usuario</th>
                        @endif
                        <th class="px-5 py-3.5">Fecha Préstamo</th>
                        <th class="px-5 py-3.5">Fecha Devolución</th>
                        <th class="px-5 py-3.5">Estado</th>
                        <th class="px-5 py-3.5 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cream-200">
                    @forelse($prestamos as $prestamo)
                    <tr class="hover:bg-cream-50 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-xs font-bold text-brand-800">
                            #{{ $prestamo->id }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-cream-900">{{ $prestamo->libro->titulo }}</div>
                            <div class="text-xs text-cream-400">{{ $prestamo->libro->autor }}</div>
                        </td>
                        @if(Auth::user()->esPersonal())
                        <td class="px-5 py-3.5">
                            <div class="font-semibold text-cream-900">{{ $prestamo->usuario->nombre }}</div>
                            <div class="text-xs text-cream-400">{{ $prestamo->usuario->email }}</div>
                        </td>
                        @endif
                        <td class="px-5 py-3.5 text-xs text-cream-900/80">
                            {{ $prestamo->fecha_prestamo->format('d/m/Y') }}
                        </td>
                        <td class="px-5 py-3.5 text-xs font-semibold">
                            @if($prestamo->fecha_devolucion_real)
                                <span class="text-emerald-700">Devuelto: {{ $prestamo->fecha_devolucion_real->format('d/m/Y') }}</span>
                            @else
                                <span>Esperado: {{ $prestamo->fecha_devolucion_esperada->format('d/m/Y') }}</span>
                                @if($prestamo->estado === 'vencido')
                                    <span class="text-red-600 block text-[11px] font-bold">¡Vencido hace {{ $prestamo->dias_atraso }} días!</span>
                                @endif
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            @if($prestamo->estado === 'activo')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                    Activo
                                </span>
                            @elseif($prestamo->estado === 'devuelto')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    Devuelto ✓
                                </span>
                            @elseif($prestamo->estado === 'vencido')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                    Vencido
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-800">
                                    {{ ucfirst($prestamo->estado) }}
                                </span>
                            @endif

                            @if($prestamo->solicitud_devolucion && in_array($prestamo->estado, ['activo', 'vencido']))
                                <div class="mt-1">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300 animate-pulse">
                                        🔔 Alumno indicó devolución
                                    </span>
                                </div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                {{-- Acción para Personal: Confirmar que devolvieron el libro --}}
                                @if(Auth::user()->tienePermiso('prestamos', 'devolver') && in_array($prestamo->estado, ['activo', 'vencido']))
                                <form action="{{ route('prestamos.devolver', $prestamo->id) }}" method="POST" onsubmit="return confirm('¿Confirmar que el estudiante devolvió físicamente el ejemplar? Se restaurará el stock en el inventario.');" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition-all" title="Confirmar devolución física y reponer stock">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span>Confirmar que devolvieron el libro</span>
                                    </button>
                                </form>
                                @endif

                                {{-- Acción para Estudiante: Solicitar / Notificar devolución --}}
                                @if(Auth::user()->esEstudiante() && in_array($prestamo->estado, ['activo', 'vencido']))
                                    @if($prestamo->solicitud_devolucion)
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                            ⏳ Entrega notificada
                                        </span>
                                    @else
                                        <form action="{{ route('prestamos.solicitarDevolucion', $prestamo->id) }}" method="POST" onsubmit="return confirm('¿Notificar que estás devolviendo o has entregado el libro físicamente en biblioteca?');" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-accent-400 hover:bg-accent-300 text-brand-950 font-bold text-xs shadow-sm transition-all">
                                                <span>Solicitar Devolución</span>
                                            </button>
                                        </form>
                                    @endif
                                @endif

                                <a href="{{ route('prestamos.show', $prestamo->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-cream-200 hover:bg-brand-800 hover:text-white text-cream-900 font-bold text-xs transition-colors" title="Ver detalles y multas">
                                    Ficha
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-cream-400">
                            No se encontraron registros de préstamos.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-cream-200">
            {{ $prestamos->links() }}
        </div>
    </div>

</div>
@endsection
