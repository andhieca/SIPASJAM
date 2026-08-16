<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-pj-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Data Desa: {{ $desa->nama_desa }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6 bg-white border-b border-gray-100">
                    <form action="{{ route('desa.update', $desa->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Nama Desa *</label>
                                <input type="text" name="nama_desa" value="{{ old('nama_desa', $desa->nama_desa) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Luas Wilayah (km²)</label>
                                <input type="number" step="0.01" name="luas_wilayah" value="{{ old('luas_wilayah', $desa->luas_wilayah) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jumlah Penduduk (jiwa)</label>
                                <input type="number" name="jumlah_penduduk" value="{{ old('jumlah_penduduk', $desa->jumlah_penduduk) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-pj-green-500 focus:ring focus:ring-pj-green-200">
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <a href="{{ route('desa.index') }}" class="inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:text-sm">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex justify-center rounded-lg border border-transparent shadow-sm px-6 py-2 bg-pj-green-600 text-base font-medium text-white hover:bg-pj-green-700 focus:outline-none sm:text-sm">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
