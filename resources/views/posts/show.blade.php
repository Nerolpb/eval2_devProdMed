<x-default-layout>
    <x-slot:title>
        @if ($post->title)
            {{ __('ui.posts.show.title', [
                'post_title' => $post->title,
                'first_name' => $post->user->first_name,
                'last_name' => $post->user->last_name,
            ]) }}
        @else
            {{ __('ui.posts.show.title_without_post_title', [
                'first_name' => $post->user->first_name,
                'last_name' => $post->user->last_name,
            ]) }}
        @endif
    </x-slot>

    <x-slot:description>
        @if ($post->title)
            {{ __('ui.posts.show.description', [
                'post_title' => $post->title,
                'first_name' => $post->user->first_name,
                'last_name' => $post->user->last_name,
            ]) }}
        @else
            {{ __('ui.posts.show.description_without_post_title', [
                'first_name' => $post->user->first_name,
                'last_name' => $post->user->last_name,
            ]) }}
        @endif
    </x-slot>

    <article class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6">
        <header class="mb-6">
            @if ($post->title)
                <h1 class="text-3xl font-bold dark:text-white mb-2">
                    {{ $post->title }}
                </h1>
            @endif

            <p class="text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ url('@' . $post->user->username) }}">
                    {{ __('ui.posts.show.author', [
                        'first_name' => $post->user->first_name,
                        'last_name' => $post->user->last_name,
                    ]) }}
                </a>
                ·
                <span title="{{ $post->created_at->isoFormat('LLLL') }}">
                    {{ $post->created_at->diffForHumans() }}
                </span>
                @can('update', $post)
                    ·
                    <a href="{{ url('/posts/' . $post->id . '/edit') }}">
                        {{ __('ui.posts.edit.title_without_post_title') }}
                    </a>
                @endcan
                ·
                <span class="font-semibold">
                    {{ trans_choice('ui.posts.likes_count', count($post->likes)) }}
                </span>
            </p>
        </header>

        <div class="mb-4">
            <p class="mt-4 dark:text-gray-300">
                {{ $post->content }}
            </p>
        </div>

        <footer class="pt-4 border-t border-gray-200 dark:border-gray-700">
            @auth
                <form method="POST" action="{{ url('/likes/' . $post->id) }}" class="mb-4">
                    @csrf
                    @method('PUT')
                    <div class="flex flex-wrap justify-between gap-2">
                        <button type="submit" name="reaction" value="like"
                            class="w-12 h-12 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 cursor-pointer {{ $reaction === 'like' ? 'ring-2 ring-teal-600 dark:ring-purple-900' : '' }}">
                            👍
                        </button>
                        <button type="submit" name="reaction" value="love"
                            class="w-12 h-12 rounded-full cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 {{ $reaction === 'love' ? 'ring-2 ring-teal-600 dark:ring-purple-900' : '' }}">
                            ❤️
                        </button>
                        <button type="submit" name="reaction" value="haha"
                            class="w-12 h-12 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 cursor-pointer {{ $reaction === 'haha' ? 'ring-2 ring-teal-600 dark:ring-purple-900' : '' }}">
                            😂
                        </button>
                        <button type="submit" name="reaction" value="wow"
                            class="w-12 h-12 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 cursor-pointer {{ $reaction === 'wow' ? 'ring-2 ring-teal-600 dark:ring-purple-900' : '' }}">
                            😮
                        </button>
                        <button type="submit" name="reaction" value="sad"
                            class="w-12 h-12 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 cursor-pointer {{ $reaction === 'sad' ? 'ring-2 ring-teal-600 dark:ring-purple-900' : '' }}">
                            😢
                        </button>
                        <button type="submit" name="reaction" value="angry"
                            class="w-12 h-12 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 cursor-pointer {{ $reaction === 'angry' ? 'ring-2 ring-teal-600 dark:ring-purple-900' : '' }}">
                            😡
                        </button>
                    </div>
                </form>
            @endauth
            <ul class="flex flex-wrap gap-2">
                @forelse ($post->likes as $user)
                    <li class="flex items-center gap-1 text-sm text-gray-600 dark:text-gray-400">
                        <a href="{{ url('@' . $user->username) }}" class="font-semibold hover:underline">
                            {{ '@' . $user->username }}
                        </a>
                        <span>
                            @if ($user->pivot->reaction === 'like')
                                👍
                            @elseif($user->pivot->reaction === 'love')
                                ❤️
                            @elseif($user->pivot->reaction === 'haha')
                                😂
                            @elseif($user->pivot->reaction === 'wow')
                                😮
                            @elseif($user->pivot->reaction === 'sad')
                                😢
                            @elseif($user->pivot->reaction === 'angry')
                                😡
                            @endif
                        </span>
                    </li>
                @empty
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        {{ trans_choice('ui.posts.likes_count', 0) }}
                    </span>
                @endforelse
            </ul>
        </footer>
    </article>

    {{-- ===== SECTION COMMENTAIRES ===== --}}
    {{--
        La section est placée en dehors de l'<article> du post pour bien
        séparer visuellement le contenu du post et ses commentaires.
        L'ancre id="comments" permet de revenir directement ici après
        soumission/modification/suppression d'un commentaire.
    --}}
    <section id="comments" class="mt-6">
        <h2 class="text-xl font-bold dark:text-white mb-4">
            {{ trans_choice('ui.comments.count', $post->comments->count()) }}
        </h2>

        {{-- Liste des commentaires existants --}}
        @forelse ($post->comments as $comment)
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-4 mb-3">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        {{-- En-tête : auteur + date --}}
                        <p class="text-sm font-semibold dark:text-white">
                            <a href="{{ url('@' . $comment->user->username) }}" class="hover:underline">
                                {{ $comment->user->first_name }} {{ $comment->user->last_name }}
                            </a>
                            <span class="font-normal text-gray-500 dark:text-gray-400 text-xs ml-2"
                                  title="{{ $comment->created_at->isoFormat('LLLL') }}">
                                {{ $comment->created_at->diffForHumans() }}
                            </span>
                        </p>
                        {{-- Contenu du commentaire --}}
                        <p class="mt-1 dark:text-gray-300 break-words">{{ $comment->content }}</p>
                    </div>

                    {{-- Actions : modifier (auteur) / supprimer (auteur ou propriétaire du post) --}}
                    <div class="flex gap-3 shrink-0">
                        @can('update', $comment)
                            <a href="{{ url('/comments/' . $comment->id . '/edit') }}"
                               class="text-sm text-teal-600 dark:text-purple-400 hover:underline">
                                {{ __('ui.comments.form.actions.edit') }}
                            </a>
                        @endcan

                        @can('delete', $comment)
                            <form method="POST" action="{{ url('/comments/' . $comment->id) }}"
                                  onsubmit="return confirm('{{ __('ui.comments.form.actions.delete_confirm') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-sm text-red-600 dark:text-red-400 hover:underline cursor-pointer">
                                    {{ __('ui.comments.form.actions.delete') }}
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <p class="text-gray-500 dark:text-gray-400 italic mb-4">
                {{ __('ui.comments.no_comments') }}
            </p>
        @endforelse

        {{-- Formulaire d'ajout d'un commentaire (utilisateurs connectés seulement) --}}
        @auth
            <div class="mt-4 bg-white dark:bg-slate-800 rounded-lg shadow-sm p-4">
                <form method="POST" action="{{ url('/posts/' . $post->id . '/comments') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="content"
                               class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('ui.comments.form.fields.content.label') }}
                        </label>
                        <textarea id="content" name="content" rows="3"
                            placeholder="{{ __('ui.comments.form.fields.content.placeholder') }}"
                            class="w-full px-3 py-2 border rounded-md bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:border-transparent @error('content') border-red-500 focus:ring-red-500 @else border-gray-300 dark:border-gray-600 focus:ring-teal-500 dark:focus:ring-purple-500 @enderror">{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="px-4 py-2 bg-teal-600 dark:bg-purple-900 text-white rounded-md hover:bg-teal-700 dark:hover:bg-purple-800 cursor-pointer">
                        {{ __('ui.comments.form.actions.submit') }}
                    </button>
                </form>
            </div>
        @endauth
    </section>
</x-default-layout>

