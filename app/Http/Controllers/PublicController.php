<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sppg;
use App\Models\Desa;
use App\Models\Umkm;
use App\Models\Kopdes;
use App\Models\Sekolah;

class PublicController extends Controller
{
    public function sppgMap()
    {
        $desas = Desa::orderBy('nama_desa')->get();
        $sppgs = Sppg::with('desa')->get();
        
        $totalSppg = $sppgs->count();
        $totalPorsi = $sppgs->sum('jumlah_penerima_manfaat');
        $totalOperasional = $sppgs->where('status', 'Operasional')->count();

        return view('public.sppg-map', compact('sppgs', 'desas', 'totalSppg', 'totalPorsi', 'totalOperasional'));
    }

    public function desaMap()
    {
        $desas = Desa::orderBy('nama_desa')->get();
        $totalPenduduk = $desas->sum('jumlah_penduduk');
        $totalLuas = $desas->sum('luas_wilayah');
        $totalDesa = $desas->count();

        return view('public.desa-map', compact('desas', 'totalPenduduk', 'totalLuas', 'totalDesa'));
    }

    public function umkmMap()
    {
        $desas = Desa::orderBy('nama_desa')->get();
        $umkms = Umkm::with(['desa', 'produks'])->get();
        
        $totalUmkm = $umkms->count();
        $totalKuliner = $umkms->where('kategori', 'Kuliner')->count();
        $totalKreatif = $umkms->where('kategori', 'Kreatif')->count();
        $totalFashion = $umkms->where('kategori', 'Fashion')->count();

        return view('public.umkm-map', compact('umkms', 'desas', 'totalUmkm', 'totalKuliner', 'totalKreatif', 'totalFashion'));
    }

    public function kopdesMap()
    {
        $desas = Desa::orderBy('nama_desa')->get();
        $kopdes = Kopdes::with('desa')->get();
        
        $totalKopdes = $kopdes->count();
        $totalAktif = $kopdes->where('status', 'Aktif')->count();

        return view('public.kopdes-map', compact('kopdes', 'desas', 'totalKopdes', 'totalAktif'));
    }

    public function sekolahMap()
    {
        $desas = Desa::orderBy('nama_desa')->get();
        $sekolahs = Sekolah::with('desa')->get();
        
        $totalSekolah = $sekolahs->count();
        $totalNpsn = $sekolahs->whereNotNull('npsn')->count();

        return view('public.sekolah-map', compact('sekolahs', 'desas', 'totalSekolah', 'totalNpsn'));
    }
}
