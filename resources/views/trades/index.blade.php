<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">Trades — {{ $account->name }}</h2>
            <a href="{{ route('accounts.trades.create', $account) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">
                + Nouveau trade
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filters --}}
            <form method="GET" class="flex flex-wrap gap-3 mb-4 items-center">
                <input type="text" name="symbol" value="{{ request('symbol') }}" placeholder="Symbole..."
                    class="bg-gray-900 border border-gray-800 text-gray-200 text-sm rounded-lg px-3 py-2 placeholder-gray-600 focus:border-indigo-500 focus:ring-0">

                <select name="close_status" class="bg-gray-900 border border-gray-800 text-gray-200 text-sm rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-0">
                    <option value="">Tous les statuts</option>
                    @foreach (['sl_hit' => 'SL touché', 'tp_hit' => 'TP touché', 'manual' => 'Manuel', 'breakeven' => 'Breakeven', 'open' => 'Ouvert'] as $value => $label)
                        <option value="{{ $value }}" {{ request('close_status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>

                <select name="direction" class="bg-gray-900 border border-gray-800 text-gray-200 text-sm rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-0">
                    <option value="">Long/Short</option>
                    <option value="long" {{ request('direction') === 'long' ? 'selected' : '' }}>Long</option>
                    <option value="short" {{ request('direction') === 'short' ? 'selected' : '' }}>Short</option>
                </select>

                <select name="result" class="bg-gray-900 border border-gray-800 text-gray-200 text-sm rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-0">
                    <option value="">Gagnant/Perdant</option>
                    <option value="win" {{ request('result') === 'win' ? 'selected' : '' }}>Gagnant</option>
                    <option value="loss" {{ request('result') === 'loss' ? 'selected' : '' }}>Perdant</option>
                </select>

                <select name="tag_id" class="bg-gray-900 border border-gray-800 text-gray-200 text-sm rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-0">
                    <option value="">Toutes stratégies</option>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}" {{ request('tag_id') == $tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
                    @endforeach
                </select>

                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="bg-gray-900 border border-gray-800 text-gray-200 text-sm rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-0">
                <span class="text-gray-500 text-sm">→</span>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="bg-gray-900 border border-gray-800 text-gray-200 text-sm rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-0">

                <button type="submit" class="text-sm px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-500 transition">
                    Filtrer
                </button>

                @if (request()->anyFilled(['symbol', 'close_status', 'direction', 'result', 'tag_id', 'date_from', 'date_to']))
                    <a href="{{ route('accounts.trades.index', $account) }}" class="text-sm text-gray-400 hover:text-white transition">
                        Réinitialiser
                    </a>
                @endif
            </form>

            <div class="bg-gray-900 border border-gray-800 rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-800">
                    <thead class="bg-gray-950">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Symbole</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Direction</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">R:R</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">PnL</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Statut</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse ($trades as $trade)
                            <tr class="hover:bg-gray-800/50 cursor-pointer transition" onclick="window.location='{{ route('trades.show', $trade) }}'">
                                <td class="px-4 py-2 font-medium text-white">{{ $trade->symbol }}</td>
                                <td class="px-4 py-2 text-gray-300">{{ strtoupper($trade->direction) }}</td>
                                <td class="px-4 py-2 text-gray-300">{{ $trade->risk_reward ?? '—' }}</td>
                                <td class="px-4 py-2 {{ $trade->pnl > 0 ? 'text-emerald-400' : ($trade->pnl < 0 ? 'text-red-400' : 'text-gray-400') }}">
                                    {{ $trade->pnl !== null ? number_format($trade->pnl, 2) : '—' }}
                                </td>
                                <td class="px-4 py-2 text-xs text-gray-400">{{ $trade->close_status }}</td>
                                <td class="px-4 py-2 text-xs text-gray-500">{{ $trade->opened_at->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">Aucun trade ne correspond à ces filtres.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 text-gray-400">{{ $trades->links() }}</div>
        </div>
    </div>
</x-app-layout>
