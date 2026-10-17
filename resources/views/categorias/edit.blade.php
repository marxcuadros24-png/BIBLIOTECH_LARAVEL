@extends('layouts.app', ['title' => 'Editar Categoría'])

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    <div class="flex items-center justify-between pb-4 border-b border-cream-300">
        <div>
            <h1 class="text-2xl font-bold font-heading text-brand-900 tracking-tight">Editar Categoría</h1>
            <p class="text-sm text-cream-900/70 mt-0.5">Modificar información de <strong class="text-brand-800">{{ $categoria->nombre }}</strong></p>
        </div>
        <a href="{{ route('categorias.index') }}" class="text-xs font-bold uppercase tracking-wider text-cream-400 hover:text-brand-800">
            &larr; Volver
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-cream-300 shadow-soft p-6 sm:p-8">
        <form action="{{ route('categorias.update', $categoria->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Nombre de la Categoría *</label>
                <input type="text" name="nombre" value="{{ old('nombre', $categoria->nombre) }}" required class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Descripción</label>
                <textarea name="descripcion" rows="4" class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">{{ old('descripcion', $categoria->descripcion) }}</textarea>
            </div>

            <div class="pt-4 border-t border-cream-200 flex items-center justify-end gap-3">
                <a href="{{ route('categorias.index') }}" class="px-4 py-2.5 rounded-xl bg-cream-200 text-cream-900 font-bold text-xs uppercase tracking-wider hover:bg-cream-300 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-accent-400 text-brand-950 font-bold text-xs uppercase tracking-wider shadow hover:bg-accent-300 transition-all">
                    Actualizar Categoría
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
