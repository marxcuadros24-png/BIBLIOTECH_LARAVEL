@extends('layouts.app', ['title' => 'Registrar Nuevo Libro'])

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between pb-4 border-b border-cream-300">
        <div>
            <h1 class="text-2xl font-bold font-heading text-brand-900 tracking-tight">
                Registrar Nuevo Libro
            </h1>
            <p class="text-sm text-cream-900/70 mt-0.5">
                Ingresa la información bibliográfica y el stock de ejemplares disponibles.
            </p>
        </div>
        <a href="{{ route('libros.index') }}" class="text-xs font-bold uppercase tracking-wider text-cream-400 hover:text-brand-800">
            &larr; Cancelar
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-cream-300 shadow-soft p-6 sm:p-8">
        <form action="{{ route('libros.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Título del Libro *</label>
                    <input type="text" name="titulo" value="{{ old('titulo') }}" required placeholder="Ej. Cien Años de Soledad" class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Autor(a) *</label>
                    <input type="text" name="autor" value="{{ old('autor') }}" required placeholder="Ej. Gabriel García Márquez" class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Código ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn') }}" placeholder="Ej. 978-0307474728" class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Categoría</label>
                    <select name="id_categoria" class="w-full px-3 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
                        <option value="">Seleccione una categoría</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ old('id_categoria') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Editorial</label>
                    <input type="text" name="editorial" value="{{ old('editorial') }}" placeholder="Ej. Sudamericana" class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Año de Publicación</label>
                    <input type="number" name="anio" value="{{ old('anio', date('Y')) }}" min="1000" max="{{ date('Y') + 1 }}" class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Stock de Ejemplares *</label>
                    <input type="number" name="stock_total" value="{{ old('stock_total', 1) }}" min="0" required class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">URL de Imagen de Portada (Opcional)</label>
                    <input type="url" name="portada_url" value="{{ old('portada_url') }}" placeholder="https://ejemplo.com/portada.jpg" class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">Sinopsis o Resumen</label>
                    <textarea name="sinopsis" rows="4" placeholder="Breve descripción del contenido del libro..." class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800">{{ old('sinopsis') }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-cream-200 flex items-center justify-end gap-3">
                <a href="{{ route('libros.index') }}" class="px-4 py-2.5 rounded-xl bg-cream-200 text-cream-900 font-bold text-xs uppercase tracking-wider hover:bg-cream-300 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-800 text-white font-bold text-xs uppercase tracking-wider shadow hover:bg-brand-700 transition-all">
                    Guardar Libro
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
