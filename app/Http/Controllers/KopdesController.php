<?php

namespace App\Http\Controllers;

use App\Models\Kopdes;
use App\Models\Desa;
use Illuminate\Http\Request;

class KopdesController extends Controller
{
    public function index(Request $request)
    {
        $query = Kopdes::with('desa');
        
        if ($request->filled('search')) {
            $query->where('nama_koperasi', 'like', '%' . $request->search . '%')
                  ->orWhere('nomor_badan_hukum', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 10);
        $kopdes = $perPage === 'all' ? $query->get() : $query->paginate((int) $perPage)->appends($request->query());
        $desas = Desa::all();
        
        return view('admin.kopdes.index', compact('kopdes', 'desas', 'perPage'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'nama_koperasi' => 'required|string',
            'nomor_badan_hukum' => 'nullable|string',
            'jumlah_anggota' => 'nullable|integer',
            'aset' => 'nullable|numeric',
            'status_aktif' => 'boolean',
            'additional_keys' => 'nullable|array',
            'additional_values' => 'nullable|array',
        ]);

        $additionalData = [];
        if (!empty($validated['additional_keys'])) {
            foreach ($validated['additional_keys'] as $index => $key) {
                if (!empty($key)) {
                    $additionalData[$key] = $validated['additional_values'][$index] ?? null;
                }
            }
        }

        Kopdes::create([
            'desa_id' => $validated['desa_id'],
            'nama_koperasi' => $validated['nama_koperasi'],
            'nomor_badan_hukum' => $validated['nomor_badan_hukum'] ?? null,
            'jumlah_anggota' => $validated['jumlah_anggota'] ?? 0,
            'aset' => $validated['aset'] ?? 0,
            'status_aktif' => $validated['status_aktif'] ?? true,
            'additional_data' => $additionalData,
        ]);

        return redirect()->route('kopdes.index')->with('success', 'Data Koperasi berhasil ditambahkan.');
    }
}
