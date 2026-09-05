<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">Calendrier économique — cette semaine</h2>
    </x-slot>

    <div class="py-8 bg-gray-950 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4 p-3 bg-amber-500/10 border border-amber-500/30 text-amber-400 text-sm rounded-lg">
                ⚠️ Évite d'ouvrir de nouvelles positions juste avant/après un événement à fort impact (rouge) — la volatilité peut fausser tes SL/TP.
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-800">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Devise</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Événement</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Impact</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prévision</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Précédent</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse ($events as $event)
                            <tr class="hover:bg-gray-800/50">
                                <td class="px-4 py-3 text-gray-300">{{ $event['date']->format('D d/m H:i') }}</td>
                                <td class="px-4 py-3 font-medium text-white">{{ $event['country'] }}</td>
                                <td class="px-4 py-3 text-gray-300">{{ $event['title'] }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $impactStyles = ['High' => 'bg-red-500/10 text-red-400', 'Medium' => 'bg-amber-500/10 text-amber-400', 'Low' => 'bg-gray-500/10 text-gray-400'];
                                    @endphp
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $impactStyles[$event['impact']] ?? $impactStyles['Low'] }}">
                                        {{ $event['impact'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-400">{{ $event['forecast'] ?? '—' }}</td>
                                <td class="px-4 py-3 text-gray-400">{{ $event['previous'] ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">Calendrier indisponible pour le moment.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>