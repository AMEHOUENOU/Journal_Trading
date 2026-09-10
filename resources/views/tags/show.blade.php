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

            {{-- Backtest par actif --}}
<div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden mb-6">
    <div class="flex justify-between items-center px-5 py-4 border-b border-gray-800">
        <h3 class="text-white font-semibold text-sm">Rapport de backtest par actif</h3>
        <button onclick="document.getElementById('add-instrument-form').classList.toggle('hidden')" class="text-sm text-indigo-400 hover:text-indigo-300">
            + Ajouter un actif
        </button>
    </div>

    {{-- Formulaire d'ajout (masqué par défaut) --}}
    <form id="add-instrument-form" action="{{ route('tags.instruments.store', $tag) }}" method="POST" class="hidden px-5 py-4 border-b border-gray-800 grid grid-cols-2 sm:grid-cols-5 gap-2">
        @csrf
        <input type="text" name="symbol" placeholder="Actif (ex: GBP/JPY)" required class="bg-gray-950 border border-gray-800 text-gray-200 text-xs rounded px-2 py-1.5">
        <input type="text" name="timeframe" placeholder="TF (ex: M5)" required class="bg-gray-950 border border-gray-800 text-gray-200 text-xs rounded px-2 py-1.5">
        <select name="bias" required class="bg-gray-950 border border-gray-800 text-gray-200 text-xs rounded px-2 py-1.5">
            <option value="achat">Achat</option>
            <option value="vente">Vente</option>
            <option value="neutre">Neutre</option>
        </select>
        <input type="number" step="0.1" name="target_rr" placeholder="RR cible" required class="bg-gray-950 border border-gray-800 text-gray-200 text-xs rounded px-2 py-1.5">
        <input type="text" name="breakeven_type" placeholder="BE (ex: BE(2))" class="bg-gray-950 border border-gray-800 text-gray-200 text-xs rounded px-2 py-1.5">
        <input type="text" name="active_days" placeholder="Jours actifs" class="bg-gray-950 border border-gray-800 text-gray-200 text-xs rounded px-2 py-1.5">
        <input type="text" name="inactive_months" placeholder="Mois inactif" class="bg-gray-950 border border-gray-800 text-gray-200 text-xs rounded px-2 py-1.5">
        <input type="number" step="0.01" name="backtest_rr_percent" placeholder="RR 20 ans %" class="bg-gray-950 border border-gray-800 text-gray-200 text-xs rounded px-2 py-1.5">
        <input type="number" step="0.01" name="win_rate" placeholder="WR %" class="bg-gray-950 border border-gray-800 text-gray-200 text-xs rounded px-2 py-1.5">
        <input type="number" step="0.01" name="drawdown" placeholder="DD %" class="bg-gray-950 border border-gray-800 text-gray-200 text-xs rounded px-2 py-1.5">
        <button type="submit" class="col-span-2 sm:col-span-1 bg-indigo-600 text-white text-xs rounded px-3 py-1.5 hover:bg-indigo-500">Ajouter</button>
    </form>

    <div class="overflow-x-auto">
        <table class="min-w-full text-xs">
            <thead>
                <tr class="border-b border-gray-800 text-gray-500 uppercase">
                    <th class="px-3 py-2 text-left">Actif</th>
                    <th class="px-3 py-2 text-left">TF</th>
                    <th class="px-3 py-2 text-left">Biais</th>
                    <th class="px-3 py-2 text-left">RR</th>
                    <th class="px-3 py-2 text-left">BE</th>
                    <th class="px-3 py-2 text-left">Jours actifs</th>
                    <th class="px-3 py-2 text-left">Mois inactif</th>
                    <th class="px-3 py-2 text-left">RR (20 ans)</th>
                    <th class="px-3 py-2 text-left">WR</th>
                    <th class="px-3 py-2 text-left">DD</th>
                    <th class="px-3 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse ($tag->instruments as $instrument)
                    <tr class="hover:bg-gray-800/50">
                        <td class="px-3 py-2 font-medium text-white">{{ $instrument->symbol }}</td>
                        <td class="px-3 py-2 text-gray-300">{{ $instrument->timeframe }}</td>
                        <td class="px-3 py-2">
                            @php
                                $biasStyles = ['achat' => 'text-emerald-400', 'vente' => 'text-red-400', 'neutre' => 'text-gray-400'];
                            @endphp
                            <span class="{{ $biasStyles[$instrument->bias] }}">{{ ucfirst($instrument->bias) }}</span>
                        </td>
                        <td class="px-3 py-2 text-gray-300">{{ $instrument->target_rr }}</td>
                        <td class="px-3 py-2 text-gray-400">{{ $instrument->breakeven_type ?? '—' }}</td>
                        <td class="px-3 py-2 text-gray-400">{{ $instrument->active_days ?? '—' }}</td>
                        <td class="px-3 py-2 text-gray-400">{{ $instrument->inactive_months ?? 'Aucun' }}</td>
                        <td class="px-3 py-2 text-emerald-400 font-medium">{{ $instrument->backtest_rr_percent !== null ? $instrument->backtest_rr_percent . '%' : '—' }}</td>
                        <td class="px-3 py-2 text-gray-300">{{ $instrument->win_rate !== null ? $instrument->win_rate . '%' : '—' }}</td>
                        <td class="px-3 py-2 text-red-400">{{ $instrument->drawdown !== null ? $instrument->drawdown . '%' : '—' }}</td>
                        <td class="px-3 py-2 text-right">
                            <form action="{{ route('tags.instruments.destroy', [$tag, $instrument]) }}" method="POST" onsubmit="return confirm('Supprimer cet actif ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300">✕</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="11" class="px-3 py-6 text-center text-gray-500">Aucun actif backtesté pour cette stratégie.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
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