<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Edit Data UMKM') }} - {{ $umkm->nama_umkm }}
            </h2>
            <a href="{{ route('umkm.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-colors">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6" x-data="umkmEditModal()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100">
                <div class="p-6 sm:p-8">
                    <form action="{{ route('umkm.update', $umkm) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            
                            <!-- Informasi Utama -->
                            <div class="col-span-1 sm:col-span-2">
                                <h4 class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-3">1. Informasi Utama Usaha</h4>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Desa *</label>
                                <select name="desa_id" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                                    <option value="">-- Pilih Desa --</option>
                                    @foreach($desas as $desa)
                                        <option value="{{ $desa->id }}" {{ $umkm->desa_id == $desa->id ? 'selected' : '' }}>{{ $desa->nama_desa }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori Usaha *</label>
                                <select name="kategori" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                                    <option value="Kuliner" {{ $umkm->kategori == 'Kuliner' ? 'selected' : '' }}>Kuliner</option>
                                    <option value="Kreatif" {{ $umkm->kategori == 'Kreatif' ? 'selected' : '' }}>Kreatif</option>
                                    <option value="Fashion" {{ $umkm->kategori == 'Fashion' ? 'selected' : '' }}>Fashion</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama UMKM *</label>
                                <input type="text" name="nama_umkm" value="{{ old('nama_umkm', $umkm->nama_umkm) }}" required placeholder="Masukkan nama UMKM" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Pemilik *</label>
                                <input type="text" name="nama_pemilik" value="{{ old('nama_pemilik', $umkm->nama_pemilik) }}" required placeholder="Masukkan nama pemilik" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                            </div>

                            <!-- Legalitas -->
                            <div class="col-span-1 sm:col-span-2 mt-2">
                                <h4 class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-3">2. Legalitas & Perizinan</h4>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor NIB</label>
                                <input type="text" name="nomor_nib" value="{{ old('nomor_nib', $umkm->nomor_nib) }}" placeholder="Contoh: 1234567890" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Izin Halal</label>
                                <input type="text" name="izin_halal" value="{{ old('izin_halal', $umkm->izin_halal) }}" placeholder="Nomor Izin Halal / ID Halal" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                            </div>

                            <div class="col-span-1 sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">BPOM</label>
                                <input type="text" name="bpom" value="{{ old('bpom', $umkm->bpom) }}" placeholder="Nomor BPOM / P-IRT" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                            </div>

                            <!-- Kontak & Media Sosial -->
                            <div class="col-span-1 sm:col-span-2 mt-2">
                                <h4 class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-3">3. Kontak & Media Sosial</h4>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor WA (WhatsApp)</label>
                                <input type="text" name="whatsapp" value="{{ old('whatsapp', $umkm->whatsapp) }}" placeholder="Contoh: 08123456789" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Instagram</label>
                                <input type="text" name="instagram" value="{{ old('instagram', $umkm->instagram) }}" placeholder="Username / Link IG" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Facebook</label>
                                <input type="text" name="facebook" value="{{ old('facebook', $umkm->facebook) }}" placeholder="Nama Halaman / Link FB" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">TikTok</label>
                                <input type="text" name="tiktok" value="{{ old('tiktok', $umkm->tiktok) }}" placeholder="Username / Link TikTok" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                            </div>

                            <div class="col-span-1 sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Link Penjualan (Marketplace)</label>
                                <input type="url" name="link_marketplace" value="{{ old('link_marketplace', $umkm->link_marketplace) }}" placeholder="https://shopee.co.id/tokokamu atau Tokopedia" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                            </div>

                            <!-- Lokasi Spasial -->
                            <div class="col-span-1 sm:col-span-2 mt-2">
                                <h4 class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-3">4. Titik Lokasi Peta (Spasial)</h4>
                                
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Titik Lokasi (Latitude, Longitude)</label>
                                <input type="text" id="koordinat_lokasi" name="koordinat_lokasi" value="{{ old('koordinat_lokasi', $umkm->koordinat_lokasi) }}" @input="updateMapFromInput($event.target.value)" placeholder="-7.123456, 107.456789" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200 mb-2">
                                
                                <div class="text-xs text-gray-500 mb-2">Klik di peta bawah untuk mengubah titik lokasi usaha secara otomatis:</div>
                                <div id="map" class="w-full h-56 rounded-2xl border border-gray-200 shadow-inner"></div>
                            </div>

                            <!-- Foto Produk -->
                            <div class="col-span-1 sm:col-span-2 mt-2">
                                <h4 class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-3">5. Foto Produk (Maksimal 3 Foto)</h4>
                                
                                <!-- Hidden inputs for existing photos that are kept -->
                                <template x-for="(fotoPath, index) in existingPhotos" :key="index">
                                    <input type="hidden" name="existing_photos[]" :value="fotoPath">
                                </template>

                                <!-- Existing Saved Photos Grid -->
                                <template x-if="existingPhotos.length > 0">
                                    <div class="mb-4">
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Foto Tersimpan Saat Ini (<span x-text="existingPhotos.length"></span> foto):</p>
                                        <div class="grid grid-cols-3 gap-3">
                                            <template x-for="(fotoPath, index) in existingPhotos" :key="index">
                                                <div class="relative group rounded-xl overflow-hidden border border-gray-200 shadow-sm bg-gray-50 aspect-square">
                                                    <img :src="'/storage/' + fotoPath" class="w-full h-full object-cover" alt="Foto Tersimpan">
                                                    <span class="absolute top-1.5 left-1.5 bg-pj-green-600/90 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-0.5 rounded-full" x-text="'Foto ' + (index + 1)"></span>
                                                    <button type="button" @click="removeExistingPhoto(index)" class="absolute top-1.5 right-1.5 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow-md transition-all transform hover:scale-110" title="Hapus foto ini dari database">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <!-- Live Preview for newly selected files -->
                                <template x-if="photoPreviews.length > 0">
                                    <div class="mb-4">
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Pratinjau Foto Baru yang Dipilih:</p>
                                        <div class="grid grid-cols-3 gap-3">
                                            <template x-for="(photo, index) in photoPreviews" :key="index">
                                                <div class="relative group rounded-xl overflow-hidden border-2 border-pj-green-500 shadow-md bg-gray-50 aspect-square">
                                                    <img :src="photo.url" class="w-full h-full object-cover" alt="Pratinjau Foto Baru">
                                                    <span class="absolute top-1.5 left-1.5 bg-black/60 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-0.5 rounded-full" x-text="'Foto Baru ' + (index + 1)"></span>
                                                    <button type="button" @click="removePreviewPhoto(index)" class="absolute top-1.5 right-1.5 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow-md transition-all transform hover:scale-110" title="Hapus foto baru ini">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                    <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-1.5 text-[10px] text-white truncate px-2 font-medium" x-text="photo.name"></div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <input type="file" 
                                       id="foto_produk_input_edit" 
                                       name="foto_produk[]" 
                                       multiple 
                                       accept="image/*" 
                                       @change="handlePhotoChange($event)"
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-pj-green-50 file:text-pj-green-700 hover:file:bg-pj-green-100 cursor-pointer">
                                
                                <p x-show="photoError" x-text="photoError" class="text-xs text-red-500 font-semibold mt-1.5"></p>
                                <p class="text-xs text-gray-500 mt-1">Dapat menambah foto produk baru hingga total 3 foto tersimpan (maksimal 2MB per foto).</p>
                            </div>

                        </div>

                        <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-gray-100">
                            <a href="{{ route('umkm.index') }}" class="inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-base font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none sm:w-auto sm:text-sm transition-colors">Batal</a>
                            <button type="submit" class="inline-flex justify-center rounded-xl border border-transparent shadow-md px-6 py-2.5 bg-pj-green-600 text-base font-semibold text-white hover:bg-pj-green-700 focus:outline-none sm:w-auto sm:text-sm transition-colors">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('umkmEditModal', () => ({
                map: null,
                marker: null,
                existingPhotos: @json($umkm->foto_produk ?? []),
                photoPreviews: [],
                photoError: '',
                removeExistingPhoto(index) {
                    this.existingPhotos.splice(index, 1);
                    if (this.existingPhotos.length + this.photoPreviews.length <= 3) {
                        this.photoError = '';
                    }
                },
                handlePhotoChange(e) {
                    const input = e.target;
                    let files = Array.from(input.files);
                    this.photoError = '';
                    
                    const availableQuota = 3 - this.existingPhotos.length;
                    
                    if (availableQuota <= 0) {
                        this.photoError = 'Sudah ada 3 foto tersimpan. Hapus salah satu foto tersimpan terlebih dahulu jika ingin menambah foto baru.';
                        input.value = '';
                        this.photoPreviews = [];
                        return;
                    }
                    
                    if (files.length > availableQuota) {
                        this.photoError = `Hanya ${availableQuota} foto baru yang dapat ditambahkan agar total tidak melebihi 3 foto.`;
                        files = files.slice(0, availableQuota);
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
                    const input = document.getElementById('foto_produk_input_edit');
                    if (input && input.files) {
                        const dt = new DataTransfer();
                        Array.from(input.files).forEach((file, i) => {
                            if (i !== index) dt.items.add(file);
                        });
                        input.files = dt.files;
                    }
                    if (this.existingPhotos.length + this.photoPreviews.length <= 3) {
                        this.photoError = '';
                    }
                },
                init() {
                    let lat = -7.1233;
                    let lng = 107.4735;
                    
                    const existingCoords = '{{ $umkm->koordinat_lokasi }}';
                    if (existingCoords && existingCoords.includes(',')) {
                        const parts = existingCoords.split(',');
                        lat = parseFloat(parts[0]);
                        lng = parseFloat(parts[1]);
                    }

                    this.map = L.map('map').setView([lat, lng], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(this.map);
                    
                    if (existingCoords) {
                        this.marker = L.marker([lat, lng]).addTo(this.map);
                    }

                    this.map.on('click', (e) => {
                        const newLat = e.latlng.lat.toFixed(6);
                        const newLng = e.latlng.lng.toFixed(6);
                        
                        document.getElementById('koordinat_lokasi').value = newLat + ', ' + newLng;

                        if (this.marker) {
                            this.map.removeLayer(this.marker);
                        }
                        this.marker = L.marker([newLat, newLng]).addTo(this.map);
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
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>
