<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">Dashboard</h2>
            <a href="{{ route('accounts.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-500 transition inline-flex items-center gap-2">
                Voir mes comptes
                <span>→</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Stats cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Total trades</p>
                    <p class="text-2xl font-bold text-white">{{ $totalTrades }}</p>
                    <p class="text-xs text-gray-500 mt-1">tous comptes confondus</p>
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
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">R:R moyen</p>
                    <p class="text-2xl font-bold text-white">{{ $avgRR ?? '—' }}</p>
                    <p class="text-xs text-gray-500 mt-1">risque / récompense</p>
                </div>
            </div>

            {{-- Equity curve --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-white font-semibold">Courbe d'équité</h3>
                    <span class="text-xs text-gray-500">Cumulée · tous comptes</span>
                </div>
                @if (count($equityCurve) > 0)
                    <canvas id="equityChart" height="80"></canvas>
                @else
                    <div class="h-40 flex items-center justify-center text-gray-500 text-sm">
                        Pas encore assez de trades clôturés pour afficher la courbe.
                    </div>
                @endif
            </div>

            {{-- Accounts list --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                <div class="flex justify-between items-center px-5 py-4 border-b border-gray-800">
                    <h3 class="text-white font-semibold">Mes comptes</h3>
                    <a href="{{ route('accounts.index') }}" class="text-sm text-indigo-400 hover:text-indigo-300">
                        Voir tout →
                    </a>
                </div>
                <div class="divide-y divide-gray-800">
                    @forelse ($accounts as $account)
                        <a href="{{ route('accounts.show', $account) }}" class="flex justify-between items-center px-5 py-4 hover:bg-gray-800/50 transition">
                            <div>
                                <p class="text-white font-medium">{{ $account->name }}</p>
                                <p class="text-xs text-gray-500">{{ $account->broker ?? 'Broker non renseigné' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-white font-semibold">{{ number_format($account->current_balance, 2) }} {{ $account->currency }}</p>
                                <p class="text-xs {{ $account->current_balance >= $account->initial_balance ? 'text-emerald-400' : 'text-red-400' }}">
                                    {{ $account->current_balance >= $account->initial_balance ? '+' : '' }}{{ number_format($account->current_balance - $account->initial_balance, 2) }}
                                </p>
                            </div>
                        </a>
                    @empty
                        <div class="px-5 py-10 text-center text-gray-500">
                            Aucun compte pour l'instant.
                            <a href="{{ route('accounts.create') }}" class="text-indigo-400 hover:underline">Crée ton premier compte</a>.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    @if (count($equityCurve) > 0)
        @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
        <script>
            const ctx = document.getElementById('equityChart');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_column($equityCurve, 'date')) !!},
                    datasets: [{
                        label: 'Capital cumulé',
                        data: {!! json_encode(array_column($equityCurve, 'balance')) !!},
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointRadius: 0,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { color: '#1f2937' }, ticks: { color: '#6b7280', maxTicksLimit: 8 } },
                        y: { grid: { color: '#1f2937' }, ticks: { color: '#6b7280' } }
                    }
                }
            });
        </script>
        @endpush
    @endif
</x-app-layout>