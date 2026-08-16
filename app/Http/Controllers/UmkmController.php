<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UmkmController extends Controller
{
    public function index(Request $request)
    {
        $query = Umkm::with('desa');
        
        if ($request->filled('search')) {
            $query->where('nama_umkm', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_pemilik', 'like', '%' . $request->search . '%')
                  ->orWhere('kategori', 'like', '%' . $request->search . '%')
                  ->orWhere('nomor_nib', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 10);
        $umkms = $perPage === 'all' ? $query->latest()->get() : $query->latest()->paginate((int) $perPage)->appends($request->query());
        $desas = Desa::all();
        
        return view('admin.umkm.index', compact('umkms', 'desas', 'perPage'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'nama_umkm' => 'required|string',
            'kategori' => 'required|in:Kuliner,Kreatif,Fashion',
            'nama_pemilik' => 'required|string',
            'koordinat_lokasi' => 'nullable|string',
            'nomor_nib' => 'nullable|string',
            'izin_halal' => 'nullable|string',
            'bpom' => 'nullable|string',
            'instagram' => 'nullable|string',
            'facebook' => 'nullable|string',
            'tiktok' => 'nullable|string',
            'whatsapp' => 'nullable|string',
            'link_marketplace' => 'nullable|string',
            'foto_produk' => 'nullable|array|max:3',
            'foto_produk.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $fotoPaths = [];
        if ($request->hasFile('foto_produk')) {
            foreach (array_slice($request->file('foto_produk'), 0, 3) as $file) {
                $path = $file->store('umkm_photos', 'public');
                $fotoPaths[] = $path;
            }
        }

        Umkm::create([
            'desa_id' => $validated['desa_id'],
            'nama_umkm' => $validated['nama_umkm'],
            'kategori' => $validated['kategori'],
            'nama_pemilik' => $validated['nama_pemilik'],
            'koordinat_lokasi' => $validated['koordinat_lokasi'] ?? null,
            'nomor_nib' => $validated['nomor_nib'] ?? null,
            'izin_halal' => $validated['izin_halal'] ?? null,
            'bpom' => $validated['bpom'] ?? null,
            'instagram' => $validated['instagram'] ?? null,
            'facebook' => $validated['facebook'] ?? null,
            'tiktok' => $validated['tiktok'] ?? null,
            'whatsapp' => $validated['whatsapp'] ?? null,
            'link_marketplace' => $validated['link_marketplace'] ?? null,
            'foto_produk' => !empty($fotoPaths) ? array_values($fotoPaths) : null,
        ]);

        return redirect()->route('umkm.index')->with('success', 'Data UMKM berhasil ditambahkan.');
    }

    public function edit(Umkm $umkm)
    {
        $desas = Desa::all();
        return view('admin.umkm.edit', compact('umkm', 'desas'));
    }

    public function update(Request $request, Umkm $umkm)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'nama_umkm' => 'required|string',
            'kategori' => 'required|in:Kuliner,Kreatif,Fashion',
            'nama_pemilik' => 'required|string',
            'koordinat_lokasi' => 'nullable|string',
            'nomor_nib' => 'nullable|string',
            'izin_halal' => 'nullable|string',
            'bpom' => 'nullable|string',
            'instagram' => 'nullable|string',
            'facebook' => 'nullable|string',
            'tiktok' => 'nullable|string',
            'whatsapp' => 'nullable|string',
            'link_marketplace' => 'nullable|string',
            'existing_photos' => 'nullable|array',
            'foto_produk' => 'nullable|array|max:3',
            'foto_produk.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $fotoPaths = $request->input('existing_photos', []);
        if (!is_array($fotoPaths)) {
            $fotoPaths = [];
        }

        if ($request->hasFile('foto_produk')) {
            $remainingQuota = max(0, 3 - count($fotoPaths));
            if ($remainingQuota > 0) {
                foreach (array_slice($request->file('foto_produk'), 0, $remainingQuota) as $file) {
                    $path = $file->store('umkm_photos', 'public');
                    $fotoPaths[] = $path;
                }
            }
        }

        $fotoPaths = array_slice($fotoPaths, 0, 3);

        $umkm->update([
            'desa_id' => $validated['desa_id'],
            'nama_umkm' => $validated['nama_umkm'],
            'kategori' => $validated['kategori'],
            'nama_pemilik' => $validated['nama_pemilik'],
            'koordinat_lokasi' => $validated['koordinat_lokasi'] ?? null,
            'nomor_nib' => $validated['nomor_nib'] ?? null,
            'izin_halal' => $validated['izin_halal'] ?? null,
            'bpom' => $validated['bpom'] ?? null,
            'instagram' => $validated['instagram'] ?? null,
            'facebook' => $validated['facebook'] ?? null,
            'tiktok' => $validated['tiktok'] ?? null,
            'whatsapp' => $validated['whatsapp'] ?? null,
            'link_marketplace' => $validated['link_marketplace'] ?? null,
            'foto_produk' => !empty($fotoPaths) ? array_values($fotoPaths) : null,
        ]);

        return redirect()->route('umkm.index')->with('success', 'Data UMKM berhasil diperbarui.');
    }

    public function destroy(Umkm $umkm)
    {
        if ($umkm->foto_produk) {
            foreach ($umkm->foto_produk as $foto) {
                Storage::disk('public')->delete($foto);
            }
        }
        $umkm->delete();

        return redirect()->route('umkm.index')->with('success', 'Data UMKM berhasil dihapus.');
    }
}
