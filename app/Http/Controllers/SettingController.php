<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function index()
    {
        $currentHeroBg = Setting::get('hero_background', 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');

        $presets = [
            [
                'name' => 'Kawasan Hujan Pagi Pasirjambu',
                'url' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80'
            ],
            [
                'name' => 'Kebun Teh & Perbukitan Hijau',
                'url' => 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80'
            ],
            [
                'name' => 'Panorama Pegunungan & Danau',
                'url' => 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80'
            ],
            [
                'name' => 'Lembah & Hutan Pinus',
                'url' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80'
            ],
        ];

        return view('admin.settings', compact('currentHeroBg', 'presets'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'bg_type' => 'required|in:upload,preset,url',
            'bg_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'preset_url' => 'nullable|string',
            'custom_url' => 'nullable|url',
        ]);

        $bgType = $request->input('bg_type');
        $newBgUrl = null;

        if ($bgType === 'upload' && $request->hasFile('bg_file')) {
            $file = $request->file('bg_file');
            $uploadPath = public_path('uploads/settings');
            
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true, true);
            }

            $filename = 'hero_bg_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            $newBgUrl = asset('uploads/settings/' . $filename);
        } elseif ($bgType === 'preset' && $request->filled('preset_url')) {
            $newBgUrl = $request->input('preset_url');
        } elseif ($bgType === 'url' && $request->filled('custom_url')) {
            $newBgUrl = $request->input('custom_url');
        }

        if ($newBgUrl) {
            Setting::set('hero_background', $newBgUrl);
            return redirect()->back()->with('success', 'Background Landing Page berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Silakan pilih atau upload gambar yang valid.');
    }
}
