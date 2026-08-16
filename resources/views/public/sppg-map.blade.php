<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Peta Sebaran SPPG - Pasirjambu</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #1e293b; overflow: hidden; }
        
        /* Custom Light Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: rgba(0,0,0,0.02); }
        ::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.15); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(0,0,0,0.25); }
        
        /* Premium Light Leaflet Popups */
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
        .leaflet-popup-tip { background: rgba(255, 255, 255, 0.96) !important; }
        .leaflet-container a.leaflet-popup-close-button { color: #64748b; }
        
        /* Animations */
        .slide-in-left { animation: slideInLeft 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .fade-in-up { animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; transform: translateY(10px); }
        
        @keyframes slideInLeft {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }
        
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
        .perspective-1000 {
            perspective: 1000px;
        }
        .rotate-y-left {
            transform: rotateY(25deg) scale(0.85);
            transform-style: preserve-3d;
        }
        .rotate-y-right {
            transform: rotateY(-25deg) scale(0.85);
            transform-style: preserve-3d;
        }
    </style>
</head>
<body class="h-screen w-screen flex flex-col-reverse md:flex-row relative bg-gray-50 overflow-hidden" x-data="sppgMapData()" @keydown.window.escape="closeGallery()" @keydown.window.arrow-right="showGalleryModal && nextPhoto()" @keydown.window.arrow-left="showGalleryModal && prevPhoto()">
    @include('components.page-loader')

    <!-- Sidebar (Left on Desktop, Bottom on Mobile) -->
    <aside style="background-color: #f8fafc;" class="w-full md:w-[420px] h-[55%] md:h-full flex flex-col border-t md:border-t-0 md:border-r border-gray-200 shadow-[0_-10px_30px_rgba(0,0,0,0.05)] md:shadow-[10px_0_30px_rgba(0,0,0,0.08)] relative z-20 shrink-0 slide-in-left">
        
        <!-- Header -->
        <div class="p-6 pb-4 flex items-center justify-between border-b border-gray-100 bg-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 flex items-center justify-center shrink-0">
                    <img src="{{ asset('images/logo-kab-bandung.png') }}" alt="Logo Kabupaten Bandung" class="w-full h-full object-contain drop-shadow-md">
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-900 tracking-tight">SISTEM INFORMASI SPPG</h1>
                    <p class="text-[11px] text-[#059669] font-medium">Kecamatan Pasirjambu • Real-time</p>
                </div>
            </div>
            <a href="{{ url('/') }}" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900 transition-colors shadow-inner" title="Kembali ke Beranda">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
        </div>

        <!-- Stats -->
        <div class="px-6 py-5 grid grid-cols-3 gap-3 border-b border-gray-100 bg-[#f8fafc]">
            <div class="bg-white rounded-xl p-3 text-center border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider mb-1">Total SPPG</p>
                <p class="text-2xl font-bold text-gray-900" x-text="filteredSppgs.length">0</p>
            </div>
            <div class="bg-white rounded-xl p-3 text-center border border-gray-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="absolute inset-0 bg-[#fef3c7] opacity-50"></div>
                <div class="relative z-10">
                    <p class="text-[10px] text-[#d97706] font-semibold uppercase tracking-wider mb-1">Total PM</p>
                    <p class="text-2xl font-bold text-[#f59e0b]" x-text="formatNumber(totalFilteredPorsi)">0</p>
                </div>
            </div>
            <div class="bg-white rounded-xl p-3 text-center border border-gray-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="absolute inset-0 bg-[#ecfdf5] opacity-50"></div>
                <div class="relative z-10">
                    <p class="text-[10px] text-[#059669] font-semibold uppercase tracking-wider mb-1">Operasional</p>
                    <p class="text-2xl font-bold text-[#059669]" x-text="totalFilteredOperasional">0</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="p-6 border-b border-gray-100 space-y-4">
            <!-- Search -->
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400 group-focus-within:text-pj-green-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" x-model="searchQuery" placeholder="Cari SPPG, Ketua, Yayasan..." class="block w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl leading-5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pj-green-500/20 focus:border-pj-green-500 sm:text-sm transition-all shadow-sm">
            </div>

            <!-- Selects -->
            <div class="grid grid-cols-2 gap-3">
                <div class="relative group">
                    <label class="block text-[10px] text-gray-500 font-semibold uppercase tracking-wider mb-1.5 ml-1">Desa / Kelurahan</label>
                    <select x-model="selectedDesa" class="block w-full py-2.5 pl-3 pr-8 bg-white border border-gray-200 text-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:border-green-500 sm:text-sm shadow-sm transition-all cursor-pointer" style="outline-color: #10b981;">
                        <option value="">Semua Desa</option>
                        @foreach($desas as $desa)
                            <option value="{{ $desa->id }}">{{ $desa->nama_desa }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="relative group">
                    <label class="block text-[10px] text-gray-500 font-semibold uppercase tracking-wider mb-1.5 ml-1">Status Operasional</label>
                    <select x-model="selectedStatus" class="block w-full py-2.5 pl-3 pr-8 bg-white border border-gray-200 text-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:border-green-500 sm:text-sm shadow-sm transition-all cursor-pointer" style="outline-color: #10b981;">
                        <option value="">Semua Status</option>
                        <option value="Operasional">Operasional</option>
                        <option value="Belum Operasional">Belum Operasional</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- List -->
        <div class="flex-1 overflow-y-auto p-5 space-y-4 relative z-0">
            
            <template x-if="filteredSppgs.length === 0">
                <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-200 shadow-sm fade-in-up">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Tidak ada data SPPG yang sesuai dengan filter.</p>
                </div>
            </template>

            <template x-for="(sppg, index) in filteredSppgs" :key="sppg.id">
                <div @click="focusMarker(sppg)" :style="`animation-delay: ${index * 0.05}s`" class="group cursor-pointer bg-white border border-gray-100 rounded-2xl p-5 hover:border-pj-green-300 hover:shadow-[0_10px_40px_-10px_rgba(16,185,129,0.2)] transition-all duration-300 relative overflow-hidden transform hover:-translate-y-1 fade-in-up">
                    
                    <!-- Decorative background blob -->
                    <div class="absolute -right-10 -top-10 w-32 h-32 rounded-full opacity-10 transition-transform duration-500 group-hover:scale-150" :class="sppg.status === 'Operasional' ? 'bg-pj-green-500' : 'bg-pj-gold-500'"></div>
                    
                    <div class="flex justify-between items-start mb-3 relative z-10">
                        <div class="pr-3">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold tracking-wider mb-2.5 shadow-sm" :style="sppg.status === 'Operasional' ? 'background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;' : 'background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a;'">
                                <span class="w-1.5 h-1.5 rounded-full" :style="sppg.status === 'Operasional' ? 'background-color: #10b981;' : 'background-color: #f59e0b;'"></span>
                                <span x-text="sppg.status.toUpperCase()"></span>
                            </span>
                            <h3 class="font-bold text-gray-900 text-[15px] leading-snug transition-colors" x-text="sppg.nama_sppg" style="color: #111827;"></h3>
                            <p class="text-[13px] text-gray-500 mt-1 font-medium" x-text="sppg.nama_yayasan || '-'"></p>
                        </div>
                        <div class="text-right shrink-0 bg-gray-50 px-3 py-2 rounded-lg border border-gray-100 transition-colors">
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-0.5">PRODUKSI</p>
                            <p class="font-bold text-base leading-none" style="color: #f59e0b;"><span x-text="formatNumber(sppg.jumlah_penerima_manfaat)"></span> <span class="text-[10px] text-gray-500 font-medium">PM</span></p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100 text-xs text-gray-500 relative z-10">
                        <div class="flex items-center gap-1.5 truncate pr-2 group-hover:text-gray-700 transition-colors">
                            <svg class="w-4 h-4 text-pj-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="truncate font-medium" x-text="sppg.desa ? sppg.desa.nama_desa.toUpperCase() : '-'"></span>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0 group-hover:text-gray-700 transition-colors">
                            <svg class="w-4 h-4 text-pj-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span class="truncate max-w-[90px] font-medium" x-text="sppg.ketua_sppg || '-'"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div class="p-3 border-t border-gray-100 bg-white flex items-center justify-between z-10">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-gray-50 flex items-center justify-center border border-gray-200">
                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[10px] font-medium text-gray-500">Data tersinkronisasi otomatis.</span>
            </div>
            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full" style="background-color: #ecfdf5; border: 1px solid #a7f3d0;">
                <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background-color: #10b981; box-shadow: 0 0 5px rgba(16,185,129,0.5);"></span>
                <span class="text-[9px] font-bold tracking-wide uppercase" style="color: #047857;">Live Sync</span>
            </div>
        </div>
    </aside>

    <!-- Main Content (Map) -->
    <main class="w-full h-[45%] md:h-full md:flex-1 relative z-10">
        <div id="leafletMap" class="w-full h-full"></div>
    </main>

    <!-- Modal Detail SPPG -->
    <div x-show="showDetailModal" 
         style="display: none;"
         class="fixed inset-0 z-[9999] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-900/60 backdrop-blur-sm p-4 md:p-0"
         @open-sppg-modal.window="openModal($event.detail)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
         
        <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.3)] overflow-hidden"
             @click.outside="closeModal()"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95">
            
            <template x-if="activeSppg">
                <div class="flex flex-col h-full max-h-[85vh]">
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center shadow-sm"
                                 :class="activeSppg.status === 'Operasional' ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 leading-tight" x-text="activeSppg.nama_sppg"></h3>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-bold tracking-wider uppercase"
                                          :class="activeSppg.status === 'Operasional' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'">
                                        <span class="w-1.5 h-1.5 rounded-full" :class="activeSppg.status === 'Operasional' ? 'bg-emerald-500' : 'bg-amber-500'"></span>
                                        <span x-text="activeSppg.status"></span>
                                    </span>
                                    <span class="text-xs text-gray-500" x-text="activeSppg.desa ? activeSppg.desa.nama_desa : '-'"></span>
                                </div>
                            </div>
                        </div>
                        <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-full transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="p-6 overflow-y-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Keterangan Umum -->
                            <div class="space-y-4">
                                <div>
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Informasi Umum</h4>
                                    <div class="bg-gray-50 rounded-xl p-4 space-y-3 border border-gray-100">
                                        <div>
                                            <p class="text-[11px] text-gray-500 font-medium mb-0.5">Ketua SPPG</p>
                                            <p class="text-sm font-semibold text-gray-900" x-text="activeSppg.ketua_sppg || '-'"></p>
                                        </div>
                                        <div>
                                            <p class="text-[11px] text-gray-500 font-medium mb-0.5">Nama Yayasan</p>
                                            <p class="text-sm font-semibold text-gray-900" x-text="activeSppg.nama_yayasan || '-'"></p>
                                        </div>
                                        <div>
                                            <p class="text-[11px] text-gray-500 font-medium mb-0.5">Alamat Lengkap</p>
                                            <p class="text-sm font-semibold text-gray-900" x-text="activeSppg.alamat || '-'"></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                                        <p class="text-[11px] text-emerald-600 font-bold uppercase tracking-wider mb-1">Porsi Produksi / PM</p>
                                        <div class="flex items-end gap-1.5">
                                            <span class="text-2xl font-black text-emerald-700" x-text="formatNumber(activeSppg.jumlah_penerima_manfaat)"></span>
                                            <span class="text-sm font-bold text-emerald-600 mb-1">Penerima Manfaat</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Data Operasional & Lokasi -->
                            <div class="space-y-4">
                                <div>
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Operasional & Lokasi</h4>
                                    <div class="bg-gray-50 rounded-xl p-4 space-y-3 border border-gray-100">
                                        <div>
                                            <p class="text-[11px] text-gray-500 font-medium mb-0.5">Desa / Wilayah</p>
                                            <p class="text-sm font-semibold text-gray-900" x-text="activeSppg.desa ? activeSppg.desa.nama_desa : '-'"></p>
                                        </div>
                                        <div>
                                            <p class="text-[11px] text-gray-500 font-medium mb-0.5">Koordinat Lokasi</p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="px-2 py-1 bg-white border border-gray-200 rounded-md text-xs font-mono text-gray-600" x-text="activeSppg.koordinat_lokasi || '-'"></span>
                                                <template x-if="activeSppg.koordinat_lokasi">
                                                    <a :href="'https://www.google.com/maps/search/?api=1&query=' + activeSppg.koordinat_lokasi" target="_blank" class="text-blue-500 hover:text-blue-600 p-1 bg-blue-50 hover:bg-blue-100 rounded transition-colors" title="Buka di Google Maps">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                    </a>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Foto Lokasi</h4>
                                    <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 shadow-sm">
                                        <template x-if="activeSppg.foto_sppg && activeSppg.foto_sppg.length > 0">
                                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                                                <template x-for="(foto, index) in activeSppg.foto_sppg" :key="index">
                                                    <button type="button" @click="openGallery(activeSppg.foto_sppg, index)" class="block relative w-full h-24 rounded-xl overflow-hidden border border-gray-200 shadow-sm hover:shadow-md hover:scale-[1.03] transition-all group cursor-pointer text-left focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                                        <img :src="'/storage/' + foto" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="Foto SPPG">
                                                        <div class="absolute inset-0 bg-black/25 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                            <div class="p-1.5 rounded-full bg-white/90 backdrop-blur-sm text-gray-800 shadow-md transform scale-90 group-hover:scale-100 transition-transform">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                                            </div>
                                                        </div>
                                                        <span class="absolute bottom-1 right-1 bg-black/60 text-white text-[9px] font-bold px-1.5 py-0.5 rounded" x-text="(index + 1) + '/' + activeSppg.foto_sppg.length"></span>
                                                    </button>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="!activeSppg.foto_sppg || activeSppg.foto_sppg.length === 0">
                                            <p class="text-sm text-gray-500 italic">Tidak ada foto yang dilampirkan.</p>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sppgMapData', () => {
                // Non-reactive variables to prevent Alpine Proxy corruption on Leaflet objects
                let map = null;
                let geojsonLayer = null;
                let markers = [];

                return {
                    allSppgs: @json($sppgs),
                    searchQuery: '',
                    selectedDesa: '',
                    selectedStatus: '',
                    showDetailModal: false,
                    activeSppg: null,
                    showGalleryModal: false,
                    galleryPhotos: [],
                    currentGalleryIndex: 0,

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
                    this.initMap();
                    
                    // Watch for filter changes
                    this.$watch('searchQuery', () => this.updateMapMarkers());
                    this.$watch('selectedDesa', () => this.updateMapMarkers());
                    this.$watch('selectedStatus', () => this.updateMapMarkers());
                },
                
                get filteredSppgs() {
                    return this.allSppgs.filter(sppg => {
                        const matchSearch = sppg.nama_sppg.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                                            (sppg.ketua_sppg && sppg.ketua_sppg.toLowerCase().includes(this.searchQuery.toLowerCase())) ||
                                            (sppg.nama_yayasan && sppg.nama_yayasan.toLowerCase().includes(this.searchQuery.toLowerCase()));
                        const matchDesa = this.selectedDesa === '' || sppg.desa_id == this.selectedDesa;
                        const matchStatus = this.selectedStatus === '' || sppg.status === this.selectedStatus;
                        
                        return matchSearch && matchDesa && matchStatus;
                    });
                },
                
                get totalFilteredPorsi() {
                    return this.filteredSppgs.reduce((total, sppg) => total + (sppg.jumlah_penerima_manfaat || 0), 0);
                },

                get totalFilteredOperasional() {
                    return this.filteredSppgs.filter(sppg => sppg.status === 'Operasional').length;
                },
                
                formatNumber(num) {
                    return num ? num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") : '0';
                },
                
                openModal(id) {
                    this.activeSppg = this.allSppgs.find(s => s.id === id);
                    if (this.activeSppg) {
                        this.showDetailModal = true;
                        document.body.style.overflow = 'hidden';
                    }
                },
                
                closeModal() {
                    this.showDetailModal = false;
                    document.body.style.overflow = 'auto';
                    setTimeout(() => { this.activeSppg = null; }, 300);
                },
                
                initMap() {
                    // Default view Pasirjambu
                    map = L.map('leafletMap', {
                        zoomControl: false
                    }).setView([-7.1233, 107.4735], 13);
                    
                    // Move zoom control to top right, with some offset to avoid overlapping custom UI if needed
                    L.control.zoom({ position: 'topright' }).addTo(map);

                    // Use standard OpenStreetMap for colorful, natural look
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                        maxZoom: 19
                    }).addTo(map);
                    
                    this.loadGeoJSON();
                    this.updateMapMarkers();
                },
                
                getSppgCountForDesa(desaName) {
                    if (!desaName) return 0;
                    const cleanName = desaName.toLowerCase().replace(/^desa\s+/, '').trim();
                    return this.allSppgs.filter(sppg => {
                        if (!sppg.desa || !sppg.desa.nama_desa) return false;
                        const sppgDesaName = sppg.desa.nama_desa.toLowerCase().replace(/^desa\s+/, '').trim();
                        return sppgDesaName === cleanName || cleanName.includes(sppgDesaName) || sppgDesaName.includes(cleanName);
                    }).length;
                },

                getSppgColor(count) {
                    if (count >= 5) return '#047857'; // High Density (Dark Emerald)
                    if (count >= 3) return '#10b981'; // Medium-High Density (Emerald)
                    if (count >= 2) return '#34d399'; // Medium Density (Mint)
                    if (count >= 1) return '#a7f3d0'; // Low Density (Soft Green)
                    return '#f1f5f9';                 // 0 SPPG (Light Gray)
                },

                getSppgBorder(count) {
                    if (count >= 5) return '#022c22';
                    if (count >= 3) return '#047857';
                    if (count >= 2) return '#059669';
                    if (count >= 1) return '#059669';
                    return '#cbd5e1';
                },

                loadGeoJSON() {
                    fetch('/geojson/pasirjambu.geojson')
                        .then(response => response.json())
                        .then(data => {
                            geojsonLayer = L.geoJSON(data, {
                                style: (feature) => {
                                    const desaName = feature.properties.NAMOBJ || '';
                                    const count = this.getSppgCountForDesa(desaName);
                                    const fillColor = this.getSppgColor(count);
                                    
                                    return {
                                        color: '#022c22',    // Dark solid forest green border line
                                        weight: 2.5,         // Thick & clear boundary line
                                        opacity: 1,          // 100% solid opacity
                                        fillColor: fillColor,
                                        fillOpacity: count >= 5 ? 0.75 : (count >= 3 ? 0.65 : (count >= 2 ? 0.55 : (count >= 1 ? 0.45 : 0.2))),
                                        dashArray: ''
                                    };
                                },
                                onEachFeature: (feature, layer) => {
                                    const desaName = feature.properties.NAMOBJ || '';
                                    const sppgCount = this.getSppgCountForDesa(desaName);
                                    
                                    // Hover effect
                                    layer.on({
                                        mouseover: (e) => {
                                            const l = e.target;
                                            l.setStyle({
                                                color: '#000000',
                                                weight: 4,
                                                fillOpacity: 0.85
                                            });
                                        },
                                        mouseout: (e) => {
                                            if (geojsonLayer) {
                                                geojsonLayer.resetStyle(e.target);
                                            }
                                        }
                                    });
                                }
                            }).addTo(map);
                            
                            map.fitBounds(geojsonLayer.getBounds(), { padding: [50, 50] });
                        })
                        .catch(err => console.error('Error loading GeoJSON:', err));
                },
                
                updateMapMarkers() {
                    // Clear existing markers
                    markers.forEach(m => map.removeLayer(m));
                    markers = [];
                    
                    const bounds = L.latLngBounds();
                    let hasValidCoords = false;

                    // Custom pulsing icon
                    const createIcon = (isOperasional) => {
                        const color = isOperasional ? '#10b981' : '#f59e0b'; // pj-green or gold
                        return L.divIcon({
                            className: 'custom-div-icon',
                            html: `<div class="pulse-marker" style="background-color:${color}; width:20px; height:20px; border-radius:50%; border:3px solid white; box-shadow:0 2px 10px rgba(0,0,0,0.3); position:relative;"></div>`,
                            iconSize: [20, 20],
                            iconAnchor: [10, 10]
                        });
                    };

                    this.filteredSppgs.forEach(sppg => {
                        if (sppg.koordinat_lokasi && sppg.koordinat_lokasi.includes(',')) {
                            const parts = sppg.koordinat_lokasi.split(',');
                            const lat = parseFloat(parts[0].trim());
                            const lng = parseFloat(parts[1].trim());
                            
                            if (!isNaN(lat) && !isNaN(lng)) {
                                const isOperasional = sppg.status === 'Operasional';
                                const popupContent = `
                                    <div class="p-4 min-w-[240px] max-w-[280px]">
                                        <div class="flex items-center justify-between mb-2.5">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm"
                                                  style="${isOperasional ? 'background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;' : 'background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a;'}">
                                                <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="${isOperasional ? 'background-color: #10b981;' : 'background-color: #f59e0b;'}"></span>
                                                ${sppg.status.toUpperCase()}
                                            </span>
                                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">SPPG</span>
                                        </div>

                                        <h4 class="font-extrabold text-gray-900 text-base leading-tight mb-1.5 tracking-tight">${sppg.nama_sppg}</h4>
                                        <p class="text-xs text-gray-500 mb-3 font-medium truncate">${sppg.nama_yayasan || '-'}</p>
                                        
                                        <div class="bg-emerald-50/60 rounded-xl p-2.5 mb-4 border border-emerald-100 flex items-center justify-between">
                                            <span class="text-[10px] text-emerald-800 font-bold uppercase tracking-wider">Kapasitas PM</span>
                                            <span class="text-sm font-extrabold text-emerald-600">${this.formatNumber(sppg.jumlah_penerima_manfaat)} <span class="text-[10px] font-normal text-gray-500">PM</span></span>
                                        </div>

                                        <button onclick="window.dispatchEvent(new CustomEvent('open-sppg-modal', {detail: ${sppg.id}}))" class="w-full py-2.5 px-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl text-xs font-extrabold tracking-wide shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer flex items-center justify-center gap-1.5 transform hover:scale-[1.02] active:scale-[0.98]">
                                            <span>Lihat Detail Lengkap</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </button>
                                    </div>
                                `;

                                const marker = L.marker([lat, lng], { 
                                    icon: createIcon(isOperasional)
                                })
                                .bindPopup(popupContent, {
                                    offset: [0, -10],
                                    closeButton: false,
                                    className: 'premium-popup'
                                })
                                .addTo(map);
                                
                                // Add mouseover event to open popup automatically on hover
                                marker.on('mouseover', function(e) {
                                    this.openPopup();
                                });
                                
                                // Store ID in marker to find it later
                                marker.sppgId = sppg.id;
                                markers.push(marker);
                                
                                bounds.extend([lat, lng]);
                                hasValidCoords = true;
                            }
                        }
                    });

                    // Fit bounds if we have markers, but don't zoom in too much (maxZoom 14)
                    if (hasValidCoords && markers.length > 0 && !geojsonLayer) {
                        map.fitBounds(bounds, { padding: [50, 50], maxZoom: 14 });
                    }
                },
                
                focusMarker(sppg) {
                    if (sppg.koordinat_lokasi && sppg.koordinat_lokasi.includes(',')) {
                        const parts = sppg.koordinat_lokasi.split(',');
                        const lat = parseFloat(parts[0].trim());
                        const lng = parseFloat(parts[1].trim());
                        
                        if (!isNaN(lat) && !isNaN(lng)) {
                            // Find marker and open popup
                            const marker = markers.find(m => m.sppgId === sppg.id);
                            if (marker) {
                                // Close modal if open on mobile so user can see the map
                                this.closeModal();

                                // Fly to marker
                                map.flyTo([lat, lng], 16, {
                                    animate: true,
                                    duration: 1.5
                                });
                                
                                // Wait for flyTo to finish before opening popup
                                setTimeout(() => {
                                    marker.openPopup();
                                }, 1500);
                            }
                        }
                    }
                }
            };
        });
        });
    </script>
    <!-- Gallery Modal (3D Coverflow Style Slider) -->
    <div x-show="showGalleryModal" 
         x-cloak
         style="display: none;"
         class="fixed inset-0 z-[99999] overflow-hidden flex items-center justify-center p-4 sm:p-6 select-none"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">

        <!-- Dark Backdrop with Glassmorphism -->
        <div class="fixed inset-0 bg-black/90 backdrop-blur-lg transition-opacity" @click="closeGallery()"></div>

        <!-- Gallery Modal Box Container -->
        <div class="relative w-full max-w-5xl z-10 flex flex-col items-center justify-between min-h-[500px] max-h-[92vh]">
            
            <!-- Header Bar -->
            <div class="w-full flex items-center justify-between px-5 py-3.5 bg-white/10 backdrop-blur-md rounded-2xl border border-white/15 text-white mb-2 shadow-2xl">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center border border-emerald-500/40 shadow-inner">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm sm:text-base leading-tight text-white" x-text="activeSppg ? activeSppg.nama_sppg : 'Galeri Foto'"></h3>
                        <p class="text-xs text-emerald-300/80 font-medium" x-text="'Foto ' + (currentGalleryIndex + 1) + ' dari ' + galleryPhotos.length"></p>
                    </div>
                </div>

                <button @click="closeGallery()" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all transform hover:rotate-90 hover:scale-105 border border-white/20 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- 3D Coverflow Container -->
            <div class="w-full flex-1 flex items-center justify-center relative perspective-1000 py-4 my-auto overflow-hidden">
                
                <!-- Left Arrow Button -->
                <button x-show="galleryPhotos.length > 1" 
                        @click="prevPhoto()" 
                        class="absolute left-2 sm:left-6 z-30 w-12 h-12 rounded-full bg-black/60 hover:bg-emerald-600 text-white backdrop-blur-md border border-white/20 flex items-center justify-center transition-all transform hover:scale-110 shadow-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </button>

                <!-- Coverflow Items Grid -->
                <div class="flex items-center justify-center gap-4 sm:gap-8 w-full max-w-4xl px-4">
                    
                    <!-- Left Preview Image (3D Tilted Coverflow) -->
                    <template x-if="galleryPhotos.length > 1">
                        <div @click="prevPhoto()" 
                             class="hidden md:block w-1/4 h-[300px] sm:h-[340px] rounded-2xl overflow-hidden border border-white/20 shadow-2xl cursor-pointer transition-all duration-500 opacity-45 hover:opacity-85 transform rotate-y-left">
                            <img :src="'/storage/' + galleryPhotos[(currentGalleryIndex - 1 + galleryPhotos.length) % galleryPhotos.length]" class="w-full h-full object-cover">
                        </div>
                    </template>

                    <!-- Center Active Highlight Image -->
                    <div class="w-full sm:w-3/4 md:w-1/2 h-[340px] sm:h-[420px] rounded-3xl overflow-hidden border-4 border-white/25 shadow-[0_20px_50px_rgba(0,0,0,0.8)] relative transition-all duration-500 z-20 group bg-black/80">
                        <img :src="'/storage/' + galleryPhotos[currentGalleryIndex]" class="w-full h-full object-contain">
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent p-4 flex justify-between items-end opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-xs text-white/80 font-medium" x-text="'Foto ' + (currentGalleryIndex + 1) + ' dari ' + galleryPhotos.length"></span>
                            <a :href="'/storage/' + galleryPhotos[currentGalleryIndex]" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-xs font-bold transition-all shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                <span>Buka Asli</span>
                            </a>
                        </div>
                    </div>

                    <!-- Right Preview Image (3D Tilted Coverflow) -->
                    <template x-if="galleryPhotos.length > 1">
                        <div @click="nextPhoto()" 
                             class="hidden md:block w-1/4 h-[300px] sm:h-[340px] rounded-2xl overflow-hidden border border-white/20 shadow-2xl cursor-pointer transition-all duration-500 opacity-45 hover:opacity-85 transform rotate-y-right">
                            <img :src="'/storage/' + galleryPhotos[(currentGalleryIndex + 1) % galleryPhotos.length]" class="w-full h-full object-cover">
                        </div>
                    </template>

                </div>

                <!-- Right Arrow Button -->
                <button x-show="galleryPhotos.length > 1" 
                        @click="nextPhoto()" 
                        class="absolute right-2 sm:right-6 z-30 w-12 h-12 rounded-full bg-black/60 hover:bg-emerald-600 text-white backdrop-blur-md border border-white/20 flex items-center justify-center transition-all transform hover:scale-110 shadow-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>

            </div>

            <!-- Footer Pagination Dots & Keyboard hint -->
            <div class="w-full flex flex-col items-center gap-3 mt-3">
                <div class="flex items-center gap-2">
                    <template x-for="(foto, i) in galleryPhotos" :key="i">
                        <button @click="currentGalleryIndex = i" 
                                :class="currentGalleryIndex === i ? 'w-8 bg-emerald-400 shadow-lg shadow-emerald-500/50' : 'w-2.5 bg-white/30 hover:bg-white/70'"
                                class="h-2.5 rounded-full transition-all duration-300 cursor-pointer"></button>
                    </template>
                </div>
                
                <p class="text-[11px] text-gray-400 font-medium tracking-wide">Tekan tombol &larr; &rarr; pada keyboard atau klik foto samping untuk menggeser galeri.</p>
            </div>

        </div>
    </div>
</body>
</html>
