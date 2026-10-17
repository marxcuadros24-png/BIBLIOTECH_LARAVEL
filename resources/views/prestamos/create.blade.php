@extends('layouts.app', ['title' => 'Registrar Préstamo'])

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between pb-4 border-b border-cream-300">
        <div>
            <h1 class="text-2xl font-bold font-heading text-brand-900 tracking-tight">Registrar Nuevo Préstamo</h1>
            <p class="text-sm text-cream-900/70 mt-0.5">Asignar un ejemplar disponible a un lector registrado.</p>
        </div>
        <a href="{{ route('prestamos.index') }}" class="text-xs font-bold uppercase tracking-wider text-cream-400 hover:text-brand-800">
            &larr; Volver
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-cream-300 shadow-soft p-6 sm:p-8">
        <form action="{{ route('prestamos.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Selector de Usuario -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1.5">
                    Lector / Usuario *
                </label>
                <select name="id_usuario" required class="w-full px-3 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
                    <option value="">Seleccione un usuario activo</option>
                    @foreach($usuarios as $u)
                        <option value="{{ $u->id }}" {{ (old('id_usuario', $idUsuario ?? '') == $u->id) ? 'selected' : '' }}>
                            {{ $u->nombre }} ({{ $u->email }}) — [{{ strtoupper($u->rol) }}]
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Selector de Libro -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1.5">
                    Libro a prestar (Solo ejemplares con stock) *
                </label>
                <select name="id_libro" required class="w-full px-3 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
                    <option value="">Seleccione un libro</option>
                    @foreach($librosDisponibles as $l)
                        <option value="{{ $l->id }}" {{ (old('id_libro', $idLibro ?? '') == $l->id) ? 'selected' : '' }}>
                            {{ $l->titulo }} — {{ $l->autor }} [{{ $l->stock_disponible }} disponibles]
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Fecha de Devolución Esperada -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1.5">
                    Fecha límite de devolución *
                </label>
                <input type="date" name="fecha_devolucion_esperada" value="{{ old('fecha_devolucion_esperada', $fechaEsperadaDefault) }}" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800 font-medium">
                <span class="text-[11px] text-cream-400 block mt-1">
                    Plazo estándar por política: <strong>{{ config('biblioteca.dias_prestamo', 7) }} días</strong> a partir de hoy.
                </span>
            </div>

            <!-- Observaciones / Notas -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1.5">
                    Observaciones o Notas (Opcional)
                </label>
                <textarea name="notas" rows="3" placeholder="Estado físico del libro al momento de entregarlo, detalles de entrega..." class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">{{ old('notas') }}</textarea>
            </div>

            <!-- Box informativo de reglas -->
            <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-800 flex items-start gap-2.5">
                <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    Al registrar el préstamo, el stock disponible del libro se reducirá en 1 unidad. Si el libro no es devuelto en la fecha pactada, el sistema generará una multa automática calculada a razón de <strong>{{ config('biblioteca.moneda', 'S/') }} {{ number_format(config('biblioteca.multa_por_dia', 1.50), 2) }} por día de atraso</strong>.
                </div>
            </div>

            <div class="pt-4 border-t border-cream-200 flex items-center justify-end gap-3">
                <a href="{{ route('prestamos.index') }}" class="px-4 py-2.5 rounded-xl bg-cream-200 text-cream-900 font-bold text-xs uppercase tracking-wider hover:bg-cream-300 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-accent-400 text-brand-950 font-bold text-xs uppercase tracking-wider shadow hover:bg-accent-300 transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Confirmar Préstamo
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
