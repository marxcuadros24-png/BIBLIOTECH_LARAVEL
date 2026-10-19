<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'BiblioTech' }} · Catálogo y Gestión Bibliotecaria</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .font-heading { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="h-full bg-cream-100 text-cream-900 antialiased flex flex-col">

    <!-- Header Público -->
    <header class="bg-brand-800 text-white shadow-md border-b border-brand-700 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Logo -->
                <a href="{{ route('inicio') }}" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 rounded-xl bg-accent-400 text-brand-900 flex items-center justify-center font-bold text-xl shadow-glow group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-brand-950" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <div>
                        <span class="font-heading font-bold text-xl text-white tracking-tight">Biblio<span class="text-accent-400">Tech</span></span>
                        <span class="hidden sm:inline-block text-xs text-brand-200 ml-2 px-2 py-0.5 rounded bg-brand-900/60 font-medium">Biblioteca Digital</span>
                    </div>
                </a>

                <!-- Navegación Pública -->
                <nav class="flex items-center gap-3">
                    <a href="{{ route('inicio') }}" class="px-3 py-2 text-sm font-semibold rounded-lg text-brand-100 hover:text-white hover:bg-brand-700 transition-colors {{ request()->routeIs('inicio') ? 'bg-brand-900/60 text-white' : '' }}">
                        Inicio
                    </a>
                    <a href="{{ route('catalogo') }}" class="px-3 py-2 text-sm font-semibold rounded-lg text-brand-100 hover:text-white hover:bg-brand-700 transition-colors {{ request()->routeIs('catalogo') ? 'bg-brand-900/60 text-white' : '' }}">
                        Explorar Catálogo
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}" class="ml-2 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-accent-400 text-brand-950 font-bold text-sm shadow hover:bg-accent-300 transition-all">
                            <span>Mi Panel</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-3.5 py-2 text-sm font-semibold rounded-xl text-brand-100 hover:text-white hover:bg-brand-700 transition-colors">
                            Iniciar sesión
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-accent-400 text-brand-950 font-bold text-sm shadow hover:bg-accent-300 transition-all">
                            Registrarse
                        </a>
                    @endauth
                </nav>

            </div>
        </div>
    </header>

    <!-- Contenido Principal -->
    <main class="flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <!-- Mensajes Flash de Sesión -->
            @if(session('exito'))
            <div class="mb-5 p-4 rounded-2xl bg-emerald-50 border-2 border-emerald-400 text-emerald-900 flex items-start gap-3 shadow-md" x-data="{ show: true }" x-show="show">
                <svg class="w-6 h-6 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="flex-1 text-sm font-semibold">{{ session('exito') }}</div>
                <button type="button" @click="show = false" class="text-emerald-500 hover:text-emerald-800 text-lg font-bold">&times;</button>
            </div>
            @endif

            @if(session('error') || $errors->any())
            <div class="mb-5 p-4 rounded-2xl bg-red-50 border-2 border-red-300 text-red-900 flex items-start gap-3 shadow-md" x-data="{ show: true }" x-show="show">
                <svg class="w-6 h-6 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="flex-1 text-sm font-medium">
                    @if(session('error'))
                        <div>{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                        <ul class="list-disc list-inside mt-1 space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <button type="button" @click="show = false" class="text-red-500 hover:text-red-800 text-lg font-bold">&times;</button>
            </div>
            @endif
        </div>

        @yield('content')
    </main>

    <!-- Footer Seguro -->
    <footer class="border-t border-cream-300 bg-white py-8 text-center text-sm text-cream-400">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2 font-heading font-bold text-brand-800 text-lg">
                    <span>Biblio<span class="text-accent-500">Tech</span></span>
                </div>
                <div class="text-xs text-cream-400">
                    &copy; {{ date('Y') }} <strong class="text-brand-800">BiblioTech</strong> · Sistema Integral de Gestión y Catálogo Bibliotecario.
                </div>
                <div class="flex items-center gap-4 text-xs font-semibold text-brand-800">
                    <a href="{{ route('catalogo') }}" class="hover:underline">Explorar Catálogo</a>
                    <span>&bull;</span>
                    <a href="{{ route('login') }}" class="hover:underline">Acceso al Sistema</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
