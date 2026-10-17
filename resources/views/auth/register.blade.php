@extends('layouts.guest', ['title' => 'Crear Cuenta de Estudiante'])

@section('content')
<div class="min-h-[85vh] flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <h2 class="text-3xl font-bold font-heading text-brand-900 tracking-tight">Registro de Estudiante</h2>
        <p class="mt-1 text-sm text-cream-900/70">
            Regístrate para solicitar y reservar libros en nuestra biblioteca
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-lg px-4">
        <div class="bg-white py-8 px-6 shadow-xl rounded-2xl border border-cream-300 sm:px-10">

            @if($errors->any())
            <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-medium">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="nombre" class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">
                        Nombre completo
                    </label>
                    <input id="nombre" type="text" name="nombre" value="{{ old('nombre') }}" required autofocus placeholder="Ej. Juan Pérez López" class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800 transition-colors">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">
                        Correo electrónico institucional o personal
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="juan.perez@bibliotech.test" class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800 transition-colors">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">
                            Contraseña
                        </label>
                        <input id="password" type="password" name="password" required placeholder="Mínimo 6 caracteres" class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800 transition-colors">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">
                            Confirmar contraseña
                        </label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Repite la contraseña" class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800 transition-colors">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="telefono" class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">
                            Teléfono / Móvil (Opcional)
                        </label>
                        <input id="telefono" type="text" name="telefono" value="{{ old('telefono') }}" placeholder="987654321" class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800 transition-colors">
                    </div>
                    <div>
                        <label for="direccion" class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1">
                            Dirección (Opcional)
                        </label>
                        <input id="direccion" type="text" name="direccion" value="{{ old('direccion') }}" placeholder="Av. Los Pinos 123" class="w-full px-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800 transition-colors">
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-accent-50 border border-accent-200 text-xs text-accent-800">
                    <strong>Nota:</strong> Tu cuenta será creada con el perfil de <strong>Estudiante</strong>, permitiéndote consultar el catálogo, registrar préstamos y realizar reservas.
                </div>

                <button type="submit" class="w-full mt-2 py-3 px-4 rounded-xl bg-accent-400 text-brand-950 font-bold text-sm shadow hover:bg-accent-300 transition-all flex items-center justify-center gap-2">
                    <span>Crear Mi Cuenta</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            <div class="mt-6 text-center text-xs text-cream-900/70 border-t border-cream-200 pt-4">
                ¿Ya tienes una cuenta registrada?
                <a href="{{ route('login') }}" class="font-bold text-brand-800 hover:underline">
                    Inicia sesión aquí
                </a>
            </div>

        </div>
    </div>

</div>
@endsection
