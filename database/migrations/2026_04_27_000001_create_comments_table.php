<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration : création de la table comments.
 *
 * Un commentaire appartient à un utilisateur (user_id) et à un post (post_id).
 * Les deux clés étrangères utilisent cascadeOnDelete() : si l'utilisateur ou
 * le post est supprimé, ses commentaires le sont aussi automatiquement.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            // Auteur du commentaire — suppression en cascade si l'utilisateur est supprimé
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Post commenté — suppression en cascade si le post est supprimé
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();

            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
