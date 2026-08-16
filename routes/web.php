<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $sppgs = \App\Models\Sppg::with('desa')->get();
    $desas = \App\Models\Desa::get();
    $umkms = \App\Models\Umkm::with('desa')->get();
    $kopdes = \App\Models\Kopdes::with('desa')->get();
    $sekolahs = \App\Models\Sekolah::with('desa')->get();
    $heroBg = \App\Models\Setting::get('hero_background', 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
    return view('welcome', compact('sppgs', 'desas', 'umkms', 'kopdes', 'sekolahs', 'heroBg'));
});

Route::get('/sebaran-sppg', [App\Http\Controllers\PublicController::class, 'sppgMap'])->name('public.sppg');
Route::get('/sebaran-desa', [App\Http\Controllers\PublicController::class, 'desaMap'])->name('public.desa');
Route::get('/sebaran-umkm', [App\Http\Controllers\PublicController::class, 'umkmMap'])->name('public.umkm');
Route::get('/sebaran-kopdes', [App\Http\Controllers\PublicController::class, 'kopdesMap'])->name('public.kopdes');
Route::get('/sebaran-sekolah', [App\Http\Controllers\PublicController::class, 'sekolahMap'])->name('public.sekolah');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $totalDesa = \App\Models\Desa::count();
        $totalKopdes = \App\Models\Kopdes::count();
        $totalUmkm = \App\Models\Umkm::count();
        $totalSppg = \App\Models\Sppg::count();
        $totalSekolah = \App\Models\Sekolah::count();
        return view('dashboard', compact('totalDesa', 'totalKopdes', 'totalUmkm', 'totalSppg', 'totalSekolah'));
    })->name('dashboard');

    Route::resource('admin/desa', App\Http\Controllers\AdminController::class);
    Route::resource('admin/umkm', App\Http\Controllers\UmkmController::class);
    Route::get('admin/umkm/{umkm}/produk', [App\Http\Controllers\ProdukUmkmController::class, 'index'])->name('produk.index');
    Route::post('admin/umkm/{umkm}/produk', [App\Http\Controllers\ProdukUmkmController::class, 'store'])->name('produk.store');
    Route::put('admin/umkm/{umkm}/produk/{produk}', [App\Http\Controllers\ProdukUmkmController::class, 'update'])->name('produk.update');
    Route::delete('admin/umkm/{umkm}/produk/{produk}', [App\Http\Controllers\ProdukUmkmController::class, 'destroy'])->name('produk.destroy');
    Route::resource('admin/kopdes', App\Http\Controllers\KopdesController::class);
    Route::resource('admin/sppg', App\Http\Controllers\SppgController::class);
    Route::resource('admin/sekolah', App\Http\Controllers\SekolahController::class);

    Route::get('admin/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('admin/settings', [App\Http\Controllers\SettingController::class, 'update'])->name('admin.settings.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
