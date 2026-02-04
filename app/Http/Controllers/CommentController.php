<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'body' => 'required',
            'post_id' => 'required|exists:posts,id',
        ]);

        Comment::create([
            'body' => $validated['body'],
            'user_id' => Auth::id(),
            'post_id' => $validated['post_id'],
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    public function destroy(Comment $comment)
    {
        // Otorisasi: Hanya penulis komentar atau penulis postingan yang bisa menghapus
        if (Auth::user()->id === $comment->user_id || Auth::user()->id === $comment->post->user_id) {
            $comment->delete();
            return back()->with('success', 'Komentar berhasil dihapus!');
        }

        abort(403, 'Akses Dilarang.');
    }
}