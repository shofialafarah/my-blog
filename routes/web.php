<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController; 
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TagController; // Tambahkan baris ini

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Tambahkan rute untuk PostController di sini
Route::resource('posts', PostController::class);
Route::post('comments', [CommentController::class, 'store'])->name('comments.store');
Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
Route::post('posts/{post}/like', [PostController::class, 'toggleLike'])->name('posts.like');

// Ini adalah rute yang benar untuk TagController
// Rute untuk membuat tag baru (jika diperlukan)
Route::post('tags', [TagController::class, 'store'])->name('tags.store');
// Rute untuk mencari tag
Route::get('tags/search', [TagController::class, 'search'])->name('tags.search');

require __DIR__.'/auth.php';