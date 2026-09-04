<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">{{ $trade->symbol }} — Récapitulatif</h2>
            <div class="flex gap-2">
                <a href="{{ route('accounts.show', $trade->account) }}" class="text-sm px-4 py-2 border border-gray-700 rounded-lg text-gray-300 hover:bg-gray-800 transition">
                    ← Retour au compte
                </a>
                <a href="{{ route('trades.edit', $trade) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-500 transition">
                    Modifier
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-950 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">

                {{-- Header badges --}}
                <div class="flex items-center gap-3 mb-6">
                    <span class="inline-flex items-center gap-1 px-3 py-1 text-sm font-semibold rounded-full
                        {{ $trade->direction === 'long' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' }}">
                        {{ $trade->direction === 'long' ? '▲' : '▼' }} {{ strtoupper($trade->direction) }}
                    </span>
                    @php
                        $statusStyles = [
                            'sl_hit' => 'bg-red-500/10 text-red-400', 'tp_hit' => 'bg-emerald-500/10 text-emerald-400',
                            'manual' => 'bg-amber-500/10 text-amber-400', 'breakeven' => 'bg-gray-500/10 text-gray-400',
                            'open' => 'bg-blue-500/10 text-blue-400',
                        ];
                        $statusLabels = [
                            'sl_hit' => 'SL touché', 'tp_hit' => 'TP touché', 'manual' => 'Manuel',
                            'breakeven' => 'Breakeven', 'open' => 'Ouvert',
                        ];
                    @endphp
                    <span class="px-3 py-1 text-sm font-medium rounded-full {{ $statusStyles[$trade->close_status] }}">
                        {{ $statusLabels[$trade->close_status] }}
                    </span>
                </div>

                {{-- Key numbers --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gray-950 border border-gray-800 rounded-lg p-4">
                        <p class="text-xs text-gray-500 uppercase mb-1">Entrée</p>
                        <p class="text-white font-semibold">{{ $trade->entry_price }}</p>
                    </div>
                    <div class="bg-gray-950 border border-gray-800 rounded-lg p-4">
                        <p class="text-xs text-gray-500 uppercase mb-1">Sortie</p>
                        <p class="text-white font-semibold">{{ $trade->exit_price ?? '—' }}</p>
                    </div>
                    <div class="bg-gray-950 border border-gray-800 rounded-lg p-4">
                        <p class="text-xs text-gray-500 uppercase mb-1">R:R</p>
                        <p class="text-white font-semibold">{{ $trade->risk_reward ?? '—' }}</p>
                    </div>
                    <div class="bg-gray-950 border border-gray-800 rounded-lg p-4">
                        <p class="text-xs text-gray-500 uppercase mb-1">P&L</p>
                        <p class="font-semibold {{ $trade->pnl > 0 ? 'text-emerald-400' : ($trade->pnl < 0 ? 'text-red-400' : 'text-white') }}">
                            {{ $trade->pnl !== null ? ($trade->pnl > 0 ? '+' : '') . number_format($trade->pnl, 2) : '—' }}
                        </p>
                    </div>
                </div>

                {{-- Secondary details --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6 text-sm">
                    <div><span class="text-gray-500">Stop loss :</span> <span class="text-gray-200">{{ $trade->stop_loss ?? '—' }}</span></div>
                    <div><span class="text-gray-500">Take profit :</span> <span class="text-gray-200">{{ $trade->take_profit ?? '—' }}</span></div>
                    <div><span class="text-gray-500">Taille :</span> <span class="text-gray-200">{{ $trade->position_size }}</span></div>
                    <div><span class="text-gray-500">Ouvert le :</span> <span class="text-gray-200">{{ $trade->opened_at->format('d/m/Y H:i') }}</span></div>
                    <div><span class="text-gray-500">Clôturé le :</span> <span class="text-gray-200">{{ $trade->closed_at?->format('d/m/Y H:i') ?? '—' }}</span></div>
                    <div><span class="text-gray-500">Compte :</span> <span class="text-gray-200">{{ $trade->account->name }}</span></div>
                </div>

                {{-- Tags --}}
                @if ($trade->tags->isNotEmpty())
                    <div class="mb-6">
                        <p class="text-xs text-gray-500 uppercase mb-2">Tags</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($trade->tags as $tag)
                                <span class="px-3 py-1 text-xs bg-gray-800 text-gray-300 rounded-full">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Notes --}}
                @if ($trade->notes)
                    <div class="mb-6">
                        <p class="text-xs text-gray-500 uppercase mb-2">Notes</p>
                        <p class="text-gray-300 text-sm whitespace-pre-line">{{ $trade->notes }}</p>
                    </div>
                @endif

                {{-- Photos gallery --}}
                @if ($trade->photos->isNotEmpty())
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-2">Screenshots ({{ $trade->photos->count() }})</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach ($trade->photos as $photo)
                                <a href="{{ Storage::url($photo->path) }}" target="_blank">
                                    <img src="{{ Storage::url($photo->path) }}" class="rounded-lg w-full h-32 object-cover border border-gray-800 hover:opacity-80 transition">
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mt-8 pt-6 border-t border-gray-800 flex justify-between items-center">
                    <form action="{{ route('trades.destroy', $trade) }}" method="POST" onsubmit="return confirm('Supprimer ce trade ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-400 hover:text-red-300">Supprimer ce trade</button>
                    </form>
                    <a href="{{ route('trades.edit', $trade) }}" class="text-sm text-indigo-400 hover:text-indigo-300">Modifier ce trade →</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>