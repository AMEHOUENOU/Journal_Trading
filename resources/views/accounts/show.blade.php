<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-white leading-tight">{{ $account->name }}</h2>
                <p class="text-sm text-gray-400">{{ $account->broker ?? 'Broker non renseigné' }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('accounts.trades.export', $account) }}" class="text-sm px-4 py-2 border border-gray-700 rounded-lg text-gray-300 hover:bg-gray-800 transition">
                    Exporter CSV
                </a>
                <a href="{{ route('accounts.trades.create', $account) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-500 transition">
                    + Nouveau trade
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Stats cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Capital</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($account->current_balance, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $account->currency }} · départ {{ number_format($account->initial_balance, 0) }}</p>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Win rate</p>
                    <p class="text-2xl font-bold {{ $winRate >= 50 ? 'text-emerald-400' : 'text-red-400' }}">{{ $winRate }}%</p>
                    <div class="w-full bg-gray-800 rounded-full h-1.5 mt-2">
                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $winRate }}%"></div>
                    </div>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Profit factor</p>
                    <p class="text-2xl font-bold text-white">{{ $profitFactor ?? '—' }}</p>
                    <p class="text-xs text-gray-500 mt-1">gains / pertes</p>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Total trades</p>
                    <p class="text-2xl font-bold text-white">{{ $trades->total() }}</p>
                    <p class="text-xs text-gray-500 mt-1">sur ce compte</p>
                </div>
            </div>

            {{-- Filters --}}
            <form method="GET" class="flex flex-wrap gap-3 mb-4">
                <input type="text" name="symbol" value="{{ request('symbol') }}" placeholder="Rechercher un symbole..."
                    class="bg-gray-900 border border-gray-800 text-gray-200 text-sm rounded-lg px-3 py-2 placeholder-gray-600 focus:border-indigo-500 focus:ring-0">
                <select name="close_status" class="bg-gray-900 border border-gray-800 text-gray-200 text-sm rounded-lg px-3 py-2 focus:border-indigo-500 focus:ring-0">
                    <option value="">Tous les statuts</option>
                    <option value="sl_hit" {{ request('close_status') === 'sl_hit' ? 'selected' : '' }}>SL touché</option>
                    <option value="tp_hit" {{ request('close_status') === 'tp_hit' ? 'selected' : '' }}>TP touché</option>
                    <option value="manual" {{ request('close_status') === 'manual' ? 'selected' : '' }}>Manuel</option>
                    <option value="breakeven" {{ request('close_status') === 'breakeven' ? 'selected' : '' }}>Breakeven</option>
                    <option value="open" {{ request('close_status') === 'open' ? 'selected' : '' }}>Ouvert</option>
                </select>
                <button type="submit" class="text-sm px-4 py-2 bg-gray-800 text-gray-300 rounded-lg hover:bg-gray-700 transition">
                    Filtrer
                </button>
            </form>

            {{-- Trades table --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-800">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Symbole</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Direction</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Entrée</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Sortie</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">R:R</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">PnL</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Statut</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Date</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse ($trades as $trade)
                            <tr class="hover:bg-gray-800/50 transition cursor-pointer" onclick="window.location='{{ route('trades.show', $trade) }}'">
                                <td class="px-4 py-3 font-medium text-white">{{ $trade->symbol }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium rounded-full
                                        {{ $trade->direction === 'long' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' }}">
                                        {{ $trade->direction === 'long' ? '▲' : '▼' }} {{ strtoupper($trade->direction) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-300 text-sm">{{ $trade->entry_price }}</td>
                                <td class="px-4 py-3 text-gray-300 text-sm">{{ $trade->exit_price ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-300">{{ $trade->risk_reward ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm font-semibold {{ $trade->pnl > 0 ? 'text-emerald-400' : ($trade->pnl < 0 ? 'text-red-400' : 'text-gray-400') }}">
                                    {{ $trade->pnl !== null ? ($trade->pnl > 0 ? '+' : '') . number_format($trade->pnl, 2) : '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusStyles = [
                                            'sl_hit' => 'bg-red-500/10 text-red-400',
                                            'tp_hit' => 'bg-emerald-500/10 text-emerald-400',
                                            'manual' => 'bg-amber-500/10 text-amber-400',
                                            'breakeven' => 'bg-gray-500/10 text-gray-400',
                                            'open' => 'bg-blue-500/10 text-blue-400',
                                        ];
                                        $statusLabels = [
                                            'sl_hit' => 'SL touché', 'tp_hit' => 'TP touché',
                                            'manual' => 'Manuel', 'breakeven' => 'Breakeven', 'open' => 'Ouvert',
                                        ];
                                    @endphp
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $statusStyles[$trade->close_status] }}">
                                        {{ $statusLabels[$trade->close_status] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500">{{ $trade->opened_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('trades.edit', $trade) }}" class="text-indigo-400 text-sm hover:text-indigo-300">Modifier</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-12 text-center text-gray-500">
                                    Aucun trade enregistré.
                                    <a href="{{ route('accounts.trades.create', $account) }}" class="text-indigo-400 hover:underline">Ajoute ton premier trade</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 text-gray-400">
                {{ $trades->links() }}
            </div>
        </div>
    </div>
</x-app-layout>