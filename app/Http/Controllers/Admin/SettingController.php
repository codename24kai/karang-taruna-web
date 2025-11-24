<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\HeroSlide;
use Illuminate\Support\Facades\File;


class SettingController extends Controller
{
    public function index()
    {
        // Ambil settings biasa
        $settings = Setting::pluck('value', 'key')->toArray();

        // Ambil data slide hero
        $hero_slides = HeroSlide::latest()->get();

        return view('admin.pengaturan.index', compact('settings', 'hero_slides'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');
        foreach($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        return redirect()->back()->with('success', 'Informasi website diperbarui!');
    }

    // === FITUR BARU: UPLOAD SLIDE ===
    // === FITUR BARU: UPLOAD MULTIPLE SLIDE ===
    public function uploadHero(Request $request)
    {
        $request->validate([
            'images' => 'required|array|min:1', // Harus array dan minimal 1
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048', // Validasi tiap file
            'caption' => 'nullable|string|max:100'
        ]);

        if ($request->hasFile('images')) {
            foreach($request->file('images') as $file) {
                // Generate nama unik: time + uniqid + extension
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Simpan ke folder
                $file->move(public_path('uploads/hero'), $filename);

                // Simpan ke Database (Satu file = Satu Row HeroSlide)
                HeroSlide::create([
                    'image' => 'uploads/hero/' . $filename,
                    'caption' => $request->caption // Caption sama untuk semua foto yang diupload bareng (opsional)
                ]);
            }
        }

        return redirect()->back()->with('success', 'Slide baru berhasil ditambahkan!');
    }

    // === FITUR BARU: HAPUS SLIDE ===
    public function deleteHero($id)
    {
        $slide = HeroSlide::findOrFail($id);

        // Hapus file fisik
        if(File::exists(public_path($slide->image))) {
            File::delete(public_path($slide->image));
        }

        $slide->delete();

        return redirect()->back()->with('success', 'Slide berhasil dihapus.');
    }

    // === FITUR BARU: UPDATE HERO (Caption) ===
    public function updateHero(Request $request, $id)
    {
        $slide = HeroSlide::findOrFail($id);

        $request->validate([
            'caption' => 'nullable|string|max:255'
        ]);

        $slide->update([
            'caption' => $request->caption
        ]);

        return redirect()->back()->with('success', 'Caption slide berhasil diperbarui!');
    }
    // === HAPUS SEMUA SLIDE ===
    public function deleteAllHero()
    {
        $slides = HeroSlide::all();

        foreach($slides as $slide) {
            // Perhatikan: Kita pakai nama lengkap '\Illuminate\Support\Facades\File'
            // Ini biar Laravel gak bingung nyari 'File' kemana-mana.
            if(\Illuminate\Support\Facades\File::exists(public_path($slide->image))) {
                \Illuminate\Support\Facades\File::delete(public_path($slide->image));
            }
        }

        HeroSlide::truncate();

        return redirect()->back()->with('success', 'Semua slide berhasil dihapus bersih!');
    }
}

