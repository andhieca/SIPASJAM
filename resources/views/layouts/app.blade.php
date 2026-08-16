<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>SIGAP - Admin Panel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900" x-data="{ sidebarOpen: false }">
        @include('components.page-loader')
        <div class="flex h-screen overflow-hidden">
            
            <!-- Mobile sidebar backdrop -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-gray-900 bg-opacity-50 transition-opacity lg:hidden" style="display: none;"></div>

            <!-- Sidebar -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-72 bg-white shadow-xl lg:static lg:inset-0 lg:translate-x-0 transition-transform duration-300 ease-in-out border-r border-gray-100 flex flex-col">
                <!-- Logo -->
                <div class="flex items-center justify-center h-24 border-b border-gray-100 px-6">
                    <img src="{{ asset('images/logo-kab-bandung.png') }}" alt="Logo" class="w-12 h-12 object-contain mr-3">
                    <div>
                        <h1 class="text-2xl font-bold text-pj-green-800 tracking-tight">SIGAP</h1>
                        <p class="text-xs text-gray-500 font-medium tracking-wider">KECAMATAN PASIRJAMBU</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                    <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 mt-2">Menu Utama</p>
                    
                    <a href="{{ route('dashboard') }}" class="group flex items-center text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-pj-green-50 text-pj-green-800 font-bold shadow-sm border-l-4 border-pj-green-600 pl-3 pr-4 py-3' : 'text-gray-600 hover:bg-gray-50 hover:text-pj-green-700 font-medium px-4 py-3' }}">
                        <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('dashboard') ? 'text-pj-green-600' : 'text-gray-400 group-hover:text-pj-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        Dashboard
                    </a>

                    <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 mt-6">Manajemen Data</p>

                    <a href="{{ route('desa.index') }}" class="group flex items-center text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('desa.*') ? 'bg-pj-green-50 text-pj-green-800 font-bold shadow-sm border-l-4 border-pj-green-600 pl-3 pr-4 py-3' : 'text-gray-600 hover:bg-gray-50 hover:text-pj-green-700 font-medium px-4 py-3' }}">
                        <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('desa.*') ? 'text-pj-green-600' : 'text-gray-400 group-hover:text-pj-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Data Desa
                    </a>

                    <a href="{{ route('kopdes.index') }}" class="group flex items-center text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('kopdes.*') ? 'bg-pj-green-50 text-pj-green-800 font-bold shadow-sm border-l-4 border-pj-green-600 pl-3 pr-4 py-3' : 'text-gray-600 hover:bg-gray-50 hover:text-pj-green-700 font-medium px-4 py-3' }}">
                        <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('kopdes.*') ? 'text-pj-green-600' : 'text-gray-400 group-hover:text-pj-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Data Kopdes
                    </a>

                    <a href="{{ route('umkm.index') }}" class="group flex items-center text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('umkm.*') ? 'bg-pj-green-50 text-pj-green-800 font-bold shadow-sm border-l-4 border-pj-green-600 pl-3 pr-4 py-3' : 'text-gray-600 hover:bg-gray-50 hover:text-pj-green-700 font-medium px-4 py-3' }}">
                        <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('umkm.*') ? 'text-pj-green-600' : 'text-gray-400 group-hover:text-pj-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Data UMKM
                    </a>
                    
                    <a href="{{ route('sppg.index') }}" class="group flex items-center text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('sppg.*') ? 'bg-pj-green-50 text-pj-green-800 font-bold shadow-sm border-l-4 border-pj-green-600 pl-3 pr-4 py-3' : 'text-gray-600 hover:bg-gray-50 hover:text-pj-green-700 font-medium px-4 py-3' }}">
                        <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('sppg.*') ? 'text-pj-green-600' : 'text-gray-400 group-hover:text-pj-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Data SPPG
                    </a>

                    <a href="{{ route('sekolah.index') }}" class="group flex items-center text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('sekolah.*') ? 'bg-pj-green-50 text-pj-green-800 font-bold shadow-sm border-l-4 border-pj-green-600 pl-3 pr-4 py-3' : 'text-gray-600 hover:bg-gray-50 hover:text-pj-green-700 font-medium px-4 py-3' }}">
                        <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('sekolah.*') ? 'text-pj-green-600' : 'text-gray-400 group-hover:text-pj-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                        Data Sekolah
                    </a>

                    <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 mt-6">Sistem</p>

                    <a href="{{ route('admin.settings.index') }}" class="group flex items-center text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-pj-green-50 text-pj-green-800 font-bold shadow-sm border-l-4 border-pj-green-600 pl-3 pr-4 py-3' : 'text-gray-600 hover:bg-gray-50 hover:text-pj-green-700 font-medium px-4 py-3' }}">
                        <svg class="w-5 h-5 mr-3 shrink-0 transition-colors {{ request()->routeIs('admin.settings.*') ? 'text-pj-green-600' : 'text-gray-400 group-hover:text-pj-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Pengaturan Aplikasi
                    </a>
                </nav>

                <!-- User Account Profile in Sidebar -->
                <div class="p-4 border-t border-gray-100">
                    <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 rounded-full bg-pj-gold-500 text-white flex items-center justify-center font-bold text-lg shadow-inner">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Log Out
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content wrapper -->
            <div class="flex-1 flex flex-col overflow-hidden relative">
                
                <!-- Top Navbar -->
                <header class="bg-white/80 backdrop-blur-md border-b border-gray-100 h-20 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-10 sticky top-0 no-print">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden mr-4 p-2 rounded-md hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        
                        @isset($header)
                            <div class="text-gray-800">
                                {{ $header }}
                            </div>
                        @endisset
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="/" target="_blank" class="text-sm text-pj-green-600 font-medium hover:text-pj-green-700 flex items-center gap-1 bg-pj-green-50 px-3 py-1.5 rounded-full transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Lihat Website
                        </a>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50/50 p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
        
        @stack('scripts')
    </body>
</html>
