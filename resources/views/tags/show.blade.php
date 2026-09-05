<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">{{ $tag->name }}</h2>
            <a href="{{ route('tags.edit', $tag) }}" class="text-sm px-4 py-2 border border-gray-700 rounded-lg text-gray-300 hover:bg-gray-800 transition">
                Modifier
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-950 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                    <p class="text-xs text-gray-500 uppercase mb-1">Win rate</p>
                    <p class="text-2xl font-bold {{ $winRate >= 50 ? 'text-emerald-400' : 'text-red-400' }}">{{ $winRate }}%</p>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                    <p class="text-xs text-gray-500 uppercase mb-1">Profit factor</p>
                    <p class="text-2xl font-bold text-white">{{ $profitFactor ?? '—' }}</p>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                    <p class="text-xs text-gray-500 uppercase mb-1">Total trades</p>
                    <p class="text-2xl font-bold text-white">{{ $trades->total() }}</p>
                </div>
            </div>

            {{-- Description & rules --}}
            <div class="grid sm:grid-cols-3 gap-4 mb-6">
                @if ($tag->description)
                    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                        <p class="text-xs text-gray-500 uppercase mb-2">Description</p>
                        <p class="text-sm text-gray-300 whitespace-pre-line">{{ $tag->description }}</p>
                    </div>
                @endif
                @if ($tag->entry_rules)
                    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                        <p class="text-xs text-gray-500 uppercase mb-2">Règles d'entrée</p>
                        <p class="text-sm text-gray-300 whitespace-pre-line">{{ $tag->entry_rules }}</p>
                    </div>
                @endif
                @if ($tag->exit_rules)
                    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                        <p class="text-xs text-gray-500 uppercase mb-2">Règles de sortie</p>
                        <p class="text-sm text-gray-300 whitespace-pre-line">{{ $tag->exit_rules }}</p>
                    </div>
                @endif
            </div>

            {{-- Trades using this strategy --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-800">
                    <h3 class="text-white font-semibold text-sm">Trades avec cette stratégie</h3>
                </div>
                <table class="min-w-full text-sm">
                    <tbody class="divide-y divide-gray-800">
                        @forelse ($trades as $trade)
                            <tr class="hover:bg-gray-800/50 transition cursor-pointer" onclick="window.location='{{ route('trades.show', $trade) }}'">
                                <td class="px-4 py-3 font-medium text-white">{{ $trade->symbol }}</td>
                                <td class="px-4 py-3 text-gray-400">{{ strtoupper($trade->direction) }}</td>
                                <td class="px-4 py-3 font-semibold {{ $trade->pnl > 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                    {{ $trade->pnl !== null ? number_format($trade->pnl, 2) : '—' }}
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500">{{ $trade->opened_at->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Aucun trade avec cette stratégie.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-gray-400">{{ $trades->links() }}</div>
        </div>
    </div>
</x-app-layout>