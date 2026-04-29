<?php

use App\Http\Controllers\Api\v1\ApiCommentController;
use App\Http\Controllers\Api\v1\ApiPostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('v1/posts', ApiPostController::class)
    ->middlewareFor(['index', 'show'], ['auth:sanctum', 'abilities:posts:read'])
    ->middlewareFor(['store'], ['auth:sanctum', 'abilities:posts:create'])
    ->middlewareFor(['update'], ['auth:sanctum', 'abilities:posts:update'])
    ->middlewareFor(['destroy'], ['auth:sanctum', 'abilities:posts:delete']);

/**
 * API REST pour les commentaires (ressource imbriquée sous posts).
 *
 * URLs générées :
 *   GET    /api/v1/posts/{post}/comments              index   (lister)
 *   POST   /api/v1/posts/{post}/comments              store   (créer)
 *   PATCH  /api/v1/posts/{post}/comments/{comment}    update  (modifier)
 *   DELETE /api/v1/posts/{post}/comments/{comment}    destroy (supprimer)
 *
 * Scopes Sanctum nécessaires : comments:read, comments:create,
 * comments:update, comments:delete.
 * (Configurable depuis la page "Jetons d'accès" de l'application.)
 */
Route::apiResource('v1/posts/{post}/comments', ApiCommentController::class)
    ->only(['index', 'store', 'update', 'destroy'])
    ->middlewareFor(['index'], ['auth:sanctum', 'abilities:comments:read'])
    ->middlewareFor(['store'], ['auth:sanctum', 'abilities:comments:create'])
    ->middlewareFor(['update'], ['auth:sanctum', 'abilities:comments:update'])
    ->middlewareFor(['destroy'], ['auth:sanctum', 'abilities:comments:delete']);
