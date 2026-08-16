<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Sebaran UMKM - Kecamatan Pasirjambu | SIGAP</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
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
        
        /* Pulse Marker Animation */
        .pulse-marker::after {
            content: '';
            position: absolute;
            top: -5px; left: -5px; right: -5px; bottom: -5px;
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

        /* 3D Coverflow Perspective Gallery */
        .perspective-1000 { perspective: 1000px; }
        .rotate-y-left { transform: rotateY(25deg) scale(0.85); transform-style: preserve-3d; }
        .rotate-y-right { transform: rotateY(-25deg) scale(0.85); transform-style: preserve-3d; }

        /* Running Product Catalogue Marquee */
        @keyframes marquee {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee {
            display: flex;
            width: max-content;
            animation: marquee 35s linear infinite;
        }
        .animate-marquee:hover {
            animation-play-state: paused;
        }
        .mask-linear {
            mask-image: linear-gradient(to right, transparent 0%, black 4%, black 96%, transparent 100%);
            -webkit-mask-image: linear-gradient(to right, transparent 0%, black 4%, black 96%, transparent 100%);
        }
    </style>
</head>
<body class="h-screen w-screen flex flex-col-reverse md:flex-row relative bg-gray-50 overflow-hidden" x-data="umkmMapData()" @open-umkm-modal.window="openModal($event.detail)" @keydown.window.escape="closeGallery()" @keydown.window.arrow-right="showGalleryModal && nextPhoto()" @keydown.window.arrow-left="showGalleryModal && prevPhoto()">
    @include('components.page-loader')

    <!-- Sidebar (Left on Desktop, Bottom on Mobile) -->
    <aside style="background-color: #f8fafc;" class="w-full md:w-[420px] h-[55%] md:h-full flex flex-col border-t md:border-t-0 md:border-r border-gray-200 shadow-[0_-10px_30px_rgba(0,0,0,0.05)] md:shadow-[10px_0_30px_rgba(0,0,0,0.08)] relative z-20 shrink-0">
        
        <!-- Header -->
        <div class="p-6 pb-4 flex items-center justify-between border-b border-gray-100 bg-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 flex items-center justify-center shrink-0">
                    <img src="{{ asset('images/logo-kab-bandung.png') }}" alt="Logo Kabupaten Bandung" class="w-full h-full object-contain drop-shadow-md">
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-900 tracking-tight">SISTEM INFORMASI UMKM</h1>
                    <p class="text-[11px] text-amber-600 font-medium">Kecamatan Pasirjambu • Real-time</p>
                </div>
            </div>
            <a href="{{ url('/') }}" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900 transition-colors shadow-inner" title="Kembali ke Beranda">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="px-5 py-3.5 grid grid-cols-2 sm:grid-cols-4 gap-2 border-b border-gray-100 bg-[#f8fafc]">
            <!-- Total -->
            <div @click="selectedKategori = ''" 
                 :class="selectedKategori === '' ? 'ring-2 ring-gray-400 bg-white shadow-md' : 'bg-white hover:bg-gray-50'" 
                 class="rounded-xl p-2.5 text-center border border-gray-200 shadow-sm transition-all cursor-pointer select-none">
                <p class="text-[9px] text-gray-500 font-extrabold uppercase tracking-wider mb-0.5">Total UMKM</p>
                <p class="text-xl font-black text-gray-900" x-text="filteredUmkms.length">0</p>
            </div>
            <!-- Kuliner -->
            <div @click="selectedKategori = selectedKategori === 'Kuliner' ? '' : 'Kuliner'" 
                 :class="selectedKategori === 'Kuliner' ? 'ring-2 ring-amber-500 bg-amber-50 shadow-md' : 'bg-white hover:bg-amber-50/40'" 
                 class="rounded-xl p-2.5 text-center border border-gray-200 shadow-sm transition-all cursor-pointer select-none">
                <p class="text-[9px] text-amber-700 font-extrabold uppercase tracking-wider mb-0.5">Kuliner</p>
                <p class="text-xl font-black text-amber-600" x-text="totalFilteredKuliner">0</p>
            </div>
            <!-- Kreatif -->
            <div @click="selectedKategori = selectedKategori === 'Kreatif' ? '' : 'Kreatif'" 
                 :class="selectedKategori === 'Kreatif' ? 'ring-2 ring-purple-500 bg-purple-50 shadow-md' : 'bg-white hover:bg-purple-50/40'" 
                 class="rounded-xl p-2.5 text-center border border-gray-200 shadow-sm transition-all cursor-pointer select-none">
                <p class="text-[9px] text-purple-700 font-extrabold uppercase tracking-wider mb-0.5">Kreatif</p>
                <p class="text-xl font-black text-purple-600" x-text="totalFilteredKreatif">0</p>
            </div>
            <!-- Fashion -->
            <div @click="selectedKategori = selectedKategori === 'Fashion' ? '' : 'Fashion'" 
                 :class="selectedKategori === 'Fashion' ? 'ring-2 ring-rose-500 bg-rose-50 shadow-md' : 'bg-white hover:bg-rose-50/40'" 
                 class="rounded-xl p-2.5 text-center border border-gray-200 shadow-sm transition-all cursor-pointer select-none">
                <p class="text-[9px] text-rose-700 font-extrabold uppercase tracking-wider mb-0.5">Fashion</p>
                <p class="text-xl font-black text-rose-600" x-text="totalFilteredFashion">0</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="p-6 border-b border-gray-100 space-y-4">
            <!-- Search -->
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400 group-focus-within:text-amber-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" x-model="searchQuery" placeholder="Cari UMKM, Pemilik..." class="block w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl leading-5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 sm:text-sm transition-all shadow-sm">
            </div>

            <!-- Selects -->
            <div class="grid grid-cols-2 gap-3">
                <div class="relative group">
                    <label class="block text-[10px] text-gray-500 font-semibold uppercase tracking-wider mb-1.5 ml-1">Desa / Kelurahan</label>
                    <select x-model="selectedDesa" class="block w-full py-2.5 pl-3 pr-8 bg-white border border-gray-200 text-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:border-amber-500 sm:text-sm shadow-sm transition-all cursor-pointer">
                        <option value="">Semua Desa</option>
                        @foreach($desas as $desa)
                            <option value="{{ $desa->id }}">{{ $desa->nama_desa }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="relative group">
                    <label class="block text-[10px] text-gray-500 font-semibold uppercase tracking-wider mb-1.5 ml-1">Kategori Usaha</label>
                    <select x-model="selectedKategori" class="block w-full py-2.5 pl-3 pr-8 bg-white border border-gray-200 text-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:border-amber-500 sm:text-sm shadow-sm transition-all cursor-pointer">
                        <option value="">Semua Kategori</option>
                        <option value="Kuliner">Kuliner</option>
                        <option value="Kreatif">Kreatif</option>
                        <option value="Fashion">Fashion</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- List -->
        <div class="flex-1 overflow-y-auto p-5 space-y-4 relative z-0">
            
            <template x-if="filteredUmkms.length === 0">
                <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-200 shadow-sm">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Tidak ada data UMKM yang sesuai dengan filter.</p>
                </div>
            </template>

            <template x-for="(umkm, index) in filteredUmkms" :key="umkm.id">
                <div @click="focusMarker(umkm)" class="group cursor-pointer bg-white border border-gray-100 rounded-2xl p-5 hover:border-amber-400 hover:shadow-[0_10px_40px_-10px_rgba(245,158,11,0.2)] transition-all duration-300 relative overflow-hidden transform hover:-translate-y-1">
                    
                    <div class="flex justify-between items-start mb-3 relative z-10">
                        <div class="pr-3">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold tracking-wider mb-2.5 shadow-sm"
                                  :class="umkm.kategori === 'Kuliner' ? 'bg-amber-50 text-amber-700 border border-amber-200' : (umkm.kategori === 'Kreatif' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-rose-50 text-rose-700 border border-rose-200')">
                                <span class="w-1.5 h-1.5 rounded-full" :class="umkm.kategori === 'Kuliner' ? 'bg-amber-500' : (umkm.kategori === 'Kreatif' ? 'bg-purple-500' : 'bg-rose-500')"></span>
                                <span x-text="(umkm.kategori || 'Kuliner').toUpperCase()"></span>
                            </span>
                            <h3 class="font-bold text-gray-900 text-[15px] leading-snug transition-colors group-hover:text-amber-600" x-text="umkm.nama_umkm"></h3>
                            <p class="text-[13px] text-gray-500 mt-1 font-medium" x-text="'Pemilik: ' + (umkm.nama_pemilik || '-')"></p>
                        </div>

                        <!-- Thumbnail -->
                        <div class="w-12 h-12 rounded-xl overflow-hidden bg-amber-50 border border-amber-100 shrink-0 relative">
                            <template x-if="umkm.foto_produk && umkm.foto_produk.length > 0">
                                <img :src="'/storage/' + umkm.foto_produk[0]" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" alt="Foto UMKM">
                            </template>
                            <template x-if="!umkm.foto_produk || umkm.foto_produk.length === 0">
                                <div class="w-full h-full flex items-center justify-center text-amber-500 font-bold text-[10px]">UMKM</div>
                            </template>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100 text-xs text-gray-500 relative z-10">
                        <div class="flex items-center gap-1.5 truncate pr-2 group-hover:text-gray-700 transition-colors">
                            <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="truncate font-medium" x-text="umkm.desa ? umkm.desa.nama_desa.toUpperCase() : '-'"></span>
                        </div>
                        <button @click.stop="openModal(umkm.id)" class="text-xs font-bold text-amber-600 hover:text-amber-700 bg-amber-50 px-2.5 py-1 rounded-lg transition-colors">
                            Detail &rarr;
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="p-3 border-t border-gray-100 bg-white flex items-center justify-between z-10">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-gray-50 flex items-center justify-center border border-gray-200">
                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[10px] font-medium text-gray-500">Data tersinkronisasi otomatis.</span>
            </div>
            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 border border-amber-200">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                <span class="text-[9px] font-bold tracking-wide uppercase text-amber-700">Live Sync</span>
            </div>
        </div>
    </aside>

    <!-- Main Content (Map) -->
    <main class="w-full h-[45%] md:h-full md:flex-1 relative z-10">
        
        <!-- Floating Running Product Catalogue Banner at Top of Map (z-[2000] to stay above Leaflet zoom layers) -->
        <div x-show="productsList.length > 0" 
             style="display: none;"
             class="absolute top-3 left-3 right-3 md:top-4 md:left-4 md:right-16 z-[2000] pointer-events-none">
            
            <div class="bg-white/95 backdrop-blur-md border border-white/80 shadow-[0_10px_30px_rgba(0,0,0,0.15)] rounded-2xl p-2 flex items-center gap-3 overflow-hidden select-none pointer-events-auto">
                <!-- Badge Label -->
                <div class="flex items-center gap-2 px-3 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl shadow-sm shrink-0">
                    <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                    <span class="text-[11px] font-black uppercase tracking-wider whitespace-nowrap">Katalog Produk</span>
                    <span class="px-1.5 py-0.5 bg-white/20 rounded-md text-[10px] font-bold" x-text="productsList.length"></span>
                </div>

                <!-- Marquee Track -->
                <div class="flex-1 overflow-hidden relative mask-linear">
                    <div class="animate-marquee gap-3 flex items-center">
                        <template x-for="(prod, idx) in marqueeList" :key="'marquee-' + idx">
                            <div @click="openModal(prod.umkm_id); focusMarkerById(prod.umkm_id);" 
                                 class="flex items-center gap-2.5 bg-white hover:bg-amber-50/90 border border-gray-100 hover:border-amber-300 rounded-xl px-3 py-1.5 transition-all duration-200 cursor-pointer shadow-sm hover:shadow-md shrink-0 group">
                                <div class="w-8 h-8 rounded-lg overflow-hidden bg-gray-100 border border-gray-200 shrink-0">
                                    <template x-if="prod.foto_produk">
                                        <img :src="'/storage/' + prod.foto_produk" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                    </template>
                                    <template x-if="!prod.foto_produk">
                                        <div class="w-full h-full flex items-center justify-center text-amber-500 text-xs font-bold">🛍️</div>
                                    </template>
                                </div>
                                <div class="min-w-0 max-w-[150px]">
                                    <div class="font-bold text-xs text-gray-900 truncate leading-tight group-hover:text-amber-700" x-text="prod.nama_produk"></div>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="text-amber-600 font-extrabold text-[11px]" x-text="'Rp ' + parseInt(prod.harga || 0).toLocaleString('id-ID')"></span>
                                        <span class="text-[10px] text-gray-400 font-medium truncate" x-text="'• ' + prod.nama_umkm"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div id="leafletMap" class="w-full h-full"></div>
    </main>

    <!-- Detail Modal -->
    <div x-show="showDetailModal" 
         style="display: none;"
         class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4 md:p-0"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
         
        <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col"
             @click.outside="closeModal()">
             
            <template x-if="activeUmkm">
                <div class="flex flex-col h-full overflow-hidden">
                    
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-bold text-white shadow-sm"
                                 :class="activeUmkm.kategori === 'Kuliner' ? 'bg-amber-500' : (activeUmkm.kategori === 'Kreatif' ? 'bg-purple-500' : 'bg-rose-500')">
                                UM
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 leading-tight" x-text="activeUmkm.nama_umkm"></h3>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider"
                                          :class="activeUmkm.kategori === 'Kuliner' ? 'bg-amber-100 text-amber-700' : (activeUmkm.kategori === 'Kreatif' ? 'bg-purple-100 text-purple-700' : 'bg-rose-100 text-rose-700')"
                                          x-text="activeUmkm.kategori"></span>
                                    <span class="text-xs text-gray-500" x-text="activeUmkm.desa ? activeUmkm.desa.nama_desa : '-'"></span>
                                </div>
                            </div>
                        </div>
                        <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-full transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <!-- Body Content -->
                    <div class="p-6 space-y-6 overflow-y-auto">
                        
                        <!-- Grid Info -->
                        <div class="grid grid-cols-2 gap-3.5">
                            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Nama UMKM</span>
                                <span class="font-bold text-gray-900 text-sm block truncate" x-text="activeUmkm.nama_umkm"></span>
                            </div>
                            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Kategori Usaha</span>
                                <span class="font-bold text-gray-900 text-sm block truncate" x-text="activeUmkm.kategori"></span>
                            </div>
                            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Nama Pemilik</span>
                                <span class="font-bold text-gray-900 text-sm block truncate" x-text="activeUmkm.nama_pemilik || 'Anonim'"></span>
                            </div>
                            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Desa / Kelurahan</span>
                                <span class="font-bold text-gray-900 text-sm block truncate" x-text="activeUmkm.desa ? activeUmkm.desa.nama_desa : '-'"></span>
                            </div>
                            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 col-span-2 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Titik Lokasi (Koordinat)</span>
                                    <span class="font-bold text-gray-800 text-xs font-mono" x-text="activeUmkm.koordinat_lokasi || 'Belum diatur'"></span>
                                </div>
                                <template x-if="activeUmkm.koordinat_lokasi">
                                    <a :href="'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(activeUmkm.koordinat_lokasi)" target="_blank" class="px-3 py-1.5 bg-white border border-gray-200 hover:bg-gray-100 text-gray-700 rounded-xl text-xs font-bold shadow-sm transition-colors flex items-center gap-1">
                                        <span>Buka Google Maps</span>
                                        <span>↗</span>
                                    </a>
                                </template>
                            </div>
                        </div>

                        <!-- Legalitas & Perizinan -->
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2.5">Legalitas & Perizinan</h4>
                            <div class="grid grid-cols-3 gap-2.5">
                                <div class="px-3 py-2 bg-blue-50/80 text-blue-800 border border-blue-100 rounded-xl text-xs font-semibold">
                                    <span class="block text-[9px] font-bold text-blue-500 uppercase tracking-wider">NIB</span>
                                    <strong class="text-xs truncate block" x-text="activeUmkm.nomor_nib || 'Belum Ada'"></strong>
                                </div>
                                <div class="px-3 py-2 bg-emerald-50/80 text-emerald-800 border border-emerald-100 rounded-xl text-xs font-semibold">
                                    <span class="block text-[9px] font-bold text-emerald-500 uppercase tracking-wider">Izin Halal</span>
                                    <strong class="text-xs truncate block" x-text="activeUmkm.izin_halal || 'Belum Ada'"></strong>
                                </div>
                                <div class="px-3 py-2 bg-purple-50/80 text-purple-800 border border-purple-100 rounded-xl text-xs font-semibold">
                                    <span class="block text-[9px] font-bold text-purple-500 uppercase tracking-wider">BPOM / PIRT</span>
                                    <strong class="text-xs truncate block" x-text="activeUmkm.bpom || 'Belum Ada'"></strong>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media & Sales Channel Links with Brand Icons -->
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2.5">Media Sosial & Toko Penjualan</h4>
                            <div class="flex flex-wrap gap-2.5">
                                <!-- WhatsApp -->
                                <template x-if="activeUmkm.whatsapp">
                                    <a :href="activeUmkm.whatsapp.startsWith('http') ? activeUmkm.whatsapp : 'https://wa.me/' + activeUmkm.whatsapp.replace(/[^0-9]/g, '')" target="_blank" class="px-4 py-2.5 bg-[#25D366] hover:bg-[#20ba59] text-white rounded-2xl text-xs font-bold inline-flex items-center gap-2 shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-1.157 4.228 4.228-1.157z"/></svg>
                                        <span>WhatsApp</span>
                                    </a>
                                </template>

                                <!-- Instagram -->
                                <template x-if="activeUmkm.instagram">
                                    <a :href="activeUmkm.instagram.startsWith('http') ? activeUmkm.instagram : 'https://instagram.com/' + activeUmkm.instagram.replace('@', '')" target="_blank" class="px-4 py-2.5 bg-gradient-to-r from-[#833ab4] via-[#fd1d1d] to-[#fcb045] hover:opacity-95 text-white rounded-2xl text-xs font-bold inline-flex items-center gap-2 shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                        <span>Instagram</span>
                                    </a>
                                </template>

                                <!-- Facebook -->
                                <template x-if="activeUmkm.facebook">
                                    <a :href="activeUmkm.facebook.startsWith('http') ? activeUmkm.facebook : 'https://facebook.com/' + activeUmkm.facebook" target="_blank" class="px-4 py-2.5 bg-[#1877F2] hover:bg-[#166fe5] text-white rounded-2xl text-xs font-bold inline-flex items-center gap-2 shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M9 8H6v4h3v12h5V12h3.642L18 8h-4V6.333C14 5.374 14.5 5 15.5 5H18V0h-3.808C10.592 0 9 1.583 9 4.615V8z"/></svg>
                                        <span>Facebook</span>
                                    </a>
                                </template>

                                <!-- TikTok -->
                                <template x-if="activeUmkm.tiktok">
                                    <a :href="activeUmkm.tiktok.startsWith('http') ? activeUmkm.tiktok : 'https://tiktok.com/@' + activeUmkm.tiktok.replace('@', '')" target="_blank" class="px-4 py-2.5 bg-[#000000] hover:bg-gray-800 text-white rounded-2xl text-xs font-bold inline-flex items-center gap-2 shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.29-3.08 1.25-6.08 3.95-7.51 1.09-.59 2.34-.89 3.58-.89v4.06c-.85.02-1.7.27-2.42.73-.97.6-1.57 1.68-1.55 2.82.01 1.29.74 2.47 1.88 3.03 1.14.56 2.5.42 3.52-.35.82-.6 1.3-1.58 1.34-2.61.02-3.79.01-7.58.01-11.37z"/></svg>
                                        <span>TikTok</span>
                                    </a>
                                </template>

                                <!-- Link Penjualan / Marketplace -->
                                <template x-if="activeUmkm.link_marketplace">
                                    <a :href="activeUmkm.link_marketplace.startsWith('http') ? activeUmkm.link_marketplace : 'https://' + activeUmkm.link_marketplace" target="_blank" class="px-4 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-2xl text-xs font-bold inline-flex items-center gap-2 shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h14v12zm-7-8c-1.66 0-3-1.34-3-3H7c0 2.76 2.24 5 5 5s5-2.24 5-5h-2c0 1.66-1.34 3-3 3z"/></svg>
                                        <span>Link Penjualan / Marketplace ↗</span>
                                    </a>
                                </template>

                                <template x-if="!activeUmkm.whatsapp && !activeUmkm.instagram && !activeUmkm.facebook && !activeUmkm.tiktok && !activeUmkm.link_marketplace">
                                    <span class="text-xs text-gray-400 italic">Belum ada link sosial media yang dicantumkan.</span>
                                </template>
                            </div>
                        </div>

                        <!-- Katalog Produk & Harga -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                                    <span>Katalog Produk & Harga</span>
                                    <template x-if="activeUmkm.produks && activeUmkm.produks.length > 0">
                                        <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold" x-text="activeUmkm.produks.length + ' Produk'"></span>
                                    </template>
                                </h4>
                            </div>

                            <template x-if="activeUmkm.produks && activeUmkm.produks.length > 0">
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    <template x-for="(prod, pIdx) in activeUmkm.produks" :key="prod.id || pIdx">
                                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all overflow-hidden group">
                                            <div class="relative aspect-square bg-gray-100 overflow-hidden cursor-pointer"
                                                 @click="prod.foto_produk ? openGallery([prod.foto_produk], 0) : null">
                                                <template x-if="prod.foto_produk">
                                                    <img :src="'/storage/' + prod.foto_produk" :alt="prod.nama_produk" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                </template>
                                                <template x-if="!prod.foto_produk">
                                                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-300 p-2">
                                                        <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                        <span class="text-[10px] font-medium text-gray-400">Tanpa Foto</span>
                                                    </div>
                                                </template>
                                                <template x-if="prod.foto_produk">
                                                    <div class="absolute inset-0 bg-black/25 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                        <div class="p-1.5 rounded-full bg-white/90 backdrop-blur-sm text-gray-800 shadow-md">🔍</div>
                                                    </div>
                                                </template>
                                            </div>
                                            <div class="p-3">
                                                <h5 class="font-bold text-gray-900 text-xs leading-snug truncate" x-text="prod.nama_produk"></h5>
                                                <p class="text-emerald-600 font-extrabold text-xs mt-1" x-text="'Rp ' + parseInt(prod.harga || 0).toLocaleString('id-ID')"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <template x-if="!activeUmkm.produks || activeUmkm.produks.length === 0">
                                <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 text-center">
                                    <p class="text-xs text-gray-400 italic">Belum ada daftar produk terperinci yang ditambahkan.</p>
                                </div>
                            </template>
                        </div>

                        <!-- Foto Produk -->
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Foto Dokumentasi UMKM</h4>
                            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                                <template x-if="activeUmkm.foto_produk && activeUmkm.foto_produk.length > 0">
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                                        <template x-for="(foto, index) in activeUmkm.foto_produk" :key="index">
                                            <button type="button" @click="openGallery(activeUmkm.foto_produk, index)" class="block relative w-full h-24 rounded-xl overflow-hidden border border-gray-200 shadow-sm hover:shadow-md hover:scale-[1.03] transition-all group cursor-pointer text-left">
                                                <img :src="'/storage/' + foto" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="Foto Produk">
                                                <div class="absolute inset-0 bg-black/25 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                    <div class="p-1.5 rounded-full bg-white/90 backdrop-blur-sm text-gray-800 shadow-md">🔍</div>
                                                </div>
                                            </button>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="!activeUmkm.foto_produk || activeUmkm.foto_produk.length === 0">
                                    <p class="text-sm text-gray-500 italic">Tidak ada foto dokumentasi yang dilampirkan.</p>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Gallery Lightbox Modal -->
    <div x-show="showGalleryModal" 
         x-cloak
         style="display: none;"
         class="fixed inset-0 z-[99999] overflow-hidden flex items-center justify-center p-4 select-none"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">

        <div class="fixed inset-0 bg-black/90 backdrop-blur-lg transition-opacity" @click="closeGallery()"></div>

        <div class="relative w-full max-w-5xl z-10 flex flex-col items-center justify-between min-h-[500px] max-h-[92vh]">
            <div class="w-full flex items-center justify-between px-5 py-3.5 bg-white/10 backdrop-blur-md rounded-2xl border border-white/15 text-white mb-2 shadow-2xl">
                <div>
                    <h3 class="font-bold text-sm sm:text-base text-white" x-text="activeUmkm ? activeUmkm.nama_umkm : 'Galeri Produk'"></h3>
                    <p class="text-xs text-amber-300/80 font-medium" x-text="'Foto ' + (currentGalleryIndex + 1) + ' dari ' + galleryPhotos.length"></p>
                </div>
                <button @click="closeGallery()" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all">✕</button>
            </div>

            <!-- 3D Coverflow -->
            <div class="w-full flex-1 flex items-center justify-center relative perspective-1000 py-4 my-auto overflow-hidden">
                <button x-show="galleryPhotos.length > 1" @click="prevPhoto()" class="absolute left-4 z-30 w-12 h-12 rounded-full bg-black/60 hover:bg-amber-600 text-white backdrop-blur-md flex items-center justify-center transition-all">&larr;</button>

                <div class="flex items-center justify-center gap-4 sm:gap-8 w-full max-w-4xl px-4">
                    <template x-if="galleryPhotos.length > 1">
                        <div @click="prevPhoto()" class="hidden md:block w-1/4 h-[300px] rounded-2xl overflow-hidden border border-white/20 shadow-2xl cursor-pointer opacity-45 transform rotate-y-left">
                            <img :src="'/storage/' + galleryPhotos[(currentGalleryIndex - 1 + galleryPhotos.length) % galleryPhotos.length]" class="w-full h-full object-cover">
                        </div>
                    </template>

                    <div class="w-full sm:w-3/4 md:w-1/2 h-[340px] sm:h-[420px] rounded-3xl overflow-hidden border-4 border-white/25 shadow-2xl relative z-20 bg-black/80">
                        <img :src="'/storage/' + galleryPhotos[currentGalleryIndex]" class="w-full h-full object-contain">
                    </div>

                    <template x-if="galleryPhotos.length > 1">
                        <div @click="nextPhoto()" class="hidden md:block w-1/4 h-[300px] rounded-2xl overflow-hidden border border-white/20 shadow-2xl cursor-pointer opacity-45 transform rotate-y-right">
                            <img :src="'/storage/' + galleryPhotos[(currentGalleryIndex + 1) % galleryPhotos.length]" class="w-full h-full object-cover">
                        </div>
                    </template>
                </div>

                <button x-show="galleryPhotos.length > 1" @click="nextPhoto()" class="absolute right-4 z-30 w-12 h-12 rounded-full bg-black/60 hover:bg-amber-600 text-white backdrop-blur-md flex items-center justify-center transition-all">&rarr;</button>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('umkmMapData', () => {
                let map = null;
                let geojsonLayer = null;
                let markers = [];

                return {
                    allUmkms: @json($umkms),
                    desas: @json($desas),
                    searchQuery: '',
                    selectedDesa: '',
                    selectedKategori: '',
                    showDetailModal: false,
                    activeUmkm: null,
                    showGalleryModal: false,
                    galleryPhotos: [],
                    currentGalleryIndex: 0,
                    productsList: [],
                    marqueeList: [],

                    initProductsList() {
                        let prods = [];
                        (this.allUmkms || []).forEach(u => {
                            if (u.produks && u.produks.length > 0) {
                                u.produks.forEach(p => {
                                    prods.push({
                                        ...p,
                                        umkm_id: u.id,
                                        nama_umkm: u.nama_umkm,
                                        kategori: u.kategori,
                                        desa_nama: u.desa ? u.desa.nama_desa : '-'
                                    });
                                });
                            }
                        });
                        this.productsList = prods;

                        if (prods.length > 0) {
                            let repeated = [];
                            while (repeated.length < 16) {
                                repeated = repeated.concat(prods);
                            }
                            this.marqueeList = repeated;
                        } else {
                            this.marqueeList = [];
                        }
                    },

                    openGallery(photos, initialIndex = 0) {
                        if (!photos || photos.length === 0) return;
                        this.galleryPhotos = photos;
                        this.currentGalleryIndex = initialIndex;
                        this.showGalleryModal = true;
                    },

                    closeGallery() {
                        this.showGalleryModal = false;
                    },

                    nextPhoto() {
                        if (this.galleryPhotos.length === 0) return;
                        this.currentGalleryIndex = (this.currentGalleryIndex + 1) % this.galleryPhotos.length;
                    },

                    prevPhoto() {
                        if (this.galleryPhotos.length === 0) return;
                        this.currentGalleryIndex = (this.currentGalleryIndex - 1 + this.galleryPhotos.length) % this.galleryPhotos.length;
                    },

                    init() {
                        this.initProductsList();
                        this.initMap();
                        this.$watch('searchQuery', () => this.updateMapMarkers());
                        this.$watch('selectedDesa', () => this.updateMapMarkers());
                        this.$watch('selectedKategori', () => this.updateMapMarkers());
                    },

                    get filteredUmkms() {
                        return this.allUmkms.filter(u => {
                            const matchSearch = u.nama_umkm.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                                (u.nama_pemilik && u.nama_pemilik.toLowerCase().includes(this.searchQuery.toLowerCase()));
                            const matchDesa = this.selectedDesa === '' || u.desa_id == this.selectedDesa;
                            const matchKategori = this.selectedKategori === '' || u.kategori === this.selectedKategori;
                            return matchSearch && matchDesa && matchKategori;
                        });
                    },

                    get totalFilteredKuliner() {
                        return this.filteredUmkms.filter(u => u.kategori === 'Kuliner').length;
                    },

                    get totalFilteredKreatif() {
                        return this.filteredUmkms.filter(u => u.kategori === 'Kreatif').length;
                    },

                    get totalFilteredFashion() {
                        return this.filteredUmkms.filter(u => u.kategori === 'Fashion').length;
                    },

                    get allProducts() {
                        let prods = [];
                        this.allUmkms.forEach(u => {
                            if (u.produks && u.produks.length > 0) {
                                u.produks.forEach(p => {
                                    prods.push({
                                        ...p,
                                        umkm_id: u.id,
                                        nama_umkm: u.nama_umkm,
                                        kategori: u.kategori,
                                        desa_nama: u.desa ? u.desa.nama_desa : '-'
                                    });
                                });
                            }
                        });
                        return prods;
                    },

                    get marqueeProducts() {
                        const list = this.allProducts;
                        if (list.length === 0) return [];
                        let repeated = [];
                        while (repeated.length < 12) {
                            repeated = repeated.concat(list);
                        }
                        return repeated;
                    },

                    focusMarkerById(id) {
                        const umkm = this.allUmkms.find(u => u.id === id);
                        if (umkm) {
                            this.focusMarker(umkm);
                        }
                    },

                    openModal(id) {
                        this.activeUmkm = this.allUmkms.find(u => u.id === id);
                        if (this.activeUmkm) {
                            this.showDetailModal = true;
                        }
                    },

                    closeModal() {
                        this.showDetailModal = false;
                        setTimeout(() => { this.activeUmkm = null; }, 300);
                    },

                    focusMarker(umkm) {
                        if (umkm.koordinat_lokasi && umkm.koordinat_lokasi.includes(',')) {
                            const parts = umkm.koordinat_lokasi.split(',');
                            const lat = parseFloat(parts[0].trim());
                            const lng = parseFloat(parts[1].trim());
                            if (!isNaN(lat) && !isNaN(lng)) {
                                map.flyTo([lat, lng], 16, { duration: 1.5 });
                                const targetMarker = markers.find(m => m.getLatLng().lat === lat && m.getLatLng().lng === lng);
                                if (targetMarker) {
                                    setTimeout(() => targetMarker.openPopup(), 1200);
                                }
                            }
                        }
                    },

                    initMap() {
                        map = L.map('leafletMap', { zoomControl: false }).setView([-7.1233, 107.4735], 13);
                        L.control.zoom({ position: 'topright' }).addTo(map);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors',
                            maxZoom: 19
                        }).addTo(map);

                        this.loadGeoJSON();
                        this.updateMapMarkers();
                    },

                    loadGeoJSON() {
                        fetch('/geojson/pasirjambu.geojson')
                            .then(res => res.json())
                            .then(data => {
                                geojsonLayer = L.geoJSON(data, {
                                    style: () => ({
                                        color: '#022c22',
                                        weight: 2.5,
                                        opacity: 1,
                                        fillColor: '#f59e0b',
                                        fillOpacity: 0.15
                                    })
                                }).addTo(map);

                                map.fitBounds(geojsonLayer.getBounds(), { padding: [50, 50] });
                            });
                    },

                    updateMapMarkers() {
                        markers.forEach(m => map.removeLayer(m));
                        markers = [];

                        const createPulseIcon = (color) => {
                            return L.divIcon({
                                className: 'custom-div-icon',
                                html: `<div class="pulse-marker" style="background-color:${color}; width:20px; height:20px; border-radius:50%; border:3px solid white; box-shadow:0 2px 10px rgba(0,0,0,0.3); position:relative;"></div>`,
                                iconSize: [20, 20],
                                iconAnchor: [10, 10]
                            });
                        };

                        this.filteredUmkms.forEach(u => {
                            if (u.koordinat_lokasi && u.koordinat_lokasi.includes(',')) {
                                const parts = u.koordinat_lokasi.split(',');
                                const lat = parseFloat(parts[0].trim());
                                const lng = parseFloat(parts[1].trim());

                                if (!isNaN(lat) && !isNaN(lng)) {
                                    let color = '#f59e0b';
                                    if (u.kategori === 'Kreatif') color = '#8b5cf6';
                                    if (u.kategori === 'Fashion') color = '#f43f5e';

                                    const popupContent = `
                                        <div class="p-4 min-w-[240px] max-w-[280px]">
                                            <div class="flex items-center justify-between mb-2.5">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm"
                                                      style="background-color: ${color}15; color: ${color}; border: 1px solid ${color}40;">
                                                    <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background-color: ${color}"></span>
                                                    ${u.kategori || 'UMKM'}
                                                </span>
                                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">PASIRJAMBU</span>
                                            </div>

                                            <h4 class="font-extrabold text-gray-900 text-base leading-tight mb-2.5 tracking-tight">${u.nama_umkm}</h4>
                                            
                                            <div class="space-y-1.5 mb-4 text-xs text-gray-600 font-medium">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                    <span class="truncate">Pemilik: <strong class="text-gray-900 font-semibold">${u.nama_pemilik || 'Anonim'}</strong></span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    <span class="truncate">Desa: <strong class="text-gray-900 font-semibold">${u.desa ? u.desa.nama_desa : '-'}</strong></span>
                                                </div>
                                            </div>

                                            <button onclick="window.dispatchEvent(new CustomEvent('open-umkm-modal', {detail: ${u.id}}))" class="w-full py-2.5 px-4 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-xl text-xs font-extrabold tracking-wide shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer flex items-center justify-center gap-1.5 transform hover:scale-[1.02] active:scale-[0.98]">
                                                <span>Lihat Detail Lengkap</span>
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </button>
                                        </div>
                                    `;

                                    const m = L.marker([lat, lng], { icon: createPulseIcon(color) })
                                        .bindPopup(popupContent, {
                                            offset: [0, -8],
                                            closeButton: false,
                                            className: 'rounded-xl border-none shadow-xl'
                                        })
                                        .addTo(map);

                                    m.on('mouseover', function() {
                                        this.openPopup();
                                    });

                                    markers.push(m);
                                }
                            }
                        });
                    }
                };
            });
        });
    </script>
</body>
</html>
