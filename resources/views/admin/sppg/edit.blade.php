<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-pj-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Data SPPG: {{ $sppg->nama_sppg }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="sppgModal">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6 bg-white border-b border-gray-100">
                    <form action="{{ route('sppg.update', $sppg->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Pilih Desa *</label>
                                <select name="desa_id" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                                    <option value="">-- Pilih Desa --</option>
                                    @foreach($desas as $desa)
                                        <option value="{{ $desa->id }}" {{ $sppg->desa_id == $desa->id ? 'selected' : '' }}>{{ $desa->nama_desa }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Nama SPPG *</label>
                                <input type="text" name="nama_sppg" value="{{ $sppg->nama_sppg }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Yayasan</label>
                                <input type="text" name="nama_yayasan" value="{{ $sppg->nama_yayasan }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ketua SPPG</label>
                                <input type="text" name="ketua_sppg" value="{{ $sppg->ketua_sppg }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                                <input type="text" name="alamat" value="{{ $sppg->alamat }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Koordinat Lokasi</label>
                                <input type="text" name="koordinat_lokasi" id="koordinat_lokasi" value="{{ $sppg->koordinat_lokasi }}" @input="updateMapFromInput($event.target.value)" placeholder="Cth: -7.123, 107.123" class="mt-1 mb-3 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                                
                                <div id="map" class="h-64 w-full rounded-lg border border-gray-300 shadow-sm z-10 relative"></div>
                                <p class="text-xs text-gray-500 mt-1">Klik pada peta untuk otomatis mengisi koordinat lokasi, atau ketik koordinat di atas untuk menggeser peta.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jumlah Penerima Manfaat</label>
                                <input type="number" name="jumlah_penerima_manfaat" value="{{ $sppg->jumlah_penerima_manfaat }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                                    <option value="Operasional" {{ $sppg->status == 'Operasional' ? 'selected' : '' }}>Operasional</option>
                                    <option value="Belum Operasional" {{ $sppg->status == 'Belum Operasional' ? 'selected' : '' }}>Belum Operasional</option>
                                </select>
                            </div>
                            <div class="col-span-1 sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Foto SPPG (Maksimal 3 Foto)</label>
                                
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
                                       id="foto_sppg_input_edit" 
                                       name="foto_sppg[]" 
                                       multiple 
                                       accept="image/*" 
                                       @change="handlePhotoChange($event)"
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-pj-green-50 file:text-pj-green-700 hover:file:bg-pj-green-100 cursor-pointer">
                                
                                <p x-show="photoError" x-text="photoError" class="text-xs text-red-500 font-semibold mt-1.5"></p>
                                <p class="text-xs text-gray-500 mt-1">Dapat menambah foto baru hingga total 3 foto tersimpan (maksimal 2MB per foto).</p>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-gray-100">
                            <a href="{{ route('sppg.index') }}" class="inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:w-auto sm:text-sm">Batal</a>
                            <button type="submit" class="inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-pj-green-600 text-base font-medium text-white hover:bg-pj-green-700 focus:outline-none sm:w-auto sm:text-sm">Simpan Perubahan</button>
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
            Alpine.data('sppgModal', () => ({
                map: null,
                marker: null,
                existingPhotos: @json($sppg->foto_sppg ?? []),
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
                    const input = document.getElementById('foto_sppg_input_edit');
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
                    
                    const existingCoords = '{{ $sppg->koordinat_lokasi }}';
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
