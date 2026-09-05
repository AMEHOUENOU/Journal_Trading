<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">Journal quotidien</h2>
            <a href="{{ route('daily-notes.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-500 transition">
                + Nouvelle note
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-950 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-4">
                @forelse ($notes as $note)
                    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-white font-semibold">{{ \Carbon\Carbon::parse($note->date)->format('d/m/Y') }}</p>
                            <div class="flex gap-3">
                                <a href="{{ route('daily-notes.edit', $note) }}" class="text-sm text-indigo-400 hover:text-indigo-300">Modifier</a>
                                <form action="{{ route('daily-notes.destroy', $note) }}" method="POST" onsubmit="return confirm('Supprimer cette note ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-400 hover:text-red-300">Supprimer</button>
                                </form>
                            </div>
                        </div>
                        @if ($note->content)
                            <p class="text-gray-300 text-sm whitespace-pre-line">{{ $note->content }}</p>
                        @else
                            <p class="text-gray-600 text-sm italic">Pas de contenu.</p>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-500">
                        Aucune note pour l'instant. <a href="{{ route('daily-notes.create') }}" class="text-indigo-400 hover:underline">Écris ta première note</a>.
                    </div>
                @endforelse
            </div>

            <div class="mt-4">{{ $notes->links() }}</div>
        </div>
    </div>
</x-app-layout>