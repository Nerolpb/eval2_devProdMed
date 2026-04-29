<x-default-layout>
    <x-slot:title>
        {{ __('ui.comments.edit.title') }}
    </x-slot>

    <x-slot:description>
        {{ __('ui.comments.edit.description') }}
    </x-slot>

    <article class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6">
        <header class="mb-6">
            <h1 class="text-3xl font-bold dark:text-white mb-2">
                {{ __('ui.comments.edit.title') }}
            </h1>
            <p class="mt-4 dark:text-gray-300">
                {{ __('ui.comments.edit.description') }}
            </p>
        </header>

        {{-- Formulaire de modification du commentaire --}}
        <form method="POST" action="{{ url('/comments/' . $comment->id) }}">
            @csrf
            @method('PATCH')

            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('ui.comments.form.fields.content.label') }}
                </label>
                <textarea id="content" name="content" rows="4"
                    placeholder="{{ __('ui.comments.form.fields.content.placeholder') }}"
                    class="w-full px-3 py-2 border rounded-md bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:border-transparent @error('content') border-red-500 focus:ring-red-500 @else border-gray-300 dark:border-gray-600 focus:ring-teal-500 dark:focus:ring-purple-500 @enderror">{{ old('content', $comment->content) }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <footer class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    {{-- Retour au post (avec ancre #comments pour revenir à la section) --}}
                    <a href="{{ url('/posts/' . $comment->post_id . '#comments') }}"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                        {{ __('ui.comments.form.actions.cancel') }}
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-teal-600 dark:bg-purple-900 text-white rounded-md hover:bg-teal-700 dark:hover:bg-purple-800 cursor-pointer">
                        {{ __('ui.comments.form.actions.save') }}
                    </button>
                </div>
            </footer>
        </form>
    </article>
</x-default-layout>
