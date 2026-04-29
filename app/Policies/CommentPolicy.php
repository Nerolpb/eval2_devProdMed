<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

/**
 * Policy d'autorisation pour les commentaires.
 *
 * Règles métier :
 *   - Créer    : tout utilisateur authentifié peut commenter un post.
 *   - Modifier : seul l'auteur du commentaire peut le modifier.
 *   - Supprimer: l'auteur du commentaire OU le propriétaire du post peut supprimer.
 *
 * Cette policy est enregistrée dans AppServiceProvider via Gate::policy().
 * Laravel la découvre automatiquement grâce à la convention de nommage
 * Comment → CommentPolicy.
 */
class CommentPolicy
{
    /**
     * Tout utilisateur authentifié peut créer un commentaire.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Seul l'auteur du commentaire peut le modifier.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    /**
     * L'auteur du commentaire OU le propriétaire du post peut supprimer.
     *
     * $comment->post est chargé en lazy loading si besoin — acceptable
     * car cette vérification se fait sur un seul commentaire à la fois.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id
            || $user->id === $comment->post->user_id;
    }
}
