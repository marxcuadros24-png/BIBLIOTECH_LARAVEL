<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'BiblioTech' }} · Sistema de Gestión Bibliotecaria</title>

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
<body class="h-full bg-cream-100 text-cream-900 antialiased flex flex-col" x-data="{ mobileMenu: false, userDropdown: false }">

    <!-- Header Principal -->
    <header class="sticky top-0 z-40 bg-brand-800 text-white shadow-md border-b border-brand-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Logo y Brand -->
                <div class="flex items-center gap-3">
                    <button type="button" @click="mobileMenu = !mobileMenu" class="md:hidden p-2 rounded-lg text-brand-200 hover:text-white hover:bg-brand-700 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <a href="{{ route('inicio') }}" class="flex items-center gap-2 group" title="Ir a la página de inicio">
                        <div class="w-10 h-10 rounded-xl bg-accent-400 text-brand-900 flex items-center justify-center font-bold text-xl shadow-glow group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6 text-brand-950" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                        <div>
                            <span class="font-heading font-bold text-xl text-white tracking-tight">Biblio<span class="text-accent-400">Tech</span></span>
                            <span class="hidden sm:inline-block text-xs text-brand-200 ml-2 px-2 py-0.5 rounded bg-brand-900/60 font-medium">Gestión Bibliotecaria</span>
                        </div>
                    </a>
                </div>

                <!-- Enlaces rápidos y Usuario -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('catalogo') }}" target="_blank" class="hidden sm:flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full bg-brand-900/70 text-accent-300 hover:bg-brand-700 hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Catálogo Público
                    </a>

                    @auth
                    <!-- Menú de Usuario -->
                    <div class="relative" @click.away="userDropdown = false">
                        <button type="button" @click="userDropdown = !userDropdown" class="flex items-center gap-2.5 p-1.5 pl-3 rounded-full bg-brand-900/60 hover:bg-brand-700/80 transition-all border border-brand-700/60 focus:outline-none">
                            <div class="text-right hidden sm:block">
                                <div class="text-xs font-medium text-white">{{ Auth::user()->nombre }}</div>
                                <div class="text-[10px] uppercase font-bold text-accent-400 tracking-wider">
                                    {{ Auth::user()->rol }}
                                </div>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-accent-400 text-brand-900 font-bold flex items-center justify-center text-sm shadow">
                                {{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}
                            </div>
                            <svg class="w-4 h-4 text-brand-300 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Dropdown -->
                        <div x-show="userDropdown" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-56 rounded-xl bg-white shadow-xl py-2 z-50 border border-cream-300 text-cream-900">
                            <div class="px-4 py-2 border-b border-cream-200">
                                <div class="text-xs text-cream-400">Sesión iniciada como</div>
                                <div class="text-sm font-bold text-brand-800 truncate">{{ Auth::user()->email }}</div>
                                <span class="inline-block mt-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ Auth::user()->esAdmin() ? 'bg-purple-100 text-purple-800' : (Auth::user()->esBibliotecario() ? 'bg-blue-100 text-blue-800' : 'bg-emerald-100 text-emerald-800') }}">
                                    ROL: {{ strtoupper(Auth::user()->rol) }}
                                </span>
                            </div>
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-cream-900 hover:bg-cream-200/60 font-medium">
                                Mi Panel
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="mt-1 pt-1 border-t border-cream-200">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                    @endauth
                </div>

            </div>
        </div>
    </header>

    <!-- Contenedor con Sidebar y Contenido -->
    <div class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col md:flex-row gap-6">

        <!-- Sidebar de Navegación -->
        <aside class="w-full md:w-64 shrink-0" :class="{ 'block': mobileMenu, 'hidden md:block': !mobileMenu }">
            <div class="bg-white rounded-2xl shadow-soft border border-cream-300 p-4 sticky top-24">
                <nav class="space-y-1">
                    
                    <!-- Panel -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-brand-800 text-white shadow-md' : 'text-cream-900 hover:bg-cream-200/80 hover:text-brand-800' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span>Panel Principal</span>
                    </a>

                    <!-- Libros -->
                    @if(Auth::user()->tienePermiso('libros', 'ver'))
                    <a href="{{ Auth::user()->esEstudiante() ? route('catalogo') : route('libros.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ (request()->routeIs('libros.*') || request()->routeIs('catalogo')) ? 'bg-brand-800 text-white shadow-md' : 'text-cream-900 hover:bg-cream-200/80 hover:text-brand-800' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <span>Catálogo de Libros</span>
                    </a>
                    @endif

                    <!-- Categorías (Oculto para Estudiantes) -->
                    @if(!Auth::user()->esEstudiante() && Auth::user()->tienePermiso('categorias', 'ver'))
                    <a href="{{ route('categorias.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('categorias.*') ? 'bg-brand-800 text-white shadow-md' : 'text-cream-900 hover:bg-cream-200/80 hover:text-brand-800' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        <span>Categorías</span>
                    </a>
                    @endif

                                        <!-- Configuración (Solo Administrador) -->
                    @if(Auth::user()->esAdmin())
                    <a href="{{ route('configuracion.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('configuracion.*') ? 'bg-brand-800 text-white shadow-md' : 'text-cream-900 hover:bg-cream-200/80 hover:text-brand-800' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Configuración</span>
                    </a>
                    @endif

                    <!-- Préstamos -->
                    <a href="{{ route('prestamos.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('prestamos.*') ? 'bg-brand-800 text-white shadow-md' : 'text-cream-900 hover:bg-cream-200/80 hover:text-brand-800' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>{{ Auth::user()->esEstudiante() ? 'Mis Préstamos' : 'Gestión de Préstamos' }}</span>
                    </a>

                    <!-- Multas -->
                    <a href="{{ route('multas.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('multas.*') ? 'bg-brand-800 text-white shadow-md' : 'text-cream-900 hover:bg-cream-200/80 hover:text-brand-800' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ Auth::user()->esEstudiante() ? 'Mis Multas' : 'Caja y Multas' }}</span>
                    </a>

                    <!-- Reservas -->
                    <a href="{{ route('reservas.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('reservas.*') ? 'bg-brand-800 text-white shadow-md' : 'text-cream-900 hover:bg-cream-200/80 hover:text-brand-800' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                        <span>{{ Auth::user()->esEstudiante() ? 'Mis Reservas' : 'Reservas de Libros' }}</span>
                    </a>

                    <!-- Usuarios (Solo Administrador) -->
                    @if(Auth::user()->esAdmin())
                    <div class="pt-3 mt-3 border-t border-cream-200">
                        <div class="px-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-cream-400">Administración</div>
                        <a href="{{ route('usuarios.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('usuarios.*') ? 'bg-brand-800 text-white shadow-md' : 'text-cream-900 hover:bg-cream-200/80 hover:text-brand-800' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span>Usuarios y Roles</span>
                        </a>

                        <a href="{{ route('dashboard.reporte') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('dashboard.reporte') ? 'bg-brand-800 text-white shadow-md' : 'text-cream-900 hover:bg-cream-200/80 hover:text-brand-800' }}">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <span>Reporte Financiero</span>
                        </a>
                    </div>
                    @endif

                </nav>

                <!-- Tarjeta de Reglas de Préstamo -->
                <div class="mt-6 p-3 rounded-xl bg-accent-50 border border-accent-200 text-accent-800 text-xs">
                    <div class="font-bold flex items-center gap-1.5 text-accent-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Reglas del Sistema
                    </div>
                    <ul class="mt-1.5 space-y-1 text-[11px] list-disc list-inside text-accent-900/80">
                        <li>Plazo estándar: <strong>{{ config('biblioteca.dias_prestamo', 7) }} días</strong></li>
                        <li>Tarifa mora: <strong>{{ config('biblioteca.moneda', 'S/') }} {{ number_format(config('biblioteca.multa_por_dia', 1.50), 2) }} / día</strong></li>
                        <li>Bloqueo automático si hay mora</li>
                    </ul>
                </div>
            </div>
        </aside>

        <!-- Área de Contenido Principal -->
        <main class="flex-1 min-w-0">

            <!-- Mensajes Flash de Sesión -->
            @if(session('exito'))
            <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3 shadow-sm animate-fade-in" x-data="{ show: true }" x-show="show">
                <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="flex-1 text-sm font-medium">{{ session('exito') }}</div>
                <button type="button" @click="show = false" class="text-emerald-500 hover:text-emerald-700">&times;</button>
            </div>
            @endif

            @if(session('error') || $errors->any())
            <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 flex items-start gap-3 shadow-sm" x-data="{ show: true }" x-show="show">
                <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
                <button type="button" @click="show = false" class="text-red-500 hover:text-red-700">&times;</button>
            </div>
            @endif

            <!-- Contenido específico de la página -->
            @yield('content')

        </main>
    </div>

    <!-- Footer Seguro -->
    <footer class="mt-auto border-t border-cream-300 bg-white py-6 text-center text-xs text-cream-400">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>
                <strong>BiblioTech</strong> &copy; {{ date('Y') }} · Plataforma Integral de Préstamos y Biblioteca.
            </div>
            <div class="flex items-center gap-4 text-brand-800 font-semibold">
                <a href="{{ route('catalogo') }}" class="hover:underline">Catálogo Público</a>
                <span>&bull;</span>
                <span class="text-cream-400 font-normal">Ambiente Protegido</span>
            </div>
        </div>
    </footer>

</body>
</html>
