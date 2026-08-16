<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Desa::query();
        
        if ($request->filled('search')) {
            $query->where('nama_desa', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 10);
        $desas = $perPage === 'all' ? $query->get() : $query->paginate((int) $perPage)->appends($request->query());
        
        return view('admin.desa.index', compact('desas', 'perPage'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_desa' => 'required|string',
            'luas_wilayah' => 'nullable|numeric',
            'jumlah_penduduk' => 'nullable|integer',
        ]);

        Desa::create([
            'nama_desa' => $validated['nama_desa'],
            'luas_wilayah' => $validated['luas_wilayah'] ?? 0,
            'jumlah_penduduk' => $validated['jumlah_penduduk'] ?? 0,
        ]);

        return redirect()->route('desa.index')->with('success', 'Data Desa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $desa = Desa::findOrFail($id);
        return view('admin.desa.edit', compact('desa'));
    }

    public function update(Request $request, $id)
    {
        $desa = Desa::findOrFail($id);

        $validated = $request->validate([
            'nama_desa' => 'required|string',
            'luas_wilayah' => 'nullable|numeric',
            'jumlah_penduduk' => 'nullable|integer',
        ]);

        $desa->update([
            'nama_desa' => $validated['nama_desa'],
            'luas_wilayah' => $validated['luas_wilayah'] ?? 0,
            'jumlah_penduduk' => $validated['jumlah_penduduk'] ?? 0,
        ]);

        return redirect()->route('desa.index')->with('success', 'Data Desa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $desa = Desa::findOrFail($id);
        $desa->delete();

        return redirect()->route('desa.index')->with('success', 'Data Desa berhasil dihapus.');
    }
}
