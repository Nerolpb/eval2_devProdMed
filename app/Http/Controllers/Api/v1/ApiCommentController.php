<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Contrôleur API REST pour les commentaires (v1).
 *
 * Toutes les routes sont imbriquées sous un post (nested resource) :
 *   GET    /api/v1/posts/{post}/comments              → index()
 *   POST   /api/v1/posts/{post}/comments              → store()
 *   PATCH  /api/v1/posts/{post}/comments/{comment}    → update()
 *   DELETE /api/v1/posts/{post}/comments/{comment}    → destroy()
 *
 * L'authentification et les scopes (comments:read, comments:create, etc.)
 * sont vérifiés par les middlewares définis dans routes/api.php.
 * Les autorisations métier restent dans CommentPolicy.
 *
 * On suit le même pattern que ApiPostController : retourner directement
 * le modèle (Laravel le sérialise en JSON automatiquement) ou response()->noContent()
 * pour les suppressions (HTTP 204).
 */
class ApiCommentController extends Controller
{
    /**
     * Lister tous les commentaires d'un post, du plus ancien au plus récent.
     * GET /api/v1/posts/{post}/comments
     */
    public function index(Post $post)
    {
        $comments = $post->comments()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return $comments;
    }

    /**
     * Créer un nouveau commentaire sur un post.
     * POST /api/v1/posts/{post}/comments
     */
    public function store(Request $request, Post $post)
    {
        Gate::authorize('create', Comment::class);

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $comment = new Comment();
        $comment->content = $validated['content'];
        $comment->user()->associate($request->user());
        $comment->post()->associate($post);
        $comment->save();

        // HTTP 201 Created + corps JSON avec l'auteur chargé
        return response()->json($comment->load('user'), 201);
    }

    /**
     * Modifier un commentaire.
     * PATCH /api/v1/posts/{post}/comments/{comment}
     *
     * Le paramètre $post est reçu par route model binding mais n'est pas
     * utilisé directement ici — Laravel le résout pour cohérence de l'URL imbriquée.
     */
    public function update(Request $request, Post $post, Comment $comment)
    {
        Gate::authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $comment->content = $validated['content'];
        $comment->save();

        return $comment->load('user');
    }

    /**
     * Supprimer un commentaire.
     * DELETE /api/v1/posts/{post}/comments/{comment}
     */
    public function destroy(Post $post, Comment $comment)
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        // HTTP 204 No Content : suppression réussie, pas de corps de réponse
        return response()->noContent();
    }
}
