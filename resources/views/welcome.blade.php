<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIGAP - Sistem Informasi Gambaran Pasirjambu</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        .custom-div-icon { background: transparent; border: none; }
        .leaflet-popup-content-wrapper {
            padding: 0 !important;
            border-radius: 1.5rem !important;
            overflow: hidden !important;
            background: rgba(255, 255, 255, 0.96) !important;
            backdrop-filter: blur(16px) !important;
            -webkit-backdrop-filter: blur(16px) !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.18), 0 0 0 1px rgba(0, 0, 0, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.9) !important;
        }
        .leaflet-popup-content { margin: 0 !important; width: auto !important; }
        .leaflet-popup-tip-container { margin-top: -1px; }
        .leaflet-popup-tip { background: rgba(255, 255, 255, 0.96) !important; }
        .leaflet-container { font-family: 'Plus Jakarta Sans', sans-serif; }
        .leaflet-container a.bg-gradient-to-r,
        .leaflet-popup-content a[class*="bg-"],
        .leaflet-popup-content a[class*="bg-"] span,
        .leaflet-popup-content a[class*="bg-"] svg {
            color: #ffffff !important;
        }

        .pulse-marker::after {
            content: '';
            position: absolute;
            top: -4px; left: -4px; right: -4px; bottom: -4px;
            border-radius: 50%;
            background: inherit;
            animation: pulse 2s infinite ease-out;
            z-index: -1;
            opacity: 0.6;
        }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.6; }
            100% { transform: scale(2.5); opacity: 0; }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 50)">
    @include('components.page-loader')

    <!-- Navbar -->
    <nav :class="{'bg-white/80 backdrop-blur-md shadow-md py-4': scrolled, 'bg-transparent py-6': !scrolled}" class="fixed w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-kab-bandung.png') }}" alt="Logo Kabupaten Bandung" class="w-10 h-10 object-contain drop-shadow-md">
                <span class="text-2xl font-bold tracking-tight" :class="{'text-pj-green-800': scrolled, 'text-white': !scrolled}">SIGAP</span>
            </div>
            <div class="hidden md:flex items-center gap-8 font-medium">
                <a href="#beranda" :class="{'text-gray-700 hover:text-pj-green-600': scrolled, 'text-gray-100 hover:text-white': !scrolled}" class="transition-colors">Beranda</a>
                
                <!-- Dropdown Data -->
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" 
                             :class="{'text-gray-700 hover:text-pj-green-600': scrolled, 'text-gray-100 hover:text-white': !scrolled}" 
                             class="flex items-center gap-1 transition-colors font-medium">
                        Data
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         style="display: none;"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="absolute left-0 mt-3 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50 overflow-hidden">
                        
                        <a href="{{ route('public.desa') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-pj-green-50 hover:text-pj-green-700 transition-colors">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                Data Desa
                            </div>
                        </a>
                        <a href="{{ route('public.kopdes') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-pj-green-50 hover:text-pj-green-700 transition-colors">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                Data Kopdes
                            </div>
                        </a>
                        <a href="{{ route('public.umkm') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-pj-green-50 hover:text-pj-green-700 transition-colors">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-pj-gold-500"></div>
                                Data UMKM
                            </div>
                        </a>
                        <a href="{{ route('public.sppg') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-pj-green-50 hover:text-pj-green-700 transition-colors">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-purple-500"></div>
                                Data SPPG
                            </div>
                        </a>
                        <a href="{{ route('public.sekolah') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-pj-green-50 hover:text-pj-green-700 transition-colors">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-cyan-500"></div>
                                Data Sekolah
                            </div>
                        </a>
                    </div>
                </div>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-full bg-pj-gold-500 hover:bg-pj-gold-600 text-white font-semibold transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">Dashboard Admin</a>
                    @else
                        <a href="{{ route('login') }}" :class="{'text-gray-700 hover:text-pj-green-600': scrolled, 'text-gray-100 hover:text-white': !scrolled}" class="transition-colors">Login Admin</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="relative min-h-screen flex items-center justify-center pt-20">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="{{ $heroBg ?? 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80' }}" alt="Pasirjambu Nature" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-pj-green-900/90 to-pj-green-800/60 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-black/20"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <span class="inline-block py-1 px-3 rounded-full bg-pj-gold-500/20 text-pj-gold-400 border border-pj-gold-500/30 text-sm font-semibold tracking-wider mb-5 backdrop-blur-sm">
                KECAMATAN PASIRJAMBU
            </span>
            <h1 class="text-5xl md:text-7xl font-bold tracking-tight mb-6 drop-shadow-lg leading-tight">
                SIGAP <br> <span class="text-pj-gold-400">Sistem Informasi Gambaran Pasirjambu</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-200 mb-10 max-w-3xl mx-auto font-light leading-relaxed drop-shadow">
                Sistem Informasi Data Terpadu yang ada di Wilayah Kecamatan Pasirjambu, Kabupaten Bandung.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#statistik" class="px-8 py-4 rounded-full bg-pj-gold-500 hover:bg-pj-gold-600 text-white font-semibold transition-all shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                    Jelajahi Data
                </a>
                <a href="#peta" class="px-8 py-4 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/30 text-white font-semibold transition-all transform hover:-translate-y-1">
                    Lihat Peta Sebaran
                </a>
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
        </div>
    </section>

    <!-- Interactive Statistics Section -->
    <section id="statistik" class="py-24 bg-white relative z-20 -mt-8 rounded-t-[3rem] shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Profil Kecamatan dalam Angka</h2>
                <div class="w-24 h-1.5 bg-pj-gold-500 mx-auto rounded-full"></div>
            </div>

            <!-- Counter Alpine.js -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6" x-data="{ shown: false }" x-intersect="shown = true">
                <!-- Stat Item 1: Data Desa -->
                <a href="{{ route('public.desa') }}" class="bg-gray-50 rounded-2xl p-6 text-center border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group block">
                    <div class="w-14 h-14 mx-auto bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3 class="text-4xl font-bold text-gray-900 mb-2 font-mono">
                        <span x-data="counter({{ \App\Models\Desa::count() }})" x-show="shown" x-text="count">0</span>
                    </h3>
                    <p class="text-gray-500 text-sm font-medium">Data Desa</p>
                </a>

                <!-- Stat Item 2: Data Kopdes -->
                <a href="{{ route('public.kopdes') }}" class="bg-gray-50 rounded-2xl p-6 text-center border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group block cursor-pointer">
                    <div class="w-14 h-14 mx-auto bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-4xl font-bold text-gray-900 mb-2 font-mono">
                        <span x-data="counter({{ \App\Models\Kopdes::count() }})" x-show="shown" x-text="count">0</span>
                    </h3>
                    <p class="text-gray-500 text-sm font-medium group-hover:text-blue-600 transition-colors">Data Kopdes</p>
                </a>

                <!-- Stat Item 3: Data UMKM -->
                <a href="{{ route('public.umkm') }}" class="bg-gray-50 rounded-2xl p-6 text-center border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group block cursor-pointer">
                    <div class="w-14 h-14 mx-auto bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-4xl font-bold text-gray-900 mb-2 font-mono">
                        <span x-data="counter({{ \App\Models\Umkm::count() }})" x-show="shown" x-text="count">0</span>
                    </h3>
                    <p class="text-gray-500 text-sm font-medium group-hover:text-amber-600 transition-colors">Data UMKM</p>
                </a>

                <!-- Stat Item 4: Data SPPG -->
                <a href="{{ route('public.sppg') }}" class="bg-gray-50 rounded-2xl p-6 text-center border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group block">
                    <div class="w-14 h-14 mx-auto bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-4xl font-bold text-gray-900 mb-2 font-mono">
                        <span x-data="counter({{ \App\Models\Sppg::count() }})" x-show="shown" x-text="count">0</span>
                    </h3>
                    <p class="text-gray-500 text-sm font-medium">Data SPPG</p>
                </a>

                <!-- Stat Item 5: Data Sekolah -->
                <a href="{{ route('public.sekolah') }}" class="bg-gray-50 rounded-2xl p-6 text-center border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group block cursor-pointer col-span-2 sm:col-span-1">
                    <div class="w-14 h-14 mx-auto bg-cyan-100 text-cyan-600 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                    </div>
                    <h3 class="text-4xl font-bold text-gray-900 mb-2 font-mono">
                        <span x-data="counter({{ \App\Models\Sekolah::count() }})" x-show="shown" x-text="count">0</span>
                    </h3>
                    <p class="text-gray-500 text-sm font-medium group-hover:text-cyan-600 transition-colors">Data Sekolah</p>
                </a>
            </div>
        </div>
    </section>

    <!-- Map Section Mockup -->
    <section id="peta" class="py-24 bg-gray-50" x-data="welcomeMapData()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-12 items-center">
                <div class="w-full md:w-1/3">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Peta Sebaran Potensi</h2>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Visualisasi interaktif sebaran Data Desa, Koperasi Desa, UMKM, dan SPPG di seluruh wilayah Kecamatan Pasirjambu. Klik pilihan data di bawah untuk mengubah tampilan peta.
                    </p>
                    
                    <!-- Left Menu Buttons -->
                    <div class="space-y-3.5">
                        <!-- Data Desa -->
                        <div @click="switchTab('desa')" 
                             :class="activeTab === 'desa' ? 'bg-white border-2 border-emerald-500 shadow-md ring-2 ring-emerald-500/20 translate-x-1' : 'bg-white/80 border border-gray-100 hover:bg-white hover:shadow-sm'"
                             class="flex items-center justify-between p-4 rounded-xl cursor-pointer transition-all duration-200">
                            <div class="flex items-center gap-4">
                                <div class="w-4 h-4 rounded-full bg-emerald-500 shadow-lg"></div>
                                <span class="font-bold text-gray-800">Data Desa</span>
                            </div>
                            <span x-show="activeTab === 'desa'" class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">Aktif</span>
                        </div>

                        <!-- Data Kopdes -->
                        <div @click="switchTab('kopdes')" 
                             :class="activeTab === 'kopdes' ? 'bg-white border-2 border-blue-500 shadow-md ring-2 ring-blue-500/20 translate-x-1' : 'bg-white/80 border border-gray-100 hover:bg-white hover:shadow-sm'"
                             class="flex items-center justify-between p-4 rounded-xl cursor-pointer transition-all duration-200">
                            <div class="flex items-center gap-4">
                                <div class="w-4 h-4 rounded-full bg-blue-500 shadow-lg"></div>
                                <span class="font-bold text-gray-800">Data Kopdes</span>
                            </div>
                            <span x-show="activeTab === 'kopdes'" class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">Aktif</span>
                        </div>

                        <!-- Data UMKM -->
                        <div @click="switchTab('umkm')" 
                             :class="activeTab === 'umkm' ? 'bg-white border-2 border-amber-500 shadow-md ring-2 ring-amber-500/20 translate-x-1' : 'bg-white/80 border border-gray-100 hover:bg-white hover:shadow-sm'"
                             class="flex items-center justify-between p-4 rounded-xl cursor-pointer transition-all duration-200">
                            <div class="flex items-center gap-4">
                                <div class="w-4 h-4 rounded-full bg-pj-gold-500 shadow-lg"></div>
                                <span class="font-bold text-gray-800">Data UMKM</span>
                            </div>
                            <span x-show="activeTab === 'umkm'" class="text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full">Aktif</span>
                        </div>

                        <!-- Data SPPG -->
                        <div @click="switchTab('sppg')" 
                             :class="activeTab === 'sppg' ? 'bg-white border-2 border-purple-500 shadow-md ring-2 ring-purple-500/20 translate-x-1' : 'bg-white/80 border border-gray-100 hover:bg-white hover:shadow-sm'"
                             class="flex items-center justify-between p-4 rounded-xl cursor-pointer transition-all duration-200">
                            <div class="flex items-center gap-4">
                                <div class="w-4 h-4 rounded-full bg-purple-500 shadow-lg"></div>
                                <span class="font-bold text-gray-800">Data SPPG</span>
                            </div>
                            <span x-show="activeTab === 'sppg'" class="text-xs font-bold text-purple-600 bg-purple-50 px-2.5 py-1 rounded-full">Aktif</span>
                        </div>

                        <!-- Data Sekolah -->
                        <div @click="switchTab('sekolah')" 
                             :class="activeTab === 'sekolah' ? 'bg-white border-2 border-cyan-500 shadow-md ring-2 ring-cyan-500/20 translate-x-1' : 'bg-white/80 border border-gray-100 hover:bg-white hover:shadow-sm'"
                             class="flex items-center justify-between p-4 rounded-xl cursor-pointer transition-all duration-200">
                            <div class="flex items-center gap-4">
                                <div class="w-4 h-4 rounded-full bg-cyan-500 shadow-lg"></div>
                                <span class="font-bold text-gray-800">Data Sekolah</span>
                            </div>
                            <span x-show="activeTab === 'sekolah'" class="text-xs font-bold text-cyan-600 bg-cyan-50 px-2.5 py-1 rounded-full">Aktif</span>
                        </div>
                    </div>

                    <!-- Dynamic Action Link -->
                    <div class="mt-6">
                        <template x-if="activeTab === 'desa'">
                            <a href="{{ route('public.desa') }}" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-md transition-all">
                                <span>Buka Peta Tematik Desa Selengkapnya</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </template>
                        <template x-if="activeTab === 'sppg'">
                            <a href="{{ route('public.sppg') }}" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl shadow-md transition-all">
                                <span>Buka Peta Sebaran SPPG Selengkapnya</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </template>
                        <template x-if="activeTab === 'sekolah'">
                            <div class="space-y-3">
                                <div class="p-4 bg-cyan-50 border border-cyan-200 text-cyan-900 rounded-xl text-xs font-medium leading-relaxed">
                                    📍 Menampilkan persebaran Data Sekolah di kawasan Pasirjambu.
                                </div>
                                <a href="{{ route('public.sekolah') }}" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-cyan-600 hover:bg-cyan-700 text-white font-semibold rounded-xl shadow-md transition-all">
                                    <span>Buka Peta Sebaran Sekolah Selengkapnya</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </template>
                        <template x-if="activeTab === 'umkm'">
                            <div class="space-y-3">
                                <div class="p-4 bg-amber-50 border border-amber-200 text-amber-900 rounded-xl text-xs font-medium leading-relaxed">
                                    📍 Menampilkan gambaran potensi UMKM di wilayah Pasirjambu. Data detail spasial UMKM dapat diakses secara publik.
                                </div>
                                <a href="{{ route('public.umkm') }}" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl shadow-md transition-all">
                                    <span>Buka Peta Sebaran UMKM Selengkapnya</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </template>
                        <template x-if="activeTab === 'kopdes'">
                            <div class="space-y-3">
                                <div class="p-4 bg-blue-50 border border-blue-200 text-blue-900 rounded-xl text-xs font-medium leading-relaxed">
                                    📍 Menampilkan persebaran Koperasi Desa (Kopdes) di kawasan Pasirjambu.
                                </div>
                                <a href="{{ route('public.kopdes') }}" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md transition-all">
                                    <span>Buka Peta Sebaran Kopdes Selengkapnya</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="w-full md:w-2/3">
                    <!-- Glassmorphism Container for Map -->
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl bg-white/50 backdrop-blur-xl border border-white/40 p-2">
                        <div id="welcomeMap" class="w-full h-[500px] bg-white rounded-2xl relative overflow-hidden z-10"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-kab-bandung.png') }}" alt="Logo Kabupaten Bandung" class="w-8 h-8 object-contain drop-shadow-sm brightness-200">
                <span class="text-2xl font-bold text-white tracking-tight">SIGAP</span>
            </div>
            <p>&copy; {{ date('Y') }} Kecamatan Pasirjambu. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <!-- Alpine Counter & Map Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('counter', (target, duration = 1500) => ({
                count: 0,
                init() {
                    let start = 0;
                    let increment = target / (duration / 16); 
                    
                    let updateCounter = () => {
                        start += increment;
                        if (start < target) {
                            this.count = Math.ceil(start);
                            requestAnimationFrame(updateCounter);
                        } else {
                            this.count = target;
                        }
                    };

                    this.$watch('shown', value => {
                        if (value) requestAnimationFrame(updateCounter);
                    });
                },
                format(num) {
                    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }
            }));

            Alpine.data('welcomeMapData', () => {
                let map = null;
                let geojsonLayer = null;
                let markersGroup = [];

                return {
                    activeTab: 'sppg',
                    desas: @json($desas ?? []),
                    sppgs: @json($sppgs ?? []),
                    umkms: @json($umkms ?? []),
                    kopdes: @json($kopdes ?? []),
                    sekolahs: @json($sekolahs ?? []),
                    
                    init() {
                        this.initMap();
                    },

                    switchTab(tab) {
                        this.activeTab = tab;
                        this.updateMapDisplay();
                    },

                    getSppgCountForDesa(desaName) {
                        if (!desaName) return 0;
                        const cleanName = desaName.toLowerCase().replace(/^desa\s+/, '').trim();
                        return this.sppgs.filter(sppg => {
                            if (!sppg.desa || !sppg.desa.nama_desa) return false;
                            const sppgDesaName = sppg.desa.nama_desa.toLowerCase().replace(/^desa\s+/, '').trim();
                            return sppgDesaName === cleanName || cleanName.includes(sppgDesaName) || sppgDesaName.includes(cleanName);
                        }).length;
                    },

                    getDesaColor(desaName, tab) {
                        const desaData = this.desas.find(d => d.nama_desa.toLowerCase() === desaName.toLowerCase());
                        
                        if (tab === 'desa') {
                            const count = desaData ? parseInt(desaData.jumlah_penduduk) || 0 : 0;
                            if (count > 14000) return '#064e3b';
                            if (count > 11000) return '#047857';
                            if (count > 9000)  return '#10b981';
                            if (count > 7000)  return '#34d399';
                            return '#a7f3d0';
                        }

                        if (tab === 'sppg') {
                            const count = this.getSppgCountForDesa(desaName);
                            if (count >= 5) return '#047857'; // High Density
                            if (count >= 3) return '#10b981'; // Medium-High Density
                            if (count >= 2) return '#34d399'; // Medium Density
                            if (count >= 1) return '#a7f3d0'; // Low Density
                            return '#f1f5f9';                 // 0 SPPG
                        }
                        
                        if (tab === 'kopdes') {
                            return '#3b82f6';
                        }
                        
                        if (tab === 'umkm') {
                            return '#f59e0b';
                        }
                        
                        return '#10b981';
                    },

                    initMap() {
                        map = L.map('welcomeMap', {
                            zoomControl: false,
                            scrollWheelZoom: false,
                            dragging: !L.Browser.mobile, 
                            tap: !L.Browser.mobile
                        }).setView([-7.1233, 107.4735], 11);
                        
                        L.control.zoom({ position: 'topright' }).addTo(map);

                        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                            attribution: '&copy; OpenStreetMap &copy; CARTO'
                        }).addTo(map);

                        this.loadGeoJSON();
                    },

                    loadGeoJSON() {
                        fetch('/geojson/pasirjambu.geojson')
                            .then(response => response.json())
                            .then(data => {
                                geojsonLayer = L.geoJSON(data, {
                                    style: (feature) => {
                                        const desaName = feature.properties.NAMOBJ || '';
                                        const fillColor = this.getDesaColor(desaName, this.activeTab);
                                        const isDesaTab = this.activeTab === 'desa';

                                        return {
                                            color: isDesaTab ? '#047857' : fillColor,
                                            weight: isDesaTab ? 2 : 1,
                                            opacity: 0.8,
                                            fillColor: fillColor,
                                            fillOpacity: isDesaTab ? 0.65 : 0.2,
                                            dashArray: ''
                                        };
                                    },
                                    onEachFeature: (feature, layer) => {
                                        const desaName = feature.properties.NAMOBJ;
                                        
                                        layer.on({
                                            mouseover: (e) => {
                                                const l = e.target;
                                                l.setStyle({ fillOpacity: 0.75, weight: 3 });
                                            },
                                            mouseout: (e) => {
                                                if (geojsonLayer) {
                                                    geojsonLayer.resetStyle(e.target);
                                                }
                                            }
                                        });

                                        layer.bindTooltip(`Desa ${desaName}`, {
                                            sticky: true,
                                            className: 'bg-white/95 backdrop-blur-sm border border-gray-200 shadow-md font-semibold text-gray-800 px-3 py-1 rounded-full text-xs'
                                        });
                                    }
                                }).addTo(map);
                                
                                map.fitBounds(geojsonLayer.getBounds(), { padding: [20, 20], maxZoom: 13 });
                                this.updateMapDisplay();
                            })
                            .catch(err => {
                                console.error('Error loading geojson:', err);
                                this.updateMapDisplay();
                            });
                    },

                    updateMapDisplay() {
                        // Clear existing markers
                        markersGroup.forEach(m => map.removeLayer(m));
                        markersGroup = [];

                        // Update GeoJSON styling
                        if (geojsonLayer) {
                            geojsonLayer.setStyle((feature) => {
                                const desaName = feature.properties.NAMOBJ || '';
                                const fillColor = this.getDesaColor(desaName, this.activeTab);
                                const isDesaTab = this.activeTab === 'desa';

                                return {
                                    color: isDesaTab ? '#047857' : fillColor,
                                    weight: isDesaTab ? 2 : 1,
                                    opacity: 0.8,
                                    fillColor: fillColor,
                                    fillOpacity: isDesaTab ? 0.65 : 0.25
                                };
                            });
                        }

                        // Helper marker icon generator
                        const createPulseIcon = (color) => {
                            return L.divIcon({
                                className: 'custom-div-icon',
                                html: `<div class="pulse-marker" style="background-color:${color}; width:16px; height:16px; border-radius:50%; border:2px solid white; box-shadow:0 2px 5px rgba(0,0,0,0.3); position:relative;"></div>`,
                                iconSize: [16, 16],
                                iconAnchor: [8, 8]
                            });
                        };

                        if (this.activeTab === 'sppg') {
                            this.sppgs.forEach(sppg => {
                                if (sppg.koordinat_lokasi && sppg.koordinat_lokasi.includes(',')) {
                                    const parts = sppg.koordinat_lokasi.split(',');
                                    const lat = parseFloat(parts[0].trim());
                                    const lng = parseFloat(parts[1].trim());
                                    
                                    if (!isNaN(lat) && !isNaN(lng)) {
                                        const popupContent = `
                                            <div class="p-4 min-w-[230px] max-w-[270px]">
                                                <div class="flex items-center justify-between mb-2.5">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-50 text-purple-700 border border-purple-200 shadow-sm">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500 animate-pulse"></span>
                                                        ${sppg.status ? sppg.status.toUpperCase() : 'SPPG'}
                                                    </span>
                                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">PASIRJAMBU</span>
                                                </div>

                                                <h4 class="font-extrabold text-gray-900 text-base leading-tight mb-1.5 tracking-tight">${sppg.nama_sppg}</h4>
                                                <p class="text-xs text-gray-500 mb-3 font-medium truncate">${sppg.desa ? sppg.desa.nama_desa : '-'}</p>

                                                 <a href="/sebaran-sppg" style="color: #ffffff !important;" class="w-full py-2.5 px-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl text-xs font-extrabold tracking-wide shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-1.5 transform hover:scale-[1.02] active:scale-[0.98]">
                                                     <span style="color: #ffffff !important;">Buka Peta Penuh</span>
                                                     <svg class="w-3.5 h-3.5" style="color: #ffffff !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                 </a>
                                            </div>
                                        `;

                                        const m = L.marker([lat, lng], { 
                                            icon: createPulseIcon('#a855f7')
                                        })
                                        .bindPopup(popupContent, {
                                            offset: [0, -8],
                                            closeButton: false,
                                            className: 'rounded-xl border-none shadow-xl'
                                        })
                                        .addTo(map);

                                        m.on('mouseover', function() { this.openPopup(); });

                                        markersGroup.push(m);
                                    }
                                }
                            });
                        }

                        if (this.activeTab === 'umkm') {
                            this.umkms.forEach(umkm => {
                                if (umkm.koordinat_lokasi && umkm.koordinat_lokasi.includes(',')) {
                                    const parts = umkm.koordinat_lokasi.split(',');
                                    const lat = parseFloat(parts[0].trim());
                                    const lng = parseFloat(parts[1].trim());
                                    
                                    if (!isNaN(lat) && !isNaN(lng)) {
                                        let iconColor = '#f59e0b'; // Amber/Kuliner
                                        if (umkm.kategori === 'Kreatif') iconColor = '#8b5cf6';
                                        if (umkm.kategori === 'Fashion') iconColor = '#f43f5e';

                                        const popupContent = `
                                            <div class="p-4 min-w-[240px] max-w-[280px]">
                                                <div class="flex items-center justify-between mb-2.5">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm"
                                                          style="background-color: ${iconColor}15; color: ${iconColor}; border: 1px solid ${iconColor}40;">
                                                        <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background-color: ${iconColor}"></span>
                                                        ${umkm.kategori || 'UMKM'}
                                                    </span>
                                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">PASIRJAMBU</span>
                                                </div>

                                                <h4 class="font-extrabold text-gray-900 text-base leading-tight mb-2.5 tracking-tight">${umkm.nama_umkm}</h4>
                                                
                                                <div class="space-y-1.5 mb-4 text-xs text-gray-600 font-medium">
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                        <span class="truncate">Pemilik: <strong class="text-gray-900 font-semibold">${umkm.nama_pemilik || 'Anonim'}</strong></span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                        <span class="truncate">Desa: <strong class="text-gray-900 font-semibold">${umkm.desa ? umkm.desa.nama_desa : '-'}</strong></span>
                                                    </div>
                                                </div>

                                                 <a href="/sebaran-umkm" style="color: #ffffff !important;" class="w-full py-2.5 px-4 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-xl text-xs font-extrabold tracking-wide shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-1.5 transform hover:scale-[1.02] active:scale-[0.98]">
                                                     <span style="color: #ffffff !important;">Buka Peta UMKM</span>
                                                     <svg class="w-3.5 h-3.5" style="color: #ffffff !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                 </a>
                                            </div>
                                        `;

                                        const m = L.marker([lat, lng], { 
                                            icon: createPulseIcon(iconColor)
                                        })
                                        .bindPopup(popupContent, {
                                            offset: [0, -8],
                                            closeButton: false,
                                            className: 'rounded-xl border-none shadow-xl'
                                        })
                                        .addTo(map);

                                        m.on('mouseover', function() { this.openPopup(); });

                                        markersGroup.push(m);
                                    }
                                }
                            });
                        }

                        if (this.activeTab === 'kopdes') {
                            this.kopdes.forEach(k => {
                                if (k.koordinat_lokasi && k.koordinat_lokasi.includes(',')) {
                                    const parts = k.koordinat_lokasi.split(',');
                                    const lat = parseFloat(parts[0].trim());
                                    const lng = parseFloat(parts[1].trim());
                                    
                                    if (!isNaN(lat) && !isNaN(lng)) {
                                        const popupContent = `
                                            <div class="p-4 min-w-[240px] max-w-[280px]">
                                                <div class="flex items-center justify-between mb-2.5">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200 shadow-sm">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                                        KOPERASI DESA
                                                    </span>
                                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">PASIRJAMBU</span>
                                                </div>

                                                <h4 class="font-extrabold text-gray-900 text-base leading-tight mb-2 tracking-tight">${k.nama_kopdes}</h4>
                                                
                                                <div class="space-y-1 mb-4 text-xs text-gray-600 font-medium">
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-3.5 h-3.5 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                        <span class="truncate">Ketua: <strong class="text-gray-900 font-semibold">${k.ketua_kopdes || '-'}</strong></span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                        <span class="truncate">Desa: <strong class="text-gray-900 font-semibold">${k.desa ? k.desa.nama_desa : '-'}</strong></span>
                                                    </div>
                                                </div>

                                                 <a href="/sebaran-kopdes" style="color: #ffffff !important;" class="w-full py-2.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl text-xs font-extrabold tracking-wide shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-1.5 transform hover:scale-[1.02] active:scale-[0.98]">
                                                     <span style="color: #ffffff !important;">Buka Peta Kopdes</span>
                                                     <svg class="w-3.5 h-3.5" style="color: #ffffff !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                 </a>
                                            </div>
                                        `;

                                        const m = L.marker([lat, lng], { 
                                            icon: createPulseIcon('#3b82f6')
                                        })
                                        .bindPopup(popupContent, {
                                            offset: [0, -8],
                                            closeButton: false,
                                            className: 'rounded-xl border-none shadow-xl'
                                        })
                                        .addTo(map);

                                        m.on('mouseover', function() { this.openPopup(); });

                                        markersGroup.push(m);
                                    }
                                }
                            });
                        }

                        if (this.activeTab === 'sekolah') {
                            this.sekolahs.forEach(s => {
                                if (s.koordinat_lokasi && s.koordinat_lokasi.includes(',')) {
                                    const parts = s.koordinat_lokasi.split(',');
                                    const lat = parseFloat(parts[0].trim());
                                    const lng = parseFloat(parts[1].trim());
                                    
                                    if (!isNaN(lat) && !isNaN(lng)) {
                                        const popupContent = `
                                            <div class="p-4 min-w-[240px] max-w-[280px]">
                                                <div class="flex items-center justify-between mb-2.5">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-cyan-50 text-cyan-700 border border-cyan-200 shadow-sm">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 animate-pulse"></span>
                                                        SEKOLAH
                                                    </span>
                                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">PASIRJAMBU</span>
                                                </div>

                                                <h4 class="font-extrabold text-gray-900 text-base leading-tight mb-2 tracking-tight">${s.nama_sekolah}</h4>
                                                
                                                <div class="space-y-1 mb-4 text-xs text-gray-600 font-medium">
                                                    ${s.npsn ? `<div class="flex items-center gap-2"><span class="text-cyan-700 font-bold">NPSN: ${s.npsn}</span></div>` : ''}
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                        <span class="truncate">Desa: <strong class="text-gray-900 font-semibold">${s.desa ? s.desa.nama_desa : '-'}</strong></span>
                                                    </div>
                                                </div>

                                                 <a href="/sebaran-sekolah" style="color: #ffffff !important;" class="w-full py-2.5 px-4 bg-gradient-to-r from-cyan-600 to-teal-600 hover:from-cyan-700 hover:to-teal-700 text-white rounded-xl text-xs font-extrabold tracking-wide shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-1.5 transform hover:scale-[1.02] active:scale-[0.98]">
                                                     <span style="color: #ffffff !important;">Buka Peta Sekolah</span>
                                                     <svg class="w-3.5 h-3.5" style="color: #ffffff !important;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                 </a>
                                            </div>
                                        `;

                                        const m = L.marker([lat, lng], { 
                                            icon: createPulseIcon('#0891b2')
                                        })
                                        .bindPopup(popupContent, {
                                            offset: [0, -8],
                                            closeButton: false,
                                            className: 'rounded-xl border-none shadow-xl'
                                        })
                                        .addTo(map);

                                        m.on('mouseover', function() { this.openPopup(); });

                                        markersGroup.push(m);
                                    }
                                }
                            });
                        }
                    }
                };
            });
        });
    </script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</body>
</html>
