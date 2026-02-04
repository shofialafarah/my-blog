<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Tag; // Pastikan baris ini ada

class PostController extends Controller
{
    // Tambahkan middleware ini untuk melindungi rute
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    // Tampilkan Semua Postingan
    public function index(Request $request)
    {
        $query = Post::with(['user', 'category', 'tags']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('body', 'like', "%{$search}%");
        }

        $posts = $query->latest()->get();
        return view('posts.index', compact('posts'));
    }

    /**
     * Tampilkan formulir untuk membuat postingan baru
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('posts.create', compact('categories', 'tags'));
    }


    /**
     * Simpan postingan baru ke database.
     */
    // Metode store
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id'
        ]);

        $post = new Post($validated);
        $post->user_id = Auth::id();
        $post->save();

        $post->tags()->attach($request->input('tags'));

        return redirect()->route('posts.index')->with('success', 'Postingan berhasil dibuat!');
    }

    /**
     * Tampilkan satu postingan.
     */
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Tampilkan formulir edit postingan
     */
    public function edit(Post $post)
    {
        if (Auth::user()->id !== $post->user_id) {
            abort(403, 'Akses Dilarang.');
        }

        $categories = Category::all();
        $tags = Tag::all();
        return view('posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Perbarui postingan di database.
     */
    // Metode update
    public function update(Request $request, Post $post)
    {
        if (Auth::user()->id !== $post->user_id) {
            abort(403, 'Akses Dilarang.');
        }
        
        $validated = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id'
        ]);

        $post->update($validated);
        $post->tags()->sync($request->input('tags'));

        return redirect()->route('posts.show', $post)->with('success', 'Postingan berhasil diperbarui!');
    }

    /**
     * Hapus postingan dari storage.
     */
    public function destroy(Post $post)
    {
        if (Auth::user()->id !== $post->user_id) {
            abort(403, 'Akses Dilarang.');
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Postingan berhasil dihapus!');
    }

    public function toggleLike(Post $post)
    {
        if (Auth::user()->likedPosts()->where('post_id', $post->id)->exists()) {
            Auth::user()->likedPosts()->detach($post);
        } else {
            Auth::user()->likedPosts()->attach($post);
        }

        return back();
    }
}