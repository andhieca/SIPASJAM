<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            Produk UMKM — {{ $umkm->nama_umkm }}
        </h2>
    </x-slot>

    <div class="py-6" x-data="produkManager()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Back Button & UMKM Info -->
            <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <a href="{{ route('umkm.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-pj-green-600 font-medium transition-colors mb-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Kembali ke Data UMKM
                    </a>
                    <div class="flex items-center gap-3">
                        <h3 class="text-xl font-bold text-gray-900">{{ $umkm->nama_umkm }}</h3>
                        @if($umkm->kategori === 'Kuliner')
                            <span class="px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold rounded-full">Kuliner</span>
                        @elseif($umkm->kategori === 'Kreatif')
                            <span class="px-3 py-1 bg-purple-50 text-purple-700 border border-purple-200 text-xs font-bold rounded-full">Kreatif</span>
                        @elseif($umkm->kategori === 'Fashion')
                            <span class="px-3 py-1 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-full">Fashion</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Pemilik: <span class="font-semibold text-gray-700">{{ $umkm->nama_pemilik }}</span> · Desa {{ $umkm->desa->nama_desa ?? '-' }}</p>
                </div>
                <button @click="openAddModal = true" class="px-5 py-2.5 bg-pj-green-600 hover:bg-pj-green-700 text-white rounded-2xl shadow-md hover:shadow-lg transition-all font-semibold text-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Tambah Produk
                </button>
            </div>

            <!-- Alert Flash Message -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 flex items-center gap-3 shadow-sm">
                    <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold shrink-0">✓</div>
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Products Grid -->
            @if($umkm->produks->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($umkm->produks as $produk)
                        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
                            <!-- Product Image -->
                            <div class="relative aspect-square bg-gray-100 overflow-hidden">
                                @if($produk->foto_produk)
                                    <img src="{{ Storage::url($produk->foto_produk) }}" alt="{{ $produk->nama_produk }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif

                                <!-- Action Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-4 gap-2">
                                    <button @click="openEdit({{ $produk->id }}, '{{ addslashes($produk->nama_produk) }}', {{ $produk->harga }}, '{{ $produk->foto_produk ? Storage::url($produk->foto_produk) : '' }}')"
                                            class="px-3 py-1.5 bg-white/90 hover:bg-white text-gray-800 rounded-xl text-xs font-bold shadow-lg transition-all">
                                        ✏️ Edit
                                    </button>
                                    <form action="{{ route('produk.destroy', [$umkm, $produk]) }}" method="POST" onsubmit="return confirm('Hapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-rose-500/90 hover:bg-rose-600 text-white rounded-xl text-xs font-bold shadow-lg transition-all">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="p-4">
                                <h4 class="font-bold text-gray-900 text-sm mb-1 truncate">{{ $produk->nama_produk }}</h4>
                                <p class="text-pj-green-600 font-extrabold text-lg">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-16 text-center">
                    <div class="w-20 h-20 rounded-full bg-gray-100 text-gray-300 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700 mb-1">Belum Ada Produk</h3>
                    <p class="text-sm text-gray-500 mb-5">Tambahkan produk pertama untuk UMKM ini</p>
                    <button @click="openAddModal = true" class="px-5 py-2.5 bg-pj-green-600 hover:bg-pj-green-700 text-white rounded-2xl shadow-md font-semibold text-sm inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Tambah Produk Pertama
                    </button>
                </div>
            @endif

            <!-- Add Product Modal -->
            <div x-show="openAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
                <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" @click="openAddModal = false"></div>
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-90"
                     x-transition:enter-end="opacity-100 scale-100">
                    
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900">Tambah Produk Baru</h3>
                            <button @click="openAddModal = false" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    <form action="{{ route('produk.store', $umkm) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                        @csrf
                        
                        <!-- Nama Produk -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Produk <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_produk" required placeholder="Contoh: Keripik Singkong Pedas"
                                   class="w-full rounded-xl border-gray-200 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200 py-2.5 text-sm">
                        </div>

                        <!-- Harga -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Harga (Rp) <span class="text-rose-500">*</span></label>
                            <input type="number" name="harga" required min="0" step="500" placeholder="15000"
                                   class="w-full rounded-xl border-gray-200 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200 py-2.5 text-sm">
                        </div>

                        <!-- Foto Produk -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Foto Produk</label>
                            <div class="relative">
                                <input type="file" name="foto_produk" accept="image/*" @change="previewAdd($event)"
                                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-pj-green-50 file:text-pj-green-700 hover:file:bg-pj-green-100 cursor-pointer">
                            </div>
                            <template x-if="addPreview">
                                <img :src="addPreview" class="mt-3 w-32 h-32 object-cover rounded-2xl border-2 border-gray-200 shadow-sm">
                            </template>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" @click="openAddModal = false" class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-pj-green-600 hover:bg-pj-green-700 text-white rounded-xl text-sm font-semibold shadow-md transition-all">Simpan Produk</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Edit Product Modal -->
            <div x-show="openEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
                <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" @click="openEditModal = false"></div>
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-90"
                     x-transition:enter-end="opacity-100 scale-100">
                    
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900">Edit Produk</h3>
                            <button @click="openEditModal = false" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    <form :action="`{{ url('admin/umkm/' . $umkm->id . '/produk') }}/${editId}`" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                        @csrf
                        @method('PUT')
                        
                        <!-- Nama Produk -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Produk <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_produk" x-model="editNama" required
                                   class="w-full rounded-xl border-gray-200 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200 py-2.5 text-sm">
                        </div>

                        <!-- Harga -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Harga (Rp) <span class="text-rose-500">*</span></label>
                            <input type="number" name="harga" x-model="editHarga" required min="0" step="500"
                                   class="w-full rounded-xl border-gray-200 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200 py-2.5 text-sm">
                        </div>

                        <!-- Foto Produk -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Foto Produk (Ganti)</label>
                            <input type="file" name="foto_produk" accept="image/*" @change="previewEditFile($event)"
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-pj-green-50 file:text-pj-green-700 hover:file:bg-pj-green-100 cursor-pointer">
                            <template x-if="editPreview">
                                <img :src="editPreview" class="mt-3 w-32 h-32 object-cover rounded-2xl border-2 border-gray-200 shadow-sm">
                            </template>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" @click="openEditModal = false" class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-semibold shadow-md transition-all">Perbarui Produk</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        function produkManager() {
            return {
                openAddModal: false,
                openEditModal: false,
                addPreview: null,
                editId: null,
                editNama: '',
                editHarga: 0,
                editPreview: null,

                previewAdd(e) {
                    const file = e.target.files[0];
                    if (file) {
                        this.addPreview = URL.createObjectURL(file);
                    }
                },

                openEdit(id, nama, harga, fotoUrl) {
                    this.editId = id;
                    this.editNama = nama;
                    this.editHarga = harga;
                    this.editPreview = fotoUrl || null;
                    this.openEditModal = true;
                },

                previewEditFile(e) {
                    const file = e.target.files[0];
                    if (file) {
                        this.editPreview = URL.createObjectURL(file);
                    }
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
