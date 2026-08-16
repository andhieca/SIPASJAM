<x-app-layout>
        <div class="py-12" x-data="sppgModal">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Print-only official Kop Surat -->
            <div class="print-header" style="display: none;">
                <div class="flex items-center justify-between pb-3 mb-4" style="border-bottom: 3px double #000;">
                    <div class="w-24 flex justify-center shrink-0">
                        <img src="{{ asset('images/logo-kab-bandung.png') }}" class="w-16 h-20 object-contain" alt="Logo Kabupaten Bandung">
                    </div>
                    <div class="flex-1 text-center px-4">
                        <h1 class="text-xl font-black text-black tracking-wide uppercase leading-tight" style="font-family: 'Times New Roman', Times, serif; font-size: 16pt;">PEMERINTAH KABUPATEN BANDUNG</h1>
                        <h2 class="text-2xl font-black text-black tracking-widest uppercase leading-tight mt-0.5" style="font-family: 'Times New Roman', Times, serif; font-size: 18pt;">KECAMATAN PASIRJAMBU</h2>
                        <p class="text-xs font-medium text-black mt-1 leading-snug" style="font-family: 'Times New Roman', Times, serif; font-size: 10pt;">
                            Jl. Lapang Jenderal No. 100 Kecamatan Pasirjambu Telp. (022) 5927477 Email :
                        </p>
                        <p class="text-xs font-medium text-black leading-snug" style="font-family: 'Times New Roman', Times, serif; font-size: 10pt;">
                            <span class="underline">kec_pasirjambu@bandungkab.go.id</span> Website : kecamatanpasirjambu.bandungkab.go.id
                        </p>
                    </div>
                    <div class="w-24 shrink-0"></div>
                </div>
                <div class="text-center mb-6">
                    <h3 class="text-base font-bold text-black uppercase underline tracking-wider" style="font-family: 'Times New Roman', Times, serif; font-size: 13pt;">LAPORAN DATA SPPG</h3>
                    <p class="text-xs font-medium text-black mt-0.5">Kecamatan Pasirjambu, Kabupaten Bandung</p>
                </div>
            </div>
            <div class="print-meta" style="display: none;">Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</div>

            <!-- Search and Actions Header -->
            <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 no-print">
                <form action="{{ route('sppg.index') }}" method="GET" class="w-full sm:w-1/2 relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama SPPG, yayasan, atau ketua..." class="w-full rounded-full border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200 focus:ring-opacity-50 pl-10 pr-4 py-2">
                    <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </form>
                <div class="flex items-center gap-3">
                    <button onclick="window.print()" class="w-full sm:w-auto bg-white hover:bg-gray-50 text-gray-700 font-semibold py-2 px-5 rounded-full shadow-sm border border-gray-200 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Cetak
                    </button>
                    <button @click="open()" class="w-full sm:w-auto bg-pj-green-600 hover:bg-pj-green-700 text-white font-semibold py-2 px-6 rounded-full shadow-md transition-all transform hover:-translate-y-0.5">
                        + Tambah Data SPPG
                    </button>
                </div>
            </div>

            <!-- Per Page Selector -->
            <div class="mb-4 flex items-center gap-2 no-print">
                <span class="text-sm text-gray-500">Tampilkan</span>
                <form action="{{ route('sppg.index') }}" method="GET" class="inline-flex items-center">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="per_page" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm py-1.5 px-3 focus:border-pj-green-500 focus:ring focus:ring-pj-green-200 cursor-pointer">
                        @foreach([10, 50, 100, 250, 'all'] as $opt)
                            <option value="{{ $opt }}" {{ (string) $perPage === (string) $opt ? 'selected' : '' }}>{{ $opt === 'all' ? 'Semua' : $opt }}</option>
                        @endforeach
                    </select>
                </form>
                <span class="text-sm text-gray-500">data per halaman</span>
            </div>

            <!-- Table Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                                <th class="p-4 rounded-tl-xl">#</th>
                                <th class="p-4">Nama SPPG</th>
                                <th class="p-4">Desa</th>
                                <th class="p-4">Alamat</th>
                                <th class="p-4">Yayasan</th>
                                <th class="p-4">Ketua</th>
                                <th class="p-4">Penerima Manfaat</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-center">Foto</th>
                                <th class="p-4 text-center rounded-tr-xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($sppgs as $sppg)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="p-4 text-gray-500">{{ $perPage === 'all' ? $loop->iteration : $loop->iteration + $sppgs->firstItem() - 1 }}</td>
                                    <td class="p-4 font-semibold text-gray-800">{{ $sppg->nama_sppg }}</td>
                                    <td class="p-4">{{ $sppg->desa->nama_desa ?? '-' }}</td>
                                    <td class="p-4 text-xs text-gray-600 max-w-xs">{{ $sppg->alamat ?? '-' }}</td>
                                    <td class="p-4">{{ $sppg->nama_yayasan ?? '-' }}</td>
                                    <td class="p-4">{{ $sppg->ketua_sppg ?? '-' }}</td>
                                    <td class="p-4">{{ number_format($sppg->jumlah_penerima_manfaat, 0, ',', '.') }} orang</td>
                                    <td class="p-4">
                                        @if($sppg->status === 'Operasional')
                                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Operasional</span>
                                        @else
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">Belum Operasional</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        @if($sppg->foto_sppg && count($sppg->foto_sppg) > 0)
                                            <div class="flex items-center justify-center -space-x-2">
                                                @foreach(array_slice($sppg->foto_sppg, 0, 3) as $foto)
                                                    <img src="{{ Storage::url($foto) }}" class="w-8 h-8 rounded-full border-2 border-white object-cover" alt="Foto">
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td class="p-4 flex justify-center gap-2">
                                        <a href="{{ route('sppg.edit', $sppg->id) }}" class="text-blue-500 hover:text-blue-700 bg-blue-50 p-2 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form action="{{ route('sppg.destroy', $sppg->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 p-2 rounded-lg transition-colors" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-8 text-center text-gray-500 font-medium">Data tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if($perPage !== 'all' && method_exists($sppgs, 'links'))
                        <div class="mt-6">
                            {{ $sppgs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Alpine.js Modal Create -->
        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
            <div x-show="openModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity"></div>

            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div x-show="openModal" @click.away="openModal = false" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <form action="{{ route('sppg.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-xl leading-6 font-semibold text-gray-900 mb-6" id="modal-title">Tambah Data SPPG Baru</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Pilih Desa *</label>
                                    <select name="desa_id" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                                        <option value="">-- Pilih Desa --</option>
                                        @foreach($desas as $desa)
                                            <option value="{{ $desa->id }}">{{ $desa->nama_desa }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Nama SPPG *</label>
                                    <input type="text" name="nama_sppg" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Yayasan</label>
                                    <input type="text" name="nama_yayasan" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Ketua SPPG</label>
                                    <input type="text" name="ketua_sppg" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                                    <input type="text" name="alamat" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Koordinat Lokasi</label>
                                    <input type="text" name="koordinat_lokasi" id="koordinat_lokasi" @input="updateMapFromInput($event.target.value)" placeholder="Cth: -7.123, 107.123" class="mt-1 mb-3 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                                    
                                    <div id="map" class="h-64 w-full rounded-lg border border-gray-300 shadow-sm z-10 relative"></div>
                                    <p class="text-xs text-gray-500 mt-1">Klik pada peta untuk otomatis mengisi koordinat lokasi, atau ketik koordinat di atas untuk menggeser peta.</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jumlah Penerima Manfaat</label>
                                    <input type="number" name="jumlah_penerima_manfaat" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status</label>
                                    <select name="status" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                                        <option value="Operasional">Operasional</option>
                                        <option value="Belum Operasional">Belum Operasional</option>
                                    </select>
                                </div>
                                <div class="col-span-1 sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto SPPG (Maksimal 3 Foto)</label>
                                    
                                    <!-- Live Thumbnail Preview Grid -->
                                    <template x-if="photoPreviews.length > 0">
                                        <div class="grid grid-cols-3 gap-3 mb-3">
                                            <template x-for="(photo, index) in photoPreviews" :key="index">
                                                <div class="relative group rounded-xl overflow-hidden border-2 border-pj-green-400 shadow-sm bg-gray-50 aspect-square">
                                                    <img :src="photo.url" class="w-full h-full object-cover" alt="Pratinjau Foto">
                                                    <span class="absolute top-1.5 left-1.5 bg-black/60 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-0.5 rounded-full" x-text="'Foto ' + (index + 1)"></span>
                                                    <button type="button" @click="removePreviewPhoto(index)" class="absolute top-1.5 right-1.5 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow-md transition-all transform hover:scale-110" title="Hapus foto ini">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                    <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-1.5 text-[10px] text-white truncate px-2 font-medium" x-text="photo.name"></div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                    <input type="file" 
                                           id="foto_sppg_input" 
                                           name="foto_sppg[]" 
                                           multiple 
                                           accept="image/*" 
                                           @change="handlePhotoChange($event)"
                                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-pj-green-50 file:text-pj-green-700 hover:file:bg-pj-green-100 cursor-pointer">
                                    
                                    <p x-show="photoError" x-text="photoError" class="text-xs text-red-500 font-semibold mt-1.5"></p>
                                    <p class="text-xs text-gray-500 mt-1">Pilih 1 hingga 3 foto (maksimal 2MB per foto).</p>
                                </div>
                            </div>


                        </div>
                        <div class="bg-gray-50 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl border-t border-gray-100">
                            <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-pj-green-600 text-base font-medium text-white hover:bg-pj-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Simpan Data</button>
                            <button type="button" @click="openModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        /* Fix leaflet z-index issue within modals */
        .leaflet-container {
            z-index: 10 !important;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sppgModal', () => ({
                openModal: false,
                map: null,
                marker: null,
                photoPreviews: [],
                photoError: '',
                handlePhotoChange(e) {
                    const input = e.target;
                    let files = Array.from(input.files);
                    this.photoError = '';
                    
                    if (files.length > 3) {
                        this.photoError = 'Maksimal 3 foto yang dapat dipilih. 3 foto pertama digunakan secara otomatis.';
                        files = files.slice(0, 3);
                        const dt = new DataTransfer();
                        files.forEach(f => dt.items.add(f));
                        input.files = dt.files;
                    }
                    
                    this.photoPreviews = files.map(file => ({
                        url: URL.createObjectURL(file),
                        name: file.name,
                        size: (file.size / 1024 / 1024).toFixed(2)
                    }));
                },
                removePreviewPhoto(index) {
                    this.photoPreviews.splice(index, 1);
                    const input = document.getElementById('foto_sppg_input');
                    if (input && input.files) {
                        const dt = new DataTransfer();
                        Array.from(input.files).forEach((file, i) => {
                            if (i !== index) dt.items.add(file);
                        });
                        input.files = dt.files;
                    }
                    if (this.photoPreviews.length <= 3) {
                        this.photoError = '';
                    }
                },
                initMap() {
                    if (this.map) return;
                    
                    // Coordinates roughly centered on Kabupaten Bandung / Pasirjambu
                    this.map = L.map('map').setView([-7.1233, 107.4735], 11);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(this.map);

                    this.map.on('click', (e) => {
                        const lat = e.latlng.lat.toFixed(6);
                        const lng = e.latlng.lng.toFixed(6);
                        
                        document.getElementById('koordinat_lokasi').value = lat + ', ' + lng;

                        if (this.marker) {
                            this.map.removeLayer(this.marker);
                        }
                        this.marker = L.marker([lat, lng]).addTo(this.map);
                    });
                },
                updateMapFromInput(val) {
                    if (!this.map) return;
                    if (val && val.includes(',')) {
                        const parts = val.split(',');
                        const lat = parseFloat(parts[0].trim());
                        const lng = parseFloat(parts[1].trim());
                        
                        if (!isNaN(lat) && !isNaN(lng)) {
                            this.map.setView([lat, lng], 13);
                            if (this.marker) {
                                this.map.removeLayer(this.marker);
                            }
                            this.marker = L.marker([lat, lng]).addTo(this.map);
                        }
                    }
                },
                open() {
                    this.openModal = true;
                    // Give modal time to transition before invalidating size
                    setTimeout(() => {
                        this.initMap();
                        this.map.invalidateSize();
                    }, 300);
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>
