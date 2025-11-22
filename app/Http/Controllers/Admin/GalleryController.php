<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::latest()->paginate(9);
        return view('admin.galeri.index', compact('galleries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'caption' => 'required',
            'date' => 'required|date',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'required|array|min:1'
        ]);

        $imagePaths = [];
        if($request->hasFile('images')) {
            foreach($request->file('images') as $file) {
                $name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/galeri'), $name);
                $imagePaths[] = 'uploads/galeri/' . $name;
            }
        }

        Gallery::create([
            'caption' => $request->caption,
            'date' => $request->date,
            'images' => json_encode($imagePaths)
        ]);

        return redirect()->back()->with('success', 'Galeri berhasil ditambahkan!');
    }

    // METHOD BARU UNTUK UPDATE
   public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $request->validate([
            'caption' => 'required',
            'date' => 'required|date',
            'new_images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Ambil foto lama (decode dulu biar jadi array)
        $currentImages = json_decode($gallery->images, true) ?? [];

        // Kalau ada foto baru, masukkan ke array lama
        if($request->hasFile('new_images')) {
            foreach($request->file('new_images') as $file) {
                $name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/galeri'), $name);
                $currentImages[] = 'uploads/galeri/' . $name; // Tambah ke array
            }
        }

        // Simpan balik ke DB
        $gallery->update([
            'caption' => $request->caption,
            'date' => $request->date,
            'images' => json_encode($currentImages) // Encode lagi jadi JSON
        ]);

        return redirect()->back()->with('success', 'Galeri diperbarui!');
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        $images = json_decode($gallery->images);
        if (is_array($images)) {
            foreach($images as $img) {
                if(file_exists(public_path($img))) {
                    unlink(public_path($img));
                }
            }
        }
        $gallery->delete();
        return redirect()->back()->with('success', 'Galeri dihapus.');
    }
}
