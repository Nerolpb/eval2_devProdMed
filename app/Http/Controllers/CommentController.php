<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Contrôleur web pour les commentaires.
 *
 * Workflow implémenté :
 *   POST   /posts/{post}/comments          → store()   Créer un commentaire
 *   GET    /comments/{comment}/edit        → edit()    Formulaire d'édition
 *   PATCH  /comments/{comment}             → update()  Sauvegarder les modifications
 *   DELETE /comments/{comment}             → destroy() Supprimer un commentaire
 *
 * Les routes store/edit/update/destroy sont toutes protégées par le middleware
 * 'auth' (déclaré dans routes/web.php). Les autorisations fines (qui peut
 * modifier/supprimer quoi) sont gérées par CommentPolicy via Gate::authorize().
 *
 * Après chaque action on redirige vers le post concerné avec l'ancre #comments
 * pour que l'utilisateur revienne directement à la section des commentaires.
 */
class CommentController extends Controller
{
    /**
     * Créer et enregistrer un nouveau commentaire sur un post.
     * Route : POST /posts/{post}/comments
     *
     * Le Post est résolu automatiquement par le route model binding de Laravel
     * (Laravel cherche un Post dont l'id correspond au segment {post} de l'URL).
     */
    public function store(Request $request, Post $post)
    {
        // Vérifie que l'utilisateur a le droit de créer un commentaire
        Gate::authorize('create', Comment::class);

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $comment = new Comment();
        $comment->content = $validated['content'];

        // associate() établit la relation Eloquent et positionne user_id / post_id
        $comment->user()->associate($request->user());
        $comment->post()->associate($post);

        $comment->save();

        // Redirige vers la section commentaires du post
        return redirect("/posts/{$post->id}#comments");
    }

    /**
     * Afficher le formulaire d'édition d'un commentaire.
     * Route : GET /comments/{comment}/edit
     */
    public function edit(Comment $comment)
    {
        // Seul l'auteur peut modifier son commentaire
        Gate::authorize('update', $comment);

        return view('comments.edit', ['comment' => $comment]);
    }

    /**
     * Enregistrer les modifications d'un commentaire.
     * Route : PATCH /comments/{comment}
     */
    public function update(Request $request, Comment $comment)
    {
        Gate::authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $comment->content = $validated['content'];
        $comment->save();

        return redirect("/posts/{$comment->post_id}#comments");
    }

    /**
     * Supprimer un commentaire.
     * Route : DELETE /comments/{comment}
     *
     * La policy autorise : l'auteur du commentaire OU le propriétaire du post.
     * On sauvegarde post_id avant la suppression car après $comment->delete()
     * l'objet n'a plus de post_id accessible en base.
     */
    public function destroy(Comment $comment)
    {
        Gate::authorize('delete', $comment);

        $post_id = $comment->post_id;
        $comment->delete();

        return redirect("/posts/{$post_id}#comments");
    }
}
