@extends('layouts.app', ['title' => 'Editar: ' . $libro->titulo])

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between pb-4 border-b border-cream-300">
        <div>
            <h1 class="text-2xl font-bold font-heading text-brand-900 tracking-tight">
                Editar Libro
            </h1>
            <p class="text-sm text-cream-900/70 mt-0.5">
                Modificar información de <strong class="text-brand-800">{{ $libro->titulo }}</strong>
            </p>
        </div>
        <a href="{{ route('libros.show', $libro->id) }}" class="text-xs font-bold uppercase tracking-wider text-cream-400 hover:text-brand-800">
            &larr; Volver
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-cream-300 shadow-soft p-6 sm:p-8">
        <form action="{{ route('libros.update', $libro->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Título del Libro *</label>
                    <input type="text" name="titulo" value="{{ old('titulo', $libro->titulo) }}" required class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Autor(a) *</label>
                    <input type="text" name="autor" value="{{ old('autor', $libro->autor) }}" required class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Código ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn', $libro->isbn) }}" class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Categoría</label>
                    <select name="id_categoria" class="w-full px-3 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
                        <option value="">Seleccione una categoría</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ old('id_categoria', $libro->id_categoria) == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Editorial</label>
                    <input type="text" name="editorial" value="{{ old('editorial', $libro->editorial) }}" class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Año de Publicación</label>
                    <input type="number" name="anio" value="{{ old('anio', $libro->anio) }}" min="1000" max="{{ date('Y') + 1 }}" class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Stock Total *</label>
                    <input type="number" name="stock_total" value="{{ old('stock_total', $libro->stock_total) }}" min="0" required class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
                    <span class="text-[11px] text-cream-400 block mt-0.5">Disponibles actuales: <strong>{{ $libro->stock_disponible }}</strong></span>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">URL de Imagen de Portada</label>
                    <input type="url" name="portada_url" value="{{ old('portada_url', $libro->portada_url) }}" class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Sinopsis</label>
                    <textarea name="sinopsis" rows="4" class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">{{ old('sinopsis', $libro->sinopsis) }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-cream-200 flex items-center justify-end gap-3">
                <a href="{{ route('libros.show', $libro->id) }}" class="px-4 py-2.5 rounded-xl bg-cream-200 text-cream-900 font-bold text-xs uppercase tracking-wider hover:bg-cream-300 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-accent-400 text-brand-950 font-bold text-xs uppercase tracking-wider shadow hover:bg-accent-300 transition-all">
                    Actualizar Libro
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
