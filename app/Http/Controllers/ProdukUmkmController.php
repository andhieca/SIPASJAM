<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use App\Models\ProdukUmkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukUmkmController extends Controller
{
    public function index(Umkm $umkm)
    {
        $umkm->load('desa', 'produks');
        return view('admin.umkm.produk', compact('umkm'));
    }

    public function store(Request $request, Umkm $umkm)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'harga' => 'required|numeric|min:0',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_produk')) {
            $fotoPath = $request->file('foto_produk')->store('produk_umkm', 'public');
        }

        $umkm->produks()->create([
            'nama_produk' => $validated['nama_produk'],
            'foto_produk' => $fotoPath,
            'harga' => $validated['harga'],
        ]);

        return redirect()->route('produk.index', $umkm)->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Umkm $umkm, ProdukUmkm $produk)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'harga' => 'required|numeric|min:0',
        ]);

        $data = [
            'nama_produk' => $validated['nama_produk'],
            'harga' => $validated['harga'],
        ];

        if ($request->hasFile('foto_produk')) {
            // Delete old photo
            if ($produk->foto_produk) {
                Storage::disk('public')->delete($produk->foto_produk);
            }
            $data['foto_produk'] = $request->file('foto_produk')->store('produk_umkm', 'public');
        }

        $produk->update($data);

        return redirect()->route('produk.index', $umkm)->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Umkm $umkm, ProdukUmkm $produk)
    {
        if ($produk->foto_produk) {
            Storage::disk('public')->delete($produk->foto_produk);
        }
        $produk->delete();

        return redirect()->route('produk.index', $umkm)->with('success', 'Produk berhasil dihapus.');
    }
}
