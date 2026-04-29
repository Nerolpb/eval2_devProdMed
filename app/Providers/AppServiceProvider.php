<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Post;
use App\Policies\CommentPolicy;
use App\Policies\PostPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * On enregistre explicitement les policies ici plutôt que de compter sur
     * la découverte automatique, pour garder une visibilité claire des
     * autorisations dans le projet.
     */
    public function boot(): void
    {
        // PostPolicy : gérer les autorisations sur les posts
        Gate::policy(Post::class, PostPolicy::class);

        // CommentPolicy : gérer les autorisations sur les commentaires
        Gate::policy(Comment::class, CommentPolicy::class);
    }
}

