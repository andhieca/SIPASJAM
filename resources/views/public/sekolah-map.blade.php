<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Sebaran Sekolah - Kecamatan Pasirjambu | SIGAP</title>
    
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
<body class="h-screen w-screen flex flex-col-reverse md:flex-row relative bg-gray-50 overflow-hidden" x-data="sekolahMapData()">
    @include('components.page-loader')

    <!-- Sidebar -->
    <aside style="background-color: #f8fafc;" class="w-full md:w-[420px] h-[55%] md:h-full flex flex-col border-t md:border-t-0 md:border-r border-gray-200 shadow-[0_-10px_30px_rgba(0,0,0,0.05)] md:shadow-[10px_0_30px_rgba(0,0,0,0.08)] relative z-20 shrink-0">
        
        <!-- Header -->
        <div class="p-6 pb-4 flex items-center justify-between border-b border-gray-100 bg-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 flex items-center justify-center shrink-0">
                    <img src="{{ asset('images/logo-kab-bandung.png') }}" alt="Logo Kabupaten Bandung" class="w-full h-full object-contain drop-shadow-md">
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-900 tracking-tight">SEBARAN SEKOLAH</h1>
                    <p class="text-[11px] text-cyan-600 font-medium">Kecamatan Pasirjambu • Real-time</p>
                </div>
            </div>
            <a href="{{ url('/') }}" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900 transition-colors shadow-inner" title="Kembali ke Beranda">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
        </div>

        <!-- Stats -->
        <div class="px-6 py-5 grid grid-cols-2 gap-3 border-b border-gray-100 bg-[#f8fafc]">
            <div class="bg-white rounded-xl p-3 text-center border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider mb-1">Total Sekolah</p>
                <p class="text-2xl font-bold text-gray-900" x-text="filteredSekolah.length">0</p>
            </div>
            <div class="bg-white rounded-xl p-3 text-center border border-gray-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="absolute inset-0 bg-cyan-50 opacity-60"></div>
                <div class="relative z-10">
                    <p class="text-[10px] text-cyan-700 font-semibold uppercase tracking-wider mb-1">Ber-NPSN</p>
                    <p class="text-2xl font-bold text-cyan-600" x-text="totalFilteredNpsn">0</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="p-6 border-b border-gray-100 space-y-4">
            <!-- Search -->
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400 group-focus-within:text-cyan-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" x-model="searchQuery" placeholder="Cari nama sekolah, NPSN, alamat..." class="block w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl leading-5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 sm:text-sm transition-all shadow-sm">
            </div>

            <!-- Filter Desa -->
            <div>
                <select x-model="selectedDesa" class="block w-full py-2.5 px-3 bg-white border border-gray-200 rounded-xl leading-5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 sm:text-sm transition-all shadow-sm">
                    <option value="">Semua Desa</option>
                    <template x-for="desa in desas" :key="desa.id">
                        <option :value="desa.id" x-text="desa.nama_desa"></option>
                    </template>
                </select>
            </div>
        </div>

        <!-- List Data Sekolah -->
        <div class="flex-1 overflow-y-auto p-6 space-y-3 custom-scrollbar">
            <template x-if="filteredSekolah.length === 0">
                <div class="text-center py-12">
                    <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-sm text-gray-500 font-medium">Data sekolah tidak ditemukan</p>
                </div>
            </template>

            <template x-for="item in filteredSekolah" :key="item.id">
                <div @click="focusMarker(item)" 
                     :class="activeSekolahId === item.id ? 'border-cyan-500 bg-cyan-50/50 shadow-md ring-2 ring-cyan-500/20' : 'border-gray-200/80 bg-white hover:border-cyan-300 hover:shadow-sm'"
                     class="p-4 rounded-2xl border transition-all duration-200 cursor-pointer group">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-bold text-gray-900 group-hover:text-cyan-600 transition-colors truncate" x-text="item.nama_sekolah"></h3>
                            <p class="text-xs text-gray-500 mt-0.5 truncate" x-text="item.desa ? 'Desa ' + item.desa.nama_desa : '-'"></p>
                        </div>
                        <template x-if="item.npsn">
                            <span class="px-2.5 py-1 bg-cyan-100 text-cyan-800 text-[10px] font-bold rounded-full shrink-0" x-text="'NPSN: ' + item.npsn"></span>
                        </template>
                    </div>

                    <div class="mt-3 flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-gray-100">
                        <span class="flex items-center gap-1 text-[11px] truncate max-w-[200px]" x-text="item.alamat_sekolah ? item.alamat_sekolah : 'Alamat belum diisi'"></span>
                        <template x-if="item.koordinat_lokasi">
                            <span class="text-emerald-600 font-semibold text-[11px]">📍 Ada Lokasi</span>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </aside>

    <!-- Map Container -->
    <main class="flex-1 h-[45%] md:h-full relative z-10">
        <div id="sekolahMap" class="w-full h-full"></div>
    </main>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sekolahMapData', () => ({
                map: null,
                desas: @json($desas ?? []),
                sekolahs: @json($sekolahs ?? []),
                searchQuery: '',
                selectedDesa: '',
                activeSekolahId: null,
                markers: {},

                get filteredSekolah() {
                    return this.sekolahs.filter(item => {
                        const matchSearch = !this.searchQuery || 
                            item.nama_sekolah.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            (item.npsn && item.npsn.toLowerCase().includes(this.searchQuery.toLowerCase())) ||
                            (item.alamat_sekolah && item.alamat_sekolah.toLowerCase().includes(this.searchQuery.toLowerCase()));

                        const matchDesa = !this.selectedDesa || item.desa_id == this.selectedDesa;

                        return matchSearch && matchDesa;
                    });
                },

                get totalFilteredNpsn() {
                    return this.filteredSekolah.filter(s => s.npsn).length;
                },

                init() {
                    this.initMap();
                },

                initMap() {
                    this.map = L.map('sekolahMap', { zoomControl: false }).setView([-7.1233, 107.4735], 12);
                    L.control.zoom({ position: 'topright' }).addTo(this.map);

                    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                        attribution: '&copy; OpenStreetMap &copy; CARTO'
                    }).addTo(this.map);

                    // Load GeoJSON kecamatan boundary
                    fetch('/geojson/pasirjambu.geojson')
                        .then(res => res.json())
                        .then(data => {
                            L.geoJSON(data, {
                                style: {
                                    color: '#0891b2',
                                    weight: 2,
                                    opacity: 0.7,
                                    fillColor: '#06b6d4',
                                    fillOpacity: 0.08
                                }
                            }).addTo(this.map);
                        }).catch(e => console.error(e));

                    this.renderMarkers();
                },

                renderMarkers() {
                    // Remove existing markers
                    Object.values(this.markers).forEach(m => this.map.removeLayer(m));
                    this.markers = {};

                    this.sekolahs.forEach(item => {
                        if (!item.koordinat_lokasi || !item.koordinat_lokasi.includes(',')) return;
                        
                        const parts = item.koordinat_lokasi.split(',');
                        const lat = parseFloat(parts[0].trim());
                        const lng = parseFloat(parts[1].trim());

                        if (isNaN(lat) || isNaN(lng)) return;

                        // Create custom icon
                        const icon = L.divIcon({
                            className: 'custom-div-icon',
                            html: `
                                <div class="pulse-marker relative w-9 h-9 rounded-full bg-cyan-600 border-2 border-white shadow-lg flex items-center justify-center text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                                </div>
                            `,
                            iconSize: [36, 36],
                            iconAnchor: [18, 18]
                        });

                        const popupContent = `
                            <div class="p-5 max-w-xs font-sans">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2.5 py-0.5 bg-cyan-100 text-cyan-800 text-[10px] font-bold rounded-full uppercase">Sekolah</span>
                                    ${item.npsn ? `<span class="text-xs text-gray-500 font-semibold">NPSN: ${item.npsn}</span>` : ''}
                                </div>
                                <h3 class="text-base font-bold text-gray-900 leading-snug mb-1">${item.nama_sekolah}</h3>
                                <p class="text-xs text-gray-500 mb-3">${item.desa ? 'Desa ' + item.desa.nama_desa : '-'}</p>
                                
                                ${item.alamat_sekolah ? `<p class="text-xs text-gray-600 bg-gray-50 p-2.5 rounded-xl mb-3 border border-gray-100">${item.alamat_sekolah}</p>` : ''}
                                
                                ${item.foto_sekolah && item.foto_sekolah.length > 0 ? `
                                    <div class="flex gap-1.5 overflow-hidden rounded-xl">
                                        ${item.foto_sekolah.slice(0, 3).map(f => `<img src="/storage/${f}" class="w-16 h-16 object-cover rounded-lg border border-gray-200">`).join('')}
                                    </div>
                                ` : ''}
                            </div>
                        `;

                        const marker = L.marker([lat, lng], { icon: icon }).addTo(this.map);
                        marker.bindPopup(popupContent);

                        marker.on('click', () => {
                            this.activeSekolahId = item.id;
                        });

                        this.markers[item.id] = marker;
                    });
                },

                focusMarker(item) {
                    this.activeSekolahId = item.id;
                    if (this.markers[item.id]) {
                        const m = this.markers[item.id];
                        this.map.setView(m.getLatLng(), 15, { animate: true });
                        m.openPopup();
                    } else if (item.koordinat_lokasi && item.koordinat_lokasi.includes(',')) {
                        const parts = item.koordinat_lokasi.split(',');
                        const lat = parseFloat(parts[0].trim());
                        const lng = parseFloat(parts[1].trim());
                        if (!isNaN(lat) && !isNaN(lng)) {
                            this.map.setView([lat, lng], 15, { animate: true });
                        }
                    }
                }
            }));
        });
    </script>
</body>
</html>
