<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TokenController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $posts = Post::orderBy('created_at', 'desc')->with('user')->with('likes')->limit(3)->get();

    return view('home', ['posts' => $posts]);
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/@{username}', [ProfileController::class, 'show'])->where('username', '[A-Za-z0-9-_]+');

Route::resource('posts', PostController::class)->except(['index', 'show'])->middleware('auth');
Route::resource('posts', PostController::class)->only(['index', 'show']);

Route::singleton('my-profile', MyProfileController::class)->destroyable()->middleware('auth');

Route::match(['put', 'patch'], '/likes/{post}', [LikeController::class, 'update'])->middleware('auth');

/**
 * Routes des commentaires.
 *
 * store est imbriquée sous /posts/{post}/comments car on a besoin du post
 * pour créer le commentaire. edit/update/destroy opèrent directement sur le
 * commentaire (son ID suffit).
 *
 * Toutes ces routes exigent d'être connecté (middleware 'auth').
 * Les autorisations fines (qui peut quoi) sont gérées dans CommentPolicy.
 */
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->middleware('auth');
Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->middleware('auth');
Route::patch('/comments/{comment}', [CommentController::class, 'update'])->middleware('auth');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->middleware('auth');

Route::controller(AuthController::class)->group(function () {
    Route::get('/auth/register', 'showRegister');
    Route::post('/auth/register', 'register');
    Route::get('/auth/login', 'showLogin')->name('login');
    Route::post('/auth/login', 'login');
    Route::post('/auth/logout', 'logout')->middleware('auth');
});

Route::resource('tokens', TokenController::class)->only(['index', 'create', 'store', 'destroy'])->middleware('auth');
