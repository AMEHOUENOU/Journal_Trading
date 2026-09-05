<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Modifier — {{ $tag->name }}</h2>
    </x-slot>

    <div class="py-8 bg-gray-950 min-h-screen">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <form method="POST" action="{{ route('tags.update', $tag) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm text-gray-300 mb-1">Nom</label>
                        <input type="text" name="name" value="{{ old('name', $tag->name) }}" required
                            class="w-full bg-gray-950 border border-gray-800 text-gray-200 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-0">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm text-gray-300 mb-1">Description</label>
                        <textarea name="description" rows="3"
                            class="w-full bg-gray-950 border border-gray-800 text-gray-200 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-0">{{ old('description', $tag->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm text-gray-300 mb-1">Règles d'entrée</label>
                        <textarea name="entry_rules" rows="3"
                            class="w-full bg-gray-950 border border-gray-800 text-gray-200 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-0">{{ old('entry_rules', $tag->entry_rules) }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm text-gray-300 mb-1">Règles de sortie</label>
                        <textarea name="exit_rules" rows="3"
                            class="w-full bg-gray-950 border border-gray-800 text-gray-200 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-0">{{ old('exit_rules', $tag->exit_rules) }}</textarea>
                    </div>

                    <div class="flex justify-between items-center">
                        <form action="{{ route('tags.destroy', $tag) }}" method="POST" onsubmit="return confirm('Supprimer cette stratégie ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-400 hover:text-red-300">Supprimer</button>
                        </form>
                        <div class="flex gap-3">
                            <a href="{{ route('tags.show', $tag) }}" class="px-4 py-2 text-sm text-gray-400">Annuler</a>
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-500 transition">
                                Enregistrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>