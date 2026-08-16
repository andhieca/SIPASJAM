<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Sebaran Koperasi Desa - Kecamatan Pasirjambu | SIGAP</title>
    
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
    </style>
</head>
<body class="h-screen w-screen flex flex-col-reverse md:flex-row relative bg-gray-50 overflow-hidden" x-data="kopdesMapData()">
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
                    <h1 class="text-lg font-bold text-gray-900 tracking-tight">SISTEM KOPERASI DESA</h1>
                    <p class="text-[11px] text-blue-600 font-medium">Kecamatan Pasirjambu • Real-time</p>
                </div>
            </div>
            <a href="{{ url('/') }}" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900 transition-colors shadow-inner" title="Kembali ke Beranda">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
        </div>

        <!-- Stats -->
        <div class="px-6 py-5 grid grid-cols-2 gap-3 border-b border-gray-100 bg-[#f8fafc]">
            <div class="bg-white rounded-xl p-3 text-center border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider mb-1">Total Koperasi</p>
                <p class="text-2xl font-bold text-gray-900" x-text="filteredKopdes.length">0</p>
            </div>
            <div class="bg-white rounded-xl p-3 text-center border border-gray-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="absolute inset-0 bg-blue-50 opacity-60"></div>
                <div class="relative z-10">
                    <p class="text-[10px] text-blue-700 font-semibold uppercase tracking-wider mb-1">Status Aktif</p>
                    <p class="text-2xl font-bold text-blue-600" x-text="totalFilteredAktif">0</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="p-6 border-b border-gray-100 space-y-4">
            <!-- Search -->
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" x-model="searchQuery" placeholder="Cari Koperasi, Ketua..." class="block w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl leading-5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm transition-all shadow-sm">
            </div>

            <!-- Select Desa -->
            <div>
                <label class="block text-[10px] text-gray-500 font-semibold uppercase tracking-wider mb-1.5 ml-1">Desa / Kelurahan</label>
                <select x-model="selectedDesa" class="block w-full py-2.5 pl-3 pr-8 bg-white border border-gray-200 text-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:border-blue-500 sm:text-sm shadow-sm transition-all cursor-pointer">
                    <option value="">Semua Desa</option>
                    @foreach($desas as $desa)
                        <option value="{{ $desa->id }}">{{ $desa->nama_desa }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- List -->
        <div class="flex-1 overflow-y-auto p-5 space-y-4 relative z-0">
            <template x-if="filteredKopdes.length === 0">
                <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-200 shadow-sm">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Tidak ada data Koperasi Desa yang sesuai dengan filter.</p>
                </div>
            </template>

            <template x-for="(k, index) in filteredKopdes" :key="k.id">
                <div @click="focusMarker(k)" class="group cursor-pointer bg-white border border-gray-100 rounded-2xl p-5 hover:border-blue-400 hover:shadow-[0_10px_40px_-10px_rgba(59,130,246,0.2)] transition-all duration-300 relative overflow-hidden transform hover:-translate-y-1">
                    
                    <div class="flex justify-between items-start mb-3 relative z-10">
                        <div class="pr-3">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold tracking-wider mb-2.5 bg-blue-50 text-blue-700 border border-blue-200 shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                <span x-text="(k.status || 'Aktif').toUpperCase()"></span>
                            </span>
                            <h3 class="font-bold text-gray-900 text-[15px] leading-snug transition-colors group-hover:text-blue-600" x-text="k.nama_kopdes"></h3>
                            <p class="text-[13px] text-gray-500 mt-1 font-medium" x-text="'Ketua: ' + (k.ketua_kopdes || '-')"></p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100 text-xs text-gray-500 relative z-10">
                        <div class="flex items-center gap-1.5 truncate pr-2 group-hover:text-gray-700 transition-colors">
                            <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="truncate font-medium" x-text="k.desa ? k.desa.nama_desa.toUpperCase() : '-'"></span>
                        </div>
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
            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-blue-50 border border-blue-200">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                <span class="text-[9px] font-bold tracking-wide uppercase text-blue-700">Live Sync</span>
            </div>
        </div>
    </aside>

    <!-- Main Content (Map) -->
    <main class="w-full h-[45%] md:h-full md:flex-1 relative z-10">
        <div id="leafletMap" class="w-full h-full"></div>
    </main>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('kopdesMapData', () => {
                let map = null;
                let geojsonLayer = null;
                let markers = [];

                return {
                    allKopdes: @json($kopdes),
                    desas: @json($desas),
                    searchQuery: '',
                    selectedDesa: '',

                    init() {
                        this.initMap();
                        this.$watch('searchQuery', () => this.updateMapMarkers());
                        this.$watch('selectedDesa', () => this.updateMapMarkers());
                    },

                    get filteredKopdes() {
                        return this.allKopdes.filter(k => {
                            const matchSearch = k.nama_kopdes.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                                (k.ketua_kopdes && k.ketua_kopdes.toLowerCase().includes(this.searchQuery.toLowerCase()));
                            const matchDesa = this.selectedDesa === '' || k.desa_id == this.selectedDesa;
                            return matchSearch && matchDesa;
                        });
                    },

                    get totalFilteredAktif() {
                        return this.filteredKopdes.filter(k => (k.status || 'Aktif') === 'Aktif').length;
                    },

                    focusMarker(k) {
                        if (k.koordinat_lokasi && k.koordinat_lokasi.includes(',')) {
                            const parts = k.koordinat_lokasi.split(',');
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
                                        fillColor: '#3b82f6',
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

                        this.filteredKopdes.forEach(k => {
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
                                            
                                            <div class="space-y-1 mb-3 text-xs text-gray-600 font-medium">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-3.5 h-3.5 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                    <span class="truncate">Ketua: <strong class="text-gray-900 font-semibold">${k.ketua_kopdes || '-'}</strong></span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    <span class="truncate">Desa: <strong class="text-gray-900 font-semibold">${k.desa ? k.desa.nama_desa : '-'}</strong></span>
                                                </div>
                                            </div>
                                        </div>
                                    `;

                                    const m = L.marker([lat, lng], { icon: createPulseIcon('#3b82f6') })
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
