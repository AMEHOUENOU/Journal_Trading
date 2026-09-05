<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">Mes stratégies</h2>
            <a href="{{ route('tags.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-500 transition">
                + Nouvelle stratégie
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-950 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($tags as $tag)
                <a href="{{ route('tags.show', $tag) }}" class="bg-gray-900 border border-gray-800 rounded-xl p-5 hover:border-indigo-500/50 transition">
                    <h3 class="text-white font-semibold mb-1">{{ $tag->name }}</h3>
                    <p class="text-xs text-gray-500 mb-3">{{ $tag->trades_count }} trade(s)</p>
                    @if ($tag->description)
                        <p class="text-sm text-gray-400 line-clamp-2">{{ $tag->description }}</p>
                    @endif
                </a>
            @empty
                <div class="col-span-full text-center py-12 text-gray-500">
                    Aucune stratégie pour l'instant. <a href="{{ route('tags.create') }}" class="text-indigo-400 hover:underline">Crée ta première stratégie</a>.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>