<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article; // Jangan lupa panggil Model-nya

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        // Ambil input pencarian & filter kategori dari URL
        $search = $request->input('q');
        $category = $request->input('category');

        // Query data pakai logika "Eloquents" (Bahasa gaul Laravel ke Database)
        $articles = Article::query()
            ->when($search, function($query, $search) {
                return $query->where('title', 'ilike', "%{$search}%") // 'ilike' itu case-insensitive di Postgres
                             ->orWhere('content', 'ilike', "%{$search}%");
            })
            ->when($category, function($query, $category) {
                return $query->where('category', $category);
            })
            ->latest('published_at') // Urutkan dari yang terbaru
            ->paginate(6); // Tampilkan 6 artikel per halaman

        // Kirim data ke View
        return view('informasi', compact('articles'));
    }
}
