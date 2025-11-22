<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth; // Tambahin ini biar 'Auth' dikenal

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::latest('published_at')->paginate(10);
        return view('admin.artikel.index', compact('articles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'category' => 'required',
            'content' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/artikel'), $filename);
            $imagePath = 'uploads/artikel/' . $filename;
        }

        Article::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'category' => $request->category,
            // PERBAIKAN 1: Pakai input('content')
            'content' => $request->input('content'),
            'excerpt' => Str::limit(strip_tags($request->input('content')), 150),
            'image' => $imagePath,
            'link' => $request->link,
            // PERBAIKAN 2: Pakai Facade Auth
            'author' => Auth::user()->name,
            'published_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Artikel berhasil diterbitkan!');
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'category' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

        ]);

        if ($request->hasFile('image')) {
            if ($article->image && file_exists(public_path($article->image))) {
                unlink(public_path($article->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/artikel'), $filename);
            $article->image = 'uploads/artikel/' . $filename;
            $article->link = $request->link;
        }

        $article->title = $request->title;
        $article->category = $request->category;

        // PERBAIKAN 3: Pakai input('content') di sini juga
        $article->content = $request->input('content');
        $article->excerpt = Str::limit(strip_tags($request->input('content')), 150);

        $article->save();

        return redirect()->back()->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        if ($article->image && file_exists(public_path($article->image))) {
            unlink(public_path($article->image));
        }

        $article->delete();
        return redirect()->back()->with('success', 'Artikel dihapus.');
    }
}
