<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle Eloquent pour les commentaires.
 *
 * Convention Laravel respectée :
 *   - Classe  : Comment  (singulier, PascalCase)
 *   - Table   : comments (pluriel, snake_case — déduite automatiquement)
 *   - Clé primaire : id (auto-increment)
 *   - Timestamps : created_at / updated_at gérés automatiquement
 */
class Comment extends Model
{
    /**
     * L'auteur du commentaire.
     * Un commentaire appartient à un seul utilisateur.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Le post sur lequel porte le commentaire.
     * Un commentaire appartient à un seul post.
     * Cette relation est utilisée par CommentPolicy::delete() pour vérifier
     * si l'utilisateur courant est le propriétaire du post.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
