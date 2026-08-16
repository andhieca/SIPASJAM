<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Peta Kepadatan Penduduk Desa - Kecamatan Pasirjambu</title>
    
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

        .legend-box {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(229, 231, 235, 0.8);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="h-screen w-screen flex flex-col-reverse md:flex-row relative bg-gray-50 overflow-hidden" x-data="desaMapData()">
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
                    <h1 class="text-lg font-bold text-gray-900 tracking-tight">PETA SEBARAN DESA</h1>
                    <p class="text-[11px] text-[#059669] font-medium">Kepadatan Penduduk • Kecamatan Pasirjambu</p>
                </div>
            </div>
            <a href="{{ url('/') }}" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900 transition-colors shadow-inner" title="Kembali ke Beranda">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
        </div>

        <!-- Stats -->
        <div class="px-6 py-5 grid grid-cols-3 gap-3 border-b border-gray-100 bg-[#f8fafc]">
            <div class="bg-white rounded-xl p-3 text-center border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider mb-1">Total Desa</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalDesa }}</p>
            </div>
            <div class="bg-white rounded-xl p-3 text-center border border-gray-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="absolute inset-0 bg-[#ecfdf5] opacity-50"></div>
                <div class="relative z-10">
                    <p class="text-[10px] text-[#059669] font-semibold uppercase tracking-wider mb-1">Penduduk</p>
                    <p class="text-xl font-bold text-[#047857]">{{ number_format($totalPenduduk, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="bg-white rounded-xl p-3 text-center border border-gray-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="absolute inset-0 bg-[#fef3c7] opacity-50"></div>
                <div class="relative z-10">
                    <p class="text-[10px] text-[#d97706] font-semibold uppercase tracking-wider mb-1">Total Luas</p>
                    <p class="text-xl font-bold text-[#f59e0b]">{{ number_format($totalLuas, 1, ',', '.') }} <span class="text-[10px] font-normal">km²</span></p>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="p-6 border-b border-gray-100 space-y-4 bg-white">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400 group-focus-within:text-pj-green-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" x-model="searchQuery" placeholder="Cari Desa..." class="block w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl leading-5 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pj-green-500/20 focus:border-pj-green-500 sm:text-sm transition-all shadow-sm">
            </div>
        </div>

        <!-- Village List -->
        <div class="flex-1 overflow-y-auto p-5 space-y-3 relative z-0">
            <template x-if="filteredDesas.length === 0">
                <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-200 shadow-sm">
                    <p class="text-gray-500 text-sm font-medium">Tidak ada desa yang sesuai dengan pencarian.</p>
                </div>
            </template>

            <template x-for="(desa, index) in filteredDesas" :key="desa.id">
                <div @click="focusVillage(desa)" :style="`animation-delay: ${index * 0.04}s`" class="group cursor-pointer bg-white border border-gray-100 rounded-2xl p-4 hover:border-emerald-300 hover:shadow-[0_10px_30px_-10px_rgba(16,185,129,0.2)] transition-all duration-300 relative overflow-hidden transform hover:-translate-y-1 fade-in-up">
                    
                    <div class="flex justify-between items-start mb-2 relative z-10">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="w-3 h-3 rounded-full shrink-0" :style="`background-color: ${getColor(desa.jumlah_penduduk)};`"></span>
                                <h3 class="font-bold text-gray-900 text-base leading-snug" x-text="`Desa ${desa.nama_desa}`"></h3>
                            </div>
                            <p class="text-xs text-gray-500 font-medium ml-5" x-text="`Luas: ${formatNumber(desa.luas_wilayah)} km²`"></p>
                        </div>
                        <div class="text-right shrink-0 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-100">
                            <p class="text-[9px] text-emerald-700 font-bold uppercase tracking-wider mb-0.5">PENDUDUK</p>
                            <p class="font-bold text-emerald-800 text-base leading-none"><span x-text="formatNumber(desa.jumlah_penduduk)"></span> <span class="text-[10px] text-emerald-600 font-normal">jiwa</span></p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between mt-3 pt-2.5 border-t border-gray-100 text-xs text-gray-500 relative z-10">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span class="font-medium" x-text="`Kepadatan: ${calculateDensity(desa.jumlah_penduduk, desa.luas_wilayah)} jiwa/km²`"></span>
                        </div>
                        <span class="text-[11px] font-semibold text-emerald-600 group-hover:underline">Lihat di Peta &rarr;</span>
                    </div>
                </div>
            </template>
        </div>

        <!-- Footer status -->
        <div class="p-3 border-t border-gray-100 bg-white flex items-center justify-between z-10">
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-medium text-gray-500">Peta Tematik Kependudukan Desa</span>
            </div>
            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[9px] font-bold tracking-wide uppercase text-emerald-700">Choropleth Mode</span>
            </div>
        </div>
    </aside>

    <!-- Main Content (Map) -->
    <main class="w-full h-[45%] md:h-full md:flex-1 relative z-10">
        <div id="leafletMap" class="w-full h-full"></div>

        <!-- Legend Overlay -->
        <div class="absolute bottom-6 right-6 z-[400] legend-box p-4 rounded-2xl max-w-xs w-full hidden sm:block">
            <h4 class="text-xs font-bold text-gray-900 tracking-wider uppercase mb-2">Tingkat Kependudukan</h4>
            <div class="space-y-1.5 text-xs">
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 rounded" style="background-color: #064e3b;"></span>
                    <span class="text-gray-700 font-medium">> 14.000 jiwa (Sangat Tinggi)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 rounded" style="background-color: #047857;"></span>
                    <span class="text-gray-700 font-medium">11.000 - 14.000 jiwa (Tinggi)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 rounded" style="background-color: #10b981;"></span>
                    <span class="text-gray-700 font-medium">9.000 - 11.000 jiwa (Sedang)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 rounded" style="background-color: #34d399;"></span>
                    <span class="text-gray-700 font-medium">7.000 - 9.000 jiwa (Cukup)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 rounded" style="background-color: #a7f3d0;"></span>
                    <span class="text-gray-700 font-medium">< 7.000 jiwa (Rendah)</span>
                </div>
            </div>
        </div>
    </main>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('desaMapData', () => {
                // Closure variables for Leaflet to prevent Alpine Proxy issues
                let map = null;
                let geojsonLayer = null;
                let villageLayers = {};

                return {
                    desas: @json($desas),
                    searchQuery: '',

                    init() {
                        this.initMap();
                    },

                    get filteredDesas() {
                        return this.desas.filter(d => 
                            d.nama_desa.toLowerCase().includes(this.searchQuery.toLowerCase())
                        ).sort((a, b) => b.jumlah_penduduk - a.jumlah_penduduk);
                    },

                    formatNumber(num) {
                        return num ? num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") : '0';
                    },

                    calculateDensity(penduduk, luas) {
                        if (!penduduk || !luas || parseFloat(luas) === 0) return '0';
                        return Math.round(penduduk / parseFloat(luas)).toLocaleString('id-ID');
                    },

                    getColor(penduduk) {
                        const count = parseInt(penduduk) || 0;
                        if (count > 14000) return '#064e3b';
                        if (count > 11000) return '#047857';
                        if (count > 9000)  return '#10b981';
                        if (count > 7000)  return '#34d399';
                        return '#a7f3d0';
                    },

                    initMap() {
                        map = L.map('leafletMap', {
                            zoomControl: false
                        }).setView([-7.1233, 107.4735], 12);

                        L.control.zoom({ position: 'topright' }).addTo(map);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                            maxZoom: 19
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
                                        const desaData = this.desas.find(d => d.nama_desa.toLowerCase() === desaName.toLowerCase());
                                        const penduduk = desaData ? desaData.jumlah_penduduk : 0;
                                        const fillColor = this.getColor(penduduk);

                                        return {
                                            color: '#047857',
                                            weight: 2,
                                            opacity: 0.9,
                                            fillColor: fillColor,
                                            fillOpacity: 0.65,
                                            dashArray: ''
                                        };
                                    },
                                    onEachFeature: (feature, layer) => {
                                        const desaName = feature.properties.NAMOBJ || '';
                                        const desaData = this.desas.find(d => d.nama_desa.toLowerCase() === desaName.toLowerCase());
                                        
                                        if (desaName) {
                                            villageLayers[desaName.toLowerCase()] = layer;
                                        }

                                        const pendudukText = desaData ? `${this.formatNumber(desaData.jumlah_penduduk)} jiwa` : 'Data tidak tersedia';
                                        const luasText = desaData ? `${desaData.luas_wilayah} km²` : '-';
                                        const densityText = desaData ? `${this.calculateDensity(desaData.jumlah_penduduk, desaData.luas_wilayah)} jiwa/km²` : '-';

                                        // Hover animation & style update
                                        layer.on({
                                            mouseover: (e) => {
                                                const l = e.target;
                                                l.setStyle({
                                                    fillOpacity: 0.85,
                                                    weight: 4,
                                                    color: '#022c22'
                                                });
                                                l.bringToFront();
                                            },
                                            mouseout: (e) => {
                                                if (geojsonLayer) {
                                                    geojsonLayer.resetStyle(e.target);
                                                }
                                            }
                                        });

                                        layer.bindTooltip(`
                                            <div class="px-2 py-1 text-center">
                                                <div class="font-bold text-gray-900 text-sm">DESA ${desaName.toUpperCase()}</div>
                                                <div class="text-xs font-semibold text-emerald-700 mt-1">👥 ${pendudukText}</div>
                                                <div class="text-[10px] text-gray-500 mt-0.5">📐 Luas: ${luasText} | 📊 Kepadatan: ${densityText}</div>
                                            </div>
                                        `, {
                                            sticky: true,
                                            className: 'border border-emerald-200 shadow-xl rounded-xl bg-white/95 backdrop-blur-md px-3 py-2',
                                            direction: 'top',
                                            offset: [0, -10]
                                        });
                                    }
                                }).addTo(map);

                                map.fitBounds(geojsonLayer.getBounds(), { padding: [50, 50] });
                            })
                            .catch(err => console.error('Error loading GeoJSON:', err));
                    },

                    focusVillage(desa) {
                        const layer = villageLayers[desa.nama_desa.toLowerCase()];
                        if (layer && map) {
                            map.flyToBounds(layer.getBounds(), {
                                padding: [50, 50],
                                maxZoom: 14,
                                duration: 1.5
                            });
                            
                            setTimeout(() => {
                                layer.openTooltip();
                            }, 1500);
                        }
                    }
                };
            });
        });
    </script>
</body>
</html>
