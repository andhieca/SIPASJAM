<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Sppg;
use Illuminate\Http\Request;

class SppgController extends Controller
{
    public function index(Request $request)
    {
        $query = Sppg::with('desa');
        
        if ($request->filled('search')) {
            $query->where('nama_sppg', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_yayasan', 'like', '%' . $request->search . '%')
                  ->orWhere('ketua_sppg', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 10);
        $sppgs = $perPage === 'all' ? $query->get() : $query->paginate((int) $perPage)->appends($request->query());
        $desas = Desa::all();
        
        return view('admin.sppg.index', compact('sppgs', 'desas', 'perPage'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'nama_sppg' => 'required|string',
            'nama_yayasan' => 'nullable|string',
            'ketua_sppg' => 'nullable|string',
            'alamat' => 'nullable|string',
            'koordinat_lokasi' => 'nullable|string',
            'status' => 'required|in:Operasional,Belum Operasional',
            'jumlah_penerima_manfaat' => 'nullable|integer',
            'foto_sppg' => 'nullable|array|max:3',
            'foto_sppg.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $fotoPaths = [];
        if ($request->hasFile('foto_sppg')) {
            foreach (array_slice($request->file('foto_sppg'), 0, 3) as $file) {
                $path = $file->store('sppg_photos', 'public');
                $fotoPaths[] = $path;
            }
        }

        Sppg::create([
            'desa_id' => $validated['desa_id'],
            'nama_sppg' => $validated['nama_sppg'],
            'nama_yayasan' => $validated['nama_yayasan'] ?? null,
            'ketua_sppg' => $validated['ketua_sppg'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'koordinat_lokasi' => $validated['koordinat_lokasi'] ?? null,
            'status' => $validated['status'] ?? 'Operasional',
            'jumlah_penerima_manfaat' => $validated['jumlah_penerima_manfaat'] ?? 0,
            'foto_sppg' => !empty($fotoPaths) ? $fotoPaths : null,
        ]);

        return redirect()->route('sppg.index')->with('success', 'Data SPPG berhasil ditambahkan.');
    }

    public function edit(Sppg $sppg)
    {
        $desas = Desa::all();
        return view('admin.sppg.edit', compact('sppg', 'desas'));
    }

    public function update(Request $request, Sppg $sppg)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'nama_sppg' => 'required|string',
            'nama_yayasan' => 'nullable|string',
            'ketua_sppg' => 'nullable|string',
            'alamat' => 'nullable|string',
            'koordinat_lokasi' => 'nullable|string',
            'status' => 'required|in:Operasional,Belum Operasional',
            'jumlah_penerima_manfaat' => 'nullable|integer',
            'existing_photos' => 'nullable|array',
            'foto_sppg' => 'nullable|array|max:3',
            'foto_sppg.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Kept existing photo paths
        $fotoPaths = $request->input('existing_photos', []);
        if (!is_array($fotoPaths)) {
            $fotoPaths = [];
        }

        // Store newly uploaded photos up to remaining quota
        if ($request->hasFile('foto_sppg')) {
            $remainingQuota = max(0, 3 - count($fotoPaths));
            if ($remainingQuota > 0) {
                foreach (array_slice($request->file('foto_sppg'), 0, $remainingQuota) as $file) {
                    $path = $file->store('sppg_photos', 'public');
                    $fotoPaths[] = $path;
                }
            }
        }

        // Guarantee maximum 3 photos total
        $fotoPaths = array_slice($fotoPaths, 0, 3);

        $sppg->update([
            'desa_id' => $validated['desa_id'],
            'nama_sppg' => $validated['nama_sppg'],
            'nama_yayasan' => $validated['nama_yayasan'] ?? null,
            'ketua_sppg' => $validated['ketua_sppg'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'koordinat_lokasi' => $validated['koordinat_lokasi'] ?? null,
            'status' => $validated['status'] ?? 'Operasional',
            'jumlah_penerima_manfaat' => $validated['jumlah_penerima_manfaat'] ?? 0,
            'foto_sppg' => !empty($fotoPaths) ? array_values($fotoPaths) : null,
        ]);

        return redirect()->route('sppg.index')->with('success', 'Data SPPG berhasil diperbarui.');
    }

    public function destroy(Sppg $sppg)
    {
        if ($sppg->foto_sppg) {
            foreach ($sppg->foto_sppg as $foto) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($foto);
            }
        }
        $sppg->delete();

        return redirect()->route('sppg.index')->with('success', 'Data SPPG berhasil dihapus.');
    }
}
