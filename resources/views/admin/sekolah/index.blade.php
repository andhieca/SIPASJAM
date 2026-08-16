<x-app-layout>
    <div class="py-6" x-data="sekolahModal()">
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
                    <h3 class="text-base font-bold text-black uppercase underline tracking-wider" style="font-family: 'Times New Roman', Times, serif; font-size: 13pt;">LAPORAN DATA SEKOLAH</h3>
                    <p class="text-xs font-medium text-black mt-0.5">Kecamatan Pasirjambu, Kabupaten Bandung</p>
                </div>
            </div>
            <div class="print-meta" style="display: none;">Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</div>

            <!-- Alert Flash Message -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 flex items-center justify-between shadow-sm no-print">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold">✓</div>
                        <span class="font-medium text-sm">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Search and Actions Header -->
            <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 no-print">
                <form action="{{ route('sekolah.index') }}" method="GET" class="w-full sm:w-1/2 relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama sekolah, NPSN, alamat..." class="w-full rounded-2xl border-gray-200 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200 pl-11 pr-4 py-3 text-sm">
                    <svg class="w-5 h-5 absolute left-4 top-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </form>
                <div class="flex items-center gap-3">
                    <button onclick="window.print()" class="w-full sm:w-auto bg-white hover:bg-gray-50 text-gray-700 font-semibold py-3 px-5 rounded-2xl shadow-sm border border-gray-200 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Cetak
                    </button>
                    <button @click="open()" class="w-full sm:w-auto bg-pj-green-600 hover:bg-pj-green-700 text-white font-semibold py-3 px-6 rounded-2xl shadow-md transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah Data Sekolah</span>
                    </button>
                </div>
            </div>

            <!-- Per Page Selector -->
            <div class="mb-4 flex items-center gap-2 no-print">
                <span class="text-sm text-gray-500">Tampilkan</span>
                <form action="{{ route('sekolah.index') }}" method="GET" class="inline-flex items-center">
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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80 text-gray-600 text-xs font-bold uppercase tracking-wider">
                                <th class="p-4 rounded-tl-xl">#</th>
                                <th class="p-4 text-center">Foto</th>
                                <th class="p-4">Nama Sekolah</th>
                                <th class="p-4">NPSN</th>
                                <th class="p-4">Alamat</th>
                                <th class="p-4">Desa</th>
                                <th class="p-4">Lokasi</th>
                                <th class="p-4 text-center rounded-tr-xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse ($sekolahs as $sekolah)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="p-4 text-gray-500 font-medium">{{ $perPage === 'all' ? $loop->iteration : $loop->iteration + $sekolahs->firstItem() - 1 }}</td>
                                    <td class="p-4 text-center">
                                        @if($sekolah->foto_sekolah && count($sekolah->foto_sekolah) > 0)
                                            <div class="flex -space-x-2 overflow-hidden justify-center">
                                                @foreach(array_slice($sekolah->foto_sekolah, 0, 3) as $foto)
                                                    <img src="{{ Storage::url($foto) }}" class="w-9 h-9 rounded-xl border-2 border-white object-cover shadow-sm" alt="Foto Sekolah">
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Tanpa Foto</span>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        <div class="font-bold text-gray-900">{{ $sekolah->nama_sekolah }}</div>
                                    </td>
                                    <td class="p-4">
                                        @if($sekolah->npsn)
                                            <span class="px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold rounded-full">{{ $sekolah->npsn }}</span>
                                        @else
                                            <span class="text-xs text-gray-400 italic">-</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-xs text-gray-600 max-w-xs">{{ Str::limit($sekolah->alamat_sekolah, 60) ?? '-' }}</td>
                                    <td class="p-4 font-medium text-gray-600">{{ $sekolah->desa->nama_desa ?? '-' }}</td>
                                    <td class="p-4">
                                        @if($sekolah->koordinat_lokasi)
                                            <span class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1">
                                                📍 {{ $sekolah->koordinat_lokasi }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400 italic">-</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('sekolah.edit', $sekolah) }}" class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-xl transition-colors" title="Edit Data">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 012.828 0L20 6.828a2 2 0 010 2.828l-8.485 8.485M7 17h.01"/></svg>
                                            </a>
                                            <form action="{{ route('sekolah.destroy', $sekolah) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data sekolah ini?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-xl transition-colors" title="Hapus Data">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-12 text-center text-gray-400 font-medium">Data Sekolah belum ada. Klik tombol "+ Tambah Data Sekolah" untuk menambahkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if($perPage !== 'all' && method_exists($sekolahs, 'links'))
                        <div class="mt-6">
                            {{ $sekolahs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modal Tambah Sekolah -->
        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
            <div x-show="openModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>

            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                <div x-show="openModal" @click.away="openModal = false" class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
                    <form action="{{ route('sekolah.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="bg-white px-6 pt-6 pb-4 sm:p-8">
                            <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-100">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900" id="modal-title">Tambah Data Sekolah Baru</h3>
                                    <p class="text-xs text-gray-500 mt-1">Lengkapi informasi sekolah beserta titik lokasi dan foto.</p>
                                </div>
                                <button type="button" @click="openModal = false" class="text-gray-400 hover:text-gray-600 rounded-full p-2 hover:bg-gray-100 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <!-- Informasi Utama -->
                                <div class="col-span-1 sm:col-span-2">
                                    <h4 class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-3">1. Informasi Sekolah</h4>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Desa *</label>
                                    <select name="desa_id" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                                        <option value="">-- Pilih Desa --</option>
                                        @foreach($desas as $desa)
                                            <option value="{{ $desa->id }}">{{ $desa->nama_desa }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">NPSN</label>
                                    <input type="text" name="npsn" placeholder="Contoh: 20201234" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                                </div>

                                <div class="col-span-1 sm:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Sekolah *</label>
                                    <input type="text" name="nama_sekolah" required placeholder="Masukkan nama sekolah" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                                </div>

                                <div class="col-span-1 sm:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Sekolah</label>
                                    <textarea name="alamat_sekolah" rows="2" placeholder="Masukkan alamat lengkap sekolah" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200"></textarea>
                                </div>

                                <!-- Lokasi Spasial -->
                                <div class="col-span-1 sm:col-span-2 mt-2">
                                    <h4 class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-3">2. Titik Lokasi Peta</h4>
                                    
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Titik Lokasi (Latitude, Longitude)</label>
                                    <input type="text" id="koordinat_lokasi" name="koordinat_lokasi" @input="updateMapFromInput($event.target.value)" placeholder="-7.123456, 107.456789" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200 mb-2">
                                    
                                    <div class="text-xs text-gray-500 mb-2">Klik di peta bawah untuk menentukan titik lokasi sekolah secara otomatis:</div>
                                    <div id="map" class="w-full h-48 rounded-2xl border border-gray-200 shadow-inner"></div>
                                </div>

                                <!-- Foto Sekolah -->
                                <div class="col-span-1 sm:col-span-2 mt-2">
                                    <h4 class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-3">3. Foto Sekolah (Maksimal 3 Foto)</h4>
                                    
                                    <!-- Live Thumbnail Preview Grid -->
                                    <template x-if="photoPreviews.length > 0">
                                        <div class="grid grid-cols-3 gap-3 mb-3">
                                            <template x-for="(photo, index) in photoPreviews" :key="index">
                                                <div class="relative group rounded-xl overflow-hidden border-2 border-pj-green-400 shadow-sm bg-gray-50 aspect-square">
                                                    <img :src="photo.url" class="w-full h-full object-cover" alt="Pratinjau Foto Sekolah">
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
                                           id="foto_sekolah_input" 
                                           name="foto_sekolah[]" 
                                           multiple 
                                           accept="image/*" 
                                           @change="handlePhotoChange($event)"
                                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-pj-green-50 file:text-pj-green-700 hover:file:bg-pj-green-100 cursor-pointer">
                                    
                                    <p x-show="photoError" x-text="photoError" class="text-xs text-red-500 font-semibold mt-1.5"></p>
                                    <p class="text-xs text-gray-500 mt-1">Pilih 1 hingga 3 foto sekolah (maksimal 2MB per foto).</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse rounded-b-3xl border-t border-gray-100 gap-3">
                            <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-md px-6 py-2.5 bg-pj-green-600 text-base font-semibold text-white hover:bg-pj-green-700 focus:outline-none sm:w-auto sm:text-sm transition-colors">Simpan Data Sekolah</button>
                            <button type="button" @click="openModal = false" class="mt-3 sm:mt-0 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-base font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none sm:w-auto sm:text-sm transition-colors">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        .leaflet-container {
            z-index: 10 !important;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sekolahModal', () => ({
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
                    const input = document.getElementById('foto_sekolah_input');
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
                    
                    this.map = L.map('map').setView([-7.1233, 107.4735], 12);
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
