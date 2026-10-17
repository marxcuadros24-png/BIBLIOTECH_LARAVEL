@extends('layouts.guest', ['title' => 'Iniciar Sesión'])

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <div class="w-14 h-14 rounded-2xl bg-brand-800 text-accent-400 mx-auto flex items-center justify-center font-bold text-2xl shadow-xl border border-brand-700 mb-4">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
            </svg>
        </div>
        <h2 class="text-3xl font-bold font-heading text-brand-900 tracking-tight">Acceso al Sistema</h2>
        <p class="mt-1 text-sm text-cream-900/70">
            Ingresa tus credenciales para administrar o consultar tus préstamos
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4">
        <div class="bg-white py-8 px-6 shadow-xl rounded-2xl border border-cream-300 sm:px-10">

            @if(session('error') || $errors->any())
            <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-medium">
                @if(session('error'))
                    <div>{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-cream-900 mb-1.5">
                        Correo electrónico
                    </label>
                    <div class="relative">
                        <svg class="w-5 h-5 text-cream-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="ejemplo@bibliotech.test" class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800 transition-colors">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-cream-900">
                            Contraseña
                        </label>
                    </div>
                    <div class="relative">
                        <svg class="w-5 h-5 text-cream-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <input id="password" type="password" name="password" required placeholder="••••••••" class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-cream-300 text-sm focus:border-brand-800 focus:ring-1 focus:ring-brand-800 transition-colors">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center text-xs text-cream-900 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-brand-800 focus:ring-brand-800 border-cream-300">
                        <span class="ml-2 font-medium">Recordar sesión</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 px-4 rounded-xl bg-brand-800 text-white font-bold text-sm shadow hover:bg-brand-700 transition-all flex items-center justify-center gap-2">
                    <span>Entrar al Panel</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            <!-- Acceso Rápido para Pruebas -->
            <div class="mt-6 pt-6 border-t border-cream-200">
                <div class="text-[11px] font-bold uppercase tracking-wider text-cream-400 text-center mb-3">
                    Cuentas de prueba en db_libros (Contraseña: <code class="text-brand-800 bg-cream-200 px-1 py-0.5 rounded">password</code>)
                </div>
                <div class="grid grid-cols-3 gap-2 text-center text-xs">
                    <button type="button" onclick="document.getElementById('email').value='admin@bibliotech.test'; document.getElementById('password').value='password';" class="p-2 rounded-lg bg-purple-50 text-purple-800 hover:bg-purple-100 font-bold border border-purple-200 transition-colors">
                        Admin
                    </button>
                    <button type="button" onclick="document.getElementById('email').value='biblio@bibliotech.test'; document.getElementById('password').value='password';" class="p-2 rounded-lg bg-blue-50 text-blue-800 hover:bg-blue-100 font-bold border border-blue-200 transition-colors">
                        Bibliotecario
                    </button>
                    <button type="button" onclick="document.getElementById('email').value='carlos@bibliotech.test'; document.getElementById('password').value='password';" class="p-2 rounded-lg bg-emerald-50 text-emerald-800 hover:bg-emerald-100 font-bold border border-emerald-200 transition-colors">
                        Estudiante
                    </button>
                </div>
            </div>

            <!-- Registro -->
            <div class="mt-6 text-center text-xs text-cream-900/70">
                ¿No tienes una cuenta aún?
                <a href="{{ route('register') }}" class="font-bold text-brand-800 hover:underline">
                    Crear cuenta de estudiante
                </a>
            </div>

        </div>
    </div>

</div>
@endsection
