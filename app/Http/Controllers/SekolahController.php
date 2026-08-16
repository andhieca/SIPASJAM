<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SekolahController extends Controller
{
    public function index(Request $request)
    {
        $query = Sekolah::with('desa');

        if ($request->filled('search')) {
            $query->where('nama_sekolah', 'like', '%' . $request->search . '%')
                  ->orWhere('npsn', 'like', '%' . $request->search . '%')
                  ->orWhere('alamat_sekolah', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 10);
        $sekolahs = $perPage === 'all' ? $query->get() : $query->paginate((int) $perPage)->appends($request->query());
        $desas = Desa::all();

        return view('admin.sekolah.index', compact('sekolahs', 'desas', 'perPage'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'nama_sekolah' => 'required|string|max:255',
            'npsn' => 'nullable|string|max:50',
            'alamat_sekolah' => 'nullable|string',
            'koordinat_lokasi' => 'nullable|string',
            'foto_sekolah' => 'nullable|array|max:3',
            'foto_sekolah.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $fotoPaths = [];
        if ($request->hasFile('foto_sekolah')) {
            foreach (array_slice($request->file('foto_sekolah'), 0, 3) as $file) {
                $path = $file->store('sekolah_photos', 'public');
                $fotoPaths[] = $path;
            }
        }

        Sekolah::create([
            'desa_id' => $validated['desa_id'],
            'nama_sekolah' => $validated['nama_sekolah'],
            'npsn' => $validated['npsn'] ?? null,
            'alamat_sekolah' => $validated['alamat_sekolah'] ?? null,
            'koordinat_lokasi' => $validated['koordinat_lokasi'] ?? null,
            'foto_sekolah' => !empty($fotoPaths) ? $fotoPaths : null,
        ]);

        return redirect()->route('sekolah.index')->with('success', 'Data Sekolah berhasil ditambahkan.');
    }

    public function edit(Sekolah $sekolah)
    {
        $desas = Desa::all();
        return view('admin.sekolah.edit', compact('sekolah', 'desas'));
    }

    public function update(Request $request, Sekolah $sekolah)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'nama_sekolah' => 'required|string|max:255',
            'npsn' => 'nullable|string|max:50',
            'alamat_sekolah' => 'nullable|string',
            'koordinat_lokasi' => 'nullable|string',
            'existing_photos' => 'nullable|array',
            'foto_sekolah' => 'nullable|array|max:3',
            'foto_sekolah.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Keep existing photo paths
        $fotoPaths = $request->input('existing_photos', []);
        if (!is_array($fotoPaths)) {
            $fotoPaths = [];
        }

        // Store newly uploaded photos up to remaining quota
        if ($request->hasFile('foto_sekolah')) {
            $remainingQuota = max(0, 3 - count($fotoPaths));
            if ($remainingQuota > 0) {
                foreach (array_slice($request->file('foto_sekolah'), 0, $remainingQuota) as $file) {
                    $path = $file->store('sekolah_photos', 'public');
                    $fotoPaths[] = $path;
                }
            }
        }

        // Guarantee maximum 3 photos total
        $fotoPaths = array_slice($fotoPaths, 0, 3);

        $sekolah->update([
            'desa_id' => $validated['desa_id'],
            'nama_sekolah' => $validated['nama_sekolah'],
            'npsn' => $validated['npsn'] ?? null,
            'alamat_sekolah' => $validated['alamat_sekolah'] ?? null,
            'koordinat_lokasi' => $validated['koordinat_lokasi'] ?? null,
            'foto_sekolah' => !empty($fotoPaths) ? array_values($fotoPaths) : null,
        ]);

        return redirect()->route('sekolah.index')->with('success', 'Data Sekolah berhasil diperbarui.');
    }

    public function destroy(Sekolah $sekolah)
    {
        if ($sekolah->foto_sekolah) {
            foreach ($sekolah->foto_sekolah as $foto) {
                Storage::disk('public')->delete($foto);
            }
        }
        $sekolah->delete();

        return redirect()->route('sekolah.index')->with('success', 'Data Sekolah berhasil dihapus.');
    }
}
