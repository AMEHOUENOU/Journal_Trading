<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Nouvelle note</h2>
    </x-slot>

    <div class="py-8 bg-gray-950 min-h-screen">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <form method="POST" action="{{ route('daily-notes.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm text-gray-300 mb-1">Date</label>
                        <input type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required
                            class="w-full bg-gray-950 border border-gray-800 text-gray-200 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-0">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm text-gray-300 mb-1">Note</label>
                        <textarea name="content" rows="8" placeholder="État d'esprit, observations sur le marché, leçons du jour..."
                            class="w-full bg-gray-950 border border-gray-800 text-gray-200 rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-0">{{ old('content') }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('daily-notes.index') }}" class="px-4 py-2 text-sm text-gray-400">Annuler</a>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-500 transition">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>