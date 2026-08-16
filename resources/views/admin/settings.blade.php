<x-app-layout>
    <div class="py-8 bg-gray-50/50 min-h-screen" x-data="{ bgType: 'upload', previewUrl: '{{ $currentHeroBg }}', selectedPreset: '' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pengaturan Aplikasi</h2>
                    <p class="mt-1 text-sm text-gray-500 font-medium">Kelola tampilan umum dan latar belakang beranda publik SIGAP Kecamatan Pasirjambu.</p>
                </div>
                <a href="{{ url('/') }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 hover:text-emerald-700 hover:border-emerald-500 font-semibold rounded-xl shadow-sm transition-all text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>Pratinjau Landing Page ↗</span>
                </a>
            </div>

            <!-- Alerts -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-sm font-bold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-rose-500 text-white flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <span class="text-sm font-bold">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left Column: Form Settings -->
                <div class="lg:col-span-7 space-y-6">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl border border-gray-100 shadow-xl overflow-hidden p-6 md:p-8 space-y-8">
                        @csrf
                        @method('PUT')

                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Background Hero Landing Page</h3>
                            <p class="text-xs text-gray-500">Pilih metode penggantian gambar latar belakang beranda utama.</p>
                        </div>

                        <!-- Mode Selector Tabs -->
                        <div class="grid grid-cols-3 gap-2 p-1.5 bg-gray-100/80 rounded-2xl text-xs font-bold">
                            <button type="button" @click="bgType = 'upload'" :class="bgType === 'upload' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-600 hover:text-gray-900'" class="py-2.5 px-3 rounded-xl transition-all flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <span>Upload File</span>
                            </button>
                            <button type="button" @click="bgType = 'preset'" :class="bgType === 'preset' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-600 hover:text-gray-900'" class="py-2.5 px-3 rounded-xl transition-all flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Preset Alam</span>
                            </button>
                            <button type="button" @click="bgType = 'url'" :class="bgType === 'url' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-600 hover:text-gray-900'" class="py-2.5 px-3 rounded-xl transition-all flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                <span>Link URL</span>
                            </button>
                        </div>

                        <input type="hidden" name="bg_type" :value="bgType">

                        <!-- Mode 1: File Upload -->
                        <div x-show="bgType === 'upload'" class="space-y-4">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Upload Gambar Baru (Maks 5MB)</label>
                            <div class="relative border-2 border-dashed border-gray-200 hover:border-emerald-500 rounded-2xl p-8 text-center bg-gray-50/50 hover:bg-emerald-50/20 transition-all group cursor-pointer">
                                <input type="file" name="bg_file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                       @change="const file = $event.target.files[0]; if(file){ previewUrl = URL.createObjectURL(file); }">
                                <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                </div>
                                <p class="text-sm font-bold text-gray-800 mb-1">Klik atau seret file gambar ke sini</p>
                                <p class="text-xs text-gray-400">Format yang didukung: JPG, PNG, WEBP, SVG</p>
                            </div>
                        </div>

                        <!-- Mode 2: Presets -->
                        <div x-show="bgType === 'preset'" class="space-y-4">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Pilih Gambar Pemandangan Alam Pasirjambu</label>
                            <div class="grid grid-cols-2 gap-4">
                                @foreach($presets as $p)
                                    <div @click="selectedPreset = '{{ $p['url'] }}'; previewUrl = '{{ $p['url'] }}'"
                                         :class="selectedPreset === '{{ $p['url'] }}' ? 'ring-4 ring-emerald-500 border-transparent shadow-lg scale-[1.02]' : 'border-gray-200 hover:border-emerald-300'"
                                         class="relative rounded-2xl overflow-hidden border-2 cursor-pointer transition-all duration-200 group bg-gray-100 h-36">
                                        <img src="{{ $p['url'] }}" alt="{{ $p['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent p-3 flex items-end">
                                            <span class="text-xs font-bold text-white leading-tight drop-shadow-md">{{ $p['name'] }}</span>
                                        </div>
                                        <div x-show="selectedPreset === '{{ $p['url'] }}'" class="absolute top-3 right-3 w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-md">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="preset_url" :value="selectedPreset">
                        </div>

                        <!-- Mode 3: Custom URL -->
                        <div x-show="bgType === 'url'" class="space-y-4">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Link URL Gambar Luar</label>
                            <input type="url" name="custom_url" x-model="previewUrl" placeholder="https://domain.com/gambar-pemandangan.jpg"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                            <p class="text-xs text-gray-400">Pastikan link URL gambar dapat diakses secara publik dengan HTTPS.</p>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4 border-t border-gray-100 flex justify-end">
                            <button type="submit" class="px-8 py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 cursor-pointer transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Simpan Perubahan Background</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Column: Live Mockup Preview -->
                <div class="lg:col-span-5">
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-xl overflow-hidden p-6 sticky top-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-extrabold text-gray-900 uppercase tracking-wider">Pratinjau Live Landing Page</h3>
                            <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200/50">LIVE PREVIEW</span>
                        </div>

                        <!-- Mini Landing Page Mockup -->
                        <div class="relative w-full h-[380px] rounded-2xl overflow-hidden shadow-2xl border border-gray-200 flex flex-col justify-between p-6">
                            <!-- Background Image -->
                            <img :src="previewUrl" alt="Background Preview" class="absolute inset-0 w-full h-full object-cover transition-all duration-500">
                            <!-- Dark Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-r from-emerald-900/90 to-emerald-800/60 mix-blend-multiply"></div>
                            <div class="absolute inset-0 bg-black/20"></div>

                            <!-- Mini Header -->
                            <div class="relative z-10 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <img src="{{ asset('images/logo-kab-bandung.png') }}" class="w-6 h-6 object-contain drop-shadow">
                                    <span class="font-bold text-white text-xs tracking-tight">SIGAP</span>
                                </div>
                                <span class="text-[9px] text-white/80 bg-white/20 px-2 py-0.5 rounded-full backdrop-blur-sm">PASIRJAMBU</span>
                            </div>

                            <!-- Mini Hero Text -->
                            <div class="relative z-10 text-center text-white my-auto px-2">
                                <span class="inline-block px-2.5 py-0.5 rounded-full bg-amber-500/20 text-amber-300 text-[10px] font-bold tracking-wider mb-2 border border-amber-500/30">
                                    KECAMATAN PASIRJAMBU
                                </span>
                                <h4 class="text-xl font-extrabold text-white tracking-tight leading-tight mb-1.5">
                                    SIGAP <br> <span class="text-amber-300 text-xs font-medium">Sistem Informasi Gambaran Pasirjambu</span>
                                </h4>
                                <p class="text-[10px] text-gray-200 line-clamp-2 max-w-xs mx-auto">
                                    Sistem Informasi Data Terpadu yang ada di Wilayah Kecamatan Pasirjambu, Kabupaten Bandung.
                                </p>
                            </div>

                            <!-- Mini Buttons -->
                            <div class="relative z-10 flex justify-center gap-2">
                                <div class="px-3 py-1.5 rounded-full bg-amber-500 text-white text-[10px] font-bold shadow-md">Jelajahi Data</div>
                                <div class="px-3 py-1.5 rounded-full bg-white/20 text-white text-[10px] font-bold backdrop-blur-md">Lihat Peta</div>
                            </div>
                        </div>

                        <p class="text-[11px] text-gray-400 mt-4 text-center">
                            *Tampilan di atas adalah gambaran langsung bagaimana latar belakang hero beranda publik akan muncul bagi pengunjung.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
