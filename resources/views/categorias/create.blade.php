@extends('layouts.app', ['title' => 'Nueva Categoría'])

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    <div class="flex items-center justify-between pb-4 border-b border-cream-300">
        <div>
            <h1 class="text-2xl font-bold font-heading text-brand-900 tracking-tight">Nueva Categoría</h1>
            <p class="text-sm text-cream-900/70 mt-0.5">Crear una nueva clasificación para organizar los libros.</p>
        </div>
        <a href="{{ route('categorias.index') }}" class="text-xs font-bold uppercase tracking-wider text-cream-400 hover:text-brand-800">
            &larr; Volver
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-cream-300 shadow-soft p-6 sm:p-8">
        <form action="{{ route('categorias.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Nombre de la Categoría *</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required placeholder="Ej. Literatura Clásica" class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Descripción (Opcional)</label>
                <textarea name="descripcion" rows="4" placeholder="Breve descripción del alcance temático de la categoría..." class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">{{ old('descripcion') }}</textarea>
            </div>

            <div class="pt-4 border-t border-cream-200 flex items-center justify-end gap-3">
                <a href="{{ route('categorias.index') }}" class="px-4 py-2.5 rounded-xl bg-cream-200 text-cream-900 font-bold text-xs uppercase tracking-wider hover:bg-cream-300 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-800 text-white font-bold text-xs uppercase tracking-wider shadow hover:bg-brand-700 transition-all">
                    Guardar Categoría
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
