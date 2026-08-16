<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Data Koperasi Desa') }}
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
                    <h3 class="text-base font-bold text-black uppercase underline tracking-wider" style="font-family: 'Times New Roman', Times, serif; font-size: 13pt;">LAPORAN DATA KOPERASI DESA (KOPDES)</h3>
                    <p class="text-xs font-medium text-black mt-0.5">Kecamatan Pasirjambu, Kabupaten Bandung</p>
                </div>
            </div>
            <div class="print-meta" style="display: none;">Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</div>

            <!-- Search and Actions Header -->
            <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 no-print">
                <form action="{{ route('kopdes.index') }}" method="GET" class="w-full sm:w-1/2 relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama koperasi atau badan hukum..." class="w-full rounded-full border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200 focus:ring-opacity-50 pl-10 pr-4 py-2">
                    <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </form>
                <div class="flex items-center gap-3">
                    <button onclick="window.print()" class="w-full sm:w-auto bg-white hover:bg-gray-50 text-gray-700 font-semibold py-2 px-5 rounded-full shadow-sm border border-gray-200 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Cetak
                    </button>
                    <button @click="openModal = true" class="w-full sm:w-auto bg-pj-green-600 hover:bg-pj-green-700 text-white font-semibold py-2 px-6 rounded-full shadow-md transition-all transform hover:-translate-y-0.5">
                        + Tambah Data Koperasi
                    </button>
                </div>
            </div>

            <!-- Per Page Selector -->
            <div class="mb-4 flex items-center gap-2 no-print">
                <span class="text-sm text-gray-500">Tampilkan</span>
                <form action="{{ route('kopdes.index') }}" method="GET" class="inline-flex items-center">
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
                                <th class="p-4">Nama Koperasi</th>
                                <th class="p-4">Badan Hukum</th>
                                <th class="p-4">Anggota</th>
                                <th class="p-4">Aset</th>
                                <th class="p-4">Desa</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-center rounded-tr-xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($kopdes as $kop)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="p-4 text-gray-500">{{ $perPage === 'all' ? $loop->iteration : $loop->iteration + $kopdes->firstItem() - 1 }}</td>
                                    <td class="p-4 font-semibold text-gray-800">{{ $kop->nama_koperasi }}</td>
                                    <td class="p-4">{{ $kop->nomor_badan_hukum ?? '-' }}</td>
                                    <td class="p-4">{{ number_format($kop->jumlah_anggota, 0, ',', '.') }}</td>
                                    <td class="p-4">Rp {{ number_format($kop->aset, 0, ',', '.') }}</td>
                                    <td class="p-4">{{ $kop->desa->nama_desa ?? '-' }}</td>
                                    <td class="p-4">
                                        @if($kop->status_aktif)
                                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Aktif</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td class="p-4 flex justify-center gap-2">
                                        <button class="text-blue-500 hover:text-blue-700 bg-blue-50 p-2 rounded-lg transition-colors">Edit</button>
                                        <button class="text-red-500 hover:text-red-700 bg-red-50 p-2 rounded-lg transition-colors">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-8 text-center text-gray-500 font-medium">Data tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if($perPage !== 'all' && method_exists($kopdes, 'links'))
                        <div class="mt-6">
                            {{ $kopdes->links() }}
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
                    <form action="{{ route('kopdes.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-xl leading-6 font-semibold text-gray-900 mb-6" id="modal-title">Tambah Data Koperasi Baru</h3>
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
                                    <label class="block text-sm font-medium text-gray-700">Nama Koperasi *</label>
                                    <input type="text" name="nama_koperasi" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nomor Badan Hukum</label>
                                    <input type="text" name="nomor_badan_hukum" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status Aktif</label>
                                    <select name="status_aktif" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                                        <option value="1">Aktif</option>
                                        <option value="0">Tidak Aktif</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jumlah Anggota</label>
                                    <input type="number" name="jumlah_anggota" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Aset (Rp)</label>
                                    <input type="number" step="0.01" name="aset" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
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
</x-app-layout>
