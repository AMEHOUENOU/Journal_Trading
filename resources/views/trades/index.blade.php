<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Trades — {{ $account->name }}</h2>
            <a href="{{ route('accounts.trades.create', $account) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">
                + Nouveau trade
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <form method="GET" class="mb-4 flex gap-3">
                <input type="text" name="symbol" value="{{ request('symbol') }}" placeholder="Filtrer par symbole" class="border-gray-300 rounded-md text-sm">
                <select name="close_status" class="border-gray-300 rounded-md text-sm">
                    <option value="">Tous les statuts</option>
                    @foreach (['sl_hit' => 'SL touché', 'tp_hit' => 'TP touché', 'manual' => 'Manuel', 'breakeven' => 'Breakeven', 'open' => 'Ouvert'] as $value => $label)
                        <option value="{{ $value }}" {{ request('close_status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="text-sm px-3 py-1 border rounded-md">Filtrer</button>
            </form>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Symbole</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Direction</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">R:R</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">PnL</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Statut</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($trades as $trade)
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('trades.edit', $trade) }}'">
                                <td class="px-4 py-2 font-medium">{{ $trade->symbol }}</td>
                                <td class="px-4 py-2">{{ strtoupper($trade->direction) }}</td>
                                <td class="px-4 py-2">{{ $trade->risk_reward ?? '—' }}</td>
                                <td class="px-4 py-2 {{ $trade->pnl > 0 ? 'text-green-600' : ($trade->pnl < 0 ? 'text-red-600' : '') }}">
                                    {{ $trade->pnl !== null ? number_format($trade->pnl, 2) : '—' }}
                                </td>
                                <td class="px-4 py-2 text-xs">{{ $trade->close_status }}</td>
                                <td class="px-4 py-2 text-xs text-gray-500">{{ $trade->opened_at->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">Aucun trade.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $trades->links() }}</div>
        </div>
    </div>
</x-app-layout>