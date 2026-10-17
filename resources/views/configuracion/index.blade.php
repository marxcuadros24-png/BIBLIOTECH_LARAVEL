@extends('layouts.app', ['title' => 'Configuración del sistema'])

@section('content')
<div class="space-y-6">

    <!-- Encabezado de Configuración -->
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold font-heading text-brand-900 tracking-tight">
            Configuración del sistema
        </h1>
        <p class="text-sm text-cream-900/70 mt-1">
            Ajusta las reglas de negocio sin tocar código.
        </p>
    </div>

    <!-- Formulario de Configuración -->
    <div class="bg-white rounded-2xl border border-cream-300 shadow-soft p-6 sm:p-8">
        <form action="{{ route('configuracion.update') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Sección: Préstamos -->
            <div>
                <h2 class="text-base font-bold font-heading text-brand-800 mb-4 pb-2 border-b border-cream-200">
                    Préstamos
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="dias_prestamo" class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1.5">
                            Días de préstamo
                        </label>
                        <input type="number" id="dias_prestamo" name="dias_prestamo" value="{{ old('dias_prestamo', $diasPrestamo) }}" min="1" max="365" required class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
                        <span class="text-xs text-cream-400 block mt-1">Plazo máximo para devolver un libro.</span>
                    </div>

                    <div>
                        <label for="max_prestamos_activos" class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1.5">
                            Máximo de préstamos activos
                        </label>
                        <input type="number" id="max_prestamos_activos" name="max_prestamos_activos" value="{{ old('max_prestamos_activos', $maxPrestamos) }}" min="1" max="50" required class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
                    </div>
                </div>
            </div>

            <!-- Sección: Reservas -->
            <div>
                <h2 class="text-base font-bold font-heading text-brand-800 mb-4 pb-2 border-b border-cream-200">
                    Reservas
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="max_reservas_activas" class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1.5">
                            Máximo de reservas activas
                        </label>
                        <input type="number" id="max_reservas_activas" name="max_reservas_activas" value="{{ old('max_reservas_activas', $maxReservas) }}" min="1" max="50" required class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
                    </div>

                    <div>
                        <label for="horas_vigencia_reserva" class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1.5">
                            Horas de vigencia de la reserva
                        </label>
                        <input type="number" id="horas_vigencia_reserva" name="horas_vigencia_reserva" value="{{ old('horas_vigencia_reserva', $horasVigenciaReserva) }}" min="1" max="720" required class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
                    </div>
                </div>
            </div>

            <!-- Sección: Multas -->
            <div>
                <h2 class="text-base font-bold font-heading text-brand-800 mb-4 pb-2 border-b border-cream-200">
                    Multas
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="multa_por_dia" class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1.5">
                            Mora por día (S/)
                        </label>
                        <input type="number" step="0.01" id="multa_por_dia" name="multa_por_dia" value="{{ old('multa_por_dia', $multaPorDia) }}" min="0" required class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
                    </div>

                    <div>
                        <label for="dias_gracia" class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1.5">
                            Días de gracia
                        </label>
                        <input type="number" id="dias_gracia" name="dias_gracia" value="{{ old('dias_gracia', $diasGracia) }}" min="0" max="30" required class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
                        <span class="text-xs text-cream-400 block mt-1">Días antes de empezar a generar multa.</span>
                    </div>
                </div>

                <!-- Checkbox Bloquear usuarios -->
                <div class="mt-6 pt-2">
                    <label class="flex items-start gap-3 cursor-pointer select-none">
                        <input type="checkbox" name="bloquear_morosos" value="1" {{ old('bloquear_morosos', $bloquearMorosos) ? 'checked' : '' }} class="w-5 h-5 mt-0.5 rounded text-accent-500 focus:ring-accent-400 border-cream-300">
                        <div>
                            <span class="text-sm font-bold text-cream-900">Bloquear usuarios con multas pendientes</span>
                            <span class="text-xs text-cream-400 block mt-0.5">Si está activo, no podrán reservar ni pedir nuevos préstamos.</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="pt-6 border-t border-cream-200 flex items-center justify-end gap-4">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 text-sm font-semibold text-cream-900 hover:text-brand-800 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-accent-400 hover:bg-accent-500 text-brand-950 font-bold text-sm shadow hover:shadow-md transition-all">
                    Guardar cambios
                </button>
            </div>

        </form>
    </div>

</div>
@endsection