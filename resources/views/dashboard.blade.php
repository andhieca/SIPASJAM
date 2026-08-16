<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            Ringkasan Dasbor
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto">
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-pj-green-700 to-pj-green-500 rounded-3xl p-8 mb-8 text-white shadow-lg relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-3xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h3>
                    <p class="text-pj-green-50 max-w-xl text-lg">Kelola data profil desa, koperasi desa, potensi UMKM, dan sebaran SPPG Kecamatan Pasirjambu dengan mudah melalui panel ini.</p>
                </div>
                <svg class="absolute right-0 bottom-0 opacity-10 w-64 h-64 transform translate-x-1/4 translate-y-1/4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 12h3v8h14v-8h3L12 2zm0 2.83l7 7V20H5v-8.17l7-7z"/></svg>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-6 mb-8">
                <!-- Stat 1: Total Desa -->
                <a href="{{ route('desa.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-all transform hover:-translate-y-1">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Desa</p>
                        <h4 class="text-4xl font-bold text-gray-900">{{ $totalDesa }}</h4>
                    </div>
                    <div class="w-14 h-14 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                </a>

                <!-- Stat 2: Total Kopdes -->
                <a href="{{ route('kopdes.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-all transform hover:-translate-y-1">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Koperasi Desa</p>
                        <h4 class="text-4xl font-bold text-gray-900">{{ $totalKopdes }}</h4>
                    </div>
                    <div class="w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </a>

                <!-- Stat 3: Total UMKM -->
                <a href="{{ route('umkm.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-all transform hover:-translate-y-1">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total UMKM</p>
                        <h4 class="text-4xl font-bold text-gray-900">{{ $totalUmkm }}</h4>
                    </div>
                    <div class="w-14 h-14 rounded-full bg-pj-gold-50 flex items-center justify-center text-pj-gold-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                </a>

                <!-- Stat 4: Total SPPG -->
                <a href="{{ route('sppg.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-all transform hover:-translate-y-1">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total SPPG</p>
                        <h4 class="text-4xl font-bold text-gray-900">{{ $totalSppg }}</h4>
                    </div>
                    <div class="w-14 h-14 rounded-full bg-purple-50 flex items-center justify-center text-purple-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </a>

                <!-- Stat 5: Total Sekolah -->
                <a href="{{ route('sekolah.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-all transform hover:-translate-y-1">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Sekolah</p>
                        <h4 class="text-4xl font-bold text-gray-900">{{ $totalSekolah }}</h4>
                    </div>
                    <div class="w-14 h-14 rounded-full bg-cyan-50 flex items-center justify-center text-cyan-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                    </div>
                </a>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-gray-100">
                <div class="p-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h3>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('desa.index') }}" class="inline-flex items-center px-6 py-3 bg-emerald-50 text-emerald-700 font-semibold rounded-xl hover:bg-emerald-100 transition-colors">
                            + Kelola Data Desa
                        </a>
                        <a href="{{ route('kopdes.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-50 text-blue-700 font-semibold rounded-xl hover:bg-blue-100 transition-colors">
                            + Kelola Data Kopdes
                        </a>
                        <a href="{{ route('umkm.index') }}" class="inline-flex items-center px-6 py-3 bg-amber-50 text-amber-700 font-semibold rounded-xl hover:bg-amber-100 transition-colors">
                            + Kelola Data UMKM
                        </a>
                        <a href="{{ route('sppg.index') }}" class="inline-flex items-center px-6 py-3 bg-purple-50 text-purple-700 font-semibold rounded-xl hover:bg-purple-100 transition-colors">
                            + Kelola Data SPPG
                        </a>
                        <a href="{{ route('sekolah.index') }}" class="inline-flex items-center px-6 py-3 bg-cyan-50 text-cyan-700 font-semibold rounded-xl hover:bg-cyan-100 transition-colors">
                            + Kelola Data Sekolah
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
