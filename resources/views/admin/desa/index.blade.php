<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Data Desa') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ openModal: false }">
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
                    <h3 class="text-base font-bold text-black uppercase underline tracking-wider" style="font-family: 'Times New Roman', Times, serif; font-size: 13pt;">LAPORAN DATA DESA WILAYAH KECAMATAN PASIRJAMBU</h3>
                    <p class="text-xs font-medium text-black mt-0.5">Kecamatan Pasirjambu, Kabupaten Bandung</p>
                </div>
            </div>
            <div class="print-meta" style="display: none;">Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</div>

            <!-- Search and Actions Header -->
            <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 no-print">
                <form action="{{ route('desa.index') }}" method="GET" class="w-full sm:w-1/2 relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama desa..." class="w-full rounded-full border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200 focus:ring-opacity-50 pl-10 pr-4 py-2">
                    <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </form>
                <div class="flex items-center gap-3">
                    <button onclick="window.print()" class="w-full sm:w-auto bg-white hover:bg-gray-50 text-gray-700 font-semibold py-2 px-5 rounded-full shadow-sm border border-gray-200 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Cetak
                    </button>
                    <button @click="openModal = true" class="w-full sm:w-auto bg-pj-green-600 hover:bg-pj-green-700 text-white font-semibold py-2 px-6 rounded-full shadow-md transition-all transform hover:-translate-y-0.5">
                        + Tambah Data Desa
                    </button>
                </div>
            </div>

            <!-- Per Page Selector -->
            <div class="mb-4 flex items-center gap-2 no-print">
                <span class="text-sm text-gray-500">Tampilkan</span>
                <form action="{{ route('desa.index') }}" method="GET" class="inline-flex items-center">
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
                                <th class="p-4">Nama Desa</th>
                                <th class="p-4">Luas Wilayah</th>
                                <th class="p-4">Penduduk</th>
                                <th class="p-4 text-center rounded-tr-xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($desas as $desa)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="p-4 text-gray-500">{{ $perPage === 'all' ? $loop->iteration : $loop->iteration + $desas->firstItem() - 1 }}</td>
                                    <td class="p-4 font-semibold text-gray-800">{{ $desa->nama_desa }}</td>
                                    <td class="p-4">{{ $desa->luas_wilayah }} km²</td>
                                    <td class="p-4">{{ number_format($desa->jumlah_penduduk, 0, ',', '.') }} jiwa</td>
                                    <td class="p-4 flex justify-center gap-2">
                                        <a href="{{ route('desa.edit', $desa->id) }}" class="text-blue-500 hover:text-blue-700 bg-blue-50 p-2 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form action="{{ route('desa.destroy', $desa->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data desa ini?');">
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
                                    <td colspan="5" class="p-8 text-center text-gray-500 font-medium">Data tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if($perPage !== 'all' && method_exists($desas, 'links'))
                        <div class="mt-6">
                            {{ $desas->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Alpine.js Modal Create -->
        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
            <!-- Backdrop -->
            <div x-show="openModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity"></div>

            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <!-- Modal Panel -->
                <div x-show="openModal" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     @click.away="openModal = false"
                     class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    
                    <form action="{{ route('desa.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-xl leading-6 font-semibold text-gray-900 mb-6" id="modal-title">
                                Tambah Data Desa Baru
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Nama Desa *</label>
                                    <input type="text" name="nama_desa" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Luas Wilayah (km²)</label>
                                    <input type="number" step="0.01" name="luas_wilayah" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jumlah Penduduk</label>
                                    <input type="number" name="jumlah_penduduk" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                                </div>
                            </div>

                        </div>
                        <div class="bg-gray-50 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl border-t border-gray-100">
                            <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-pj-green-600 text-base font-medium text-white hover:bg-pj-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan Data
                            </button>
                            <button type="button" @click="openModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
