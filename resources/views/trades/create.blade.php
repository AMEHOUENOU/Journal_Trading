<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nouveau trade — {{ $account->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <form method="POST" action="{{ route('accounts.trades.store', $account) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="symbol" value="Symbole" />
                            <x-text-input id="symbol" name="symbol" type="text" class="mt-1 block w-full" :value="old('symbol')" required placeholder="ex: EURUSD" />
                            <x-input-error :messages="$errors->get('symbol')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="direction" value="Direction" />
                            <select id="direction" name="direction" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="long" {{ old('direction') === 'long' ? 'selected' : '' }}>Long</option>
                                <option value="short" {{ old('direction') === 'short' ? 'selected' : '' }}>Short</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="entry_price" value="Prix d'entrée" />
                            <x-text-input id="entry_price" name="entry_price" type="number" step="0.00001" class="mt-1 block w-full" :value="old('entry_price')" required />
                            <x-input-error :messages="$errors->get('entry_price')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="exit_price" value="Prix de sortie" />
                            <x-text-input id="exit_price" name="exit_price" type="number" step="0.00001" class="mt-1 block w-full" :value="old('exit_price')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="stop_loss" value="Stop Loss" />
                            <x-text-input id="stop_loss" name="stop_loss" type="number" step="0.00001" class="mt-1 block w-full" :value="old('stop_loss')" />
                        </div>
                        <div>
                            <x-input-label for="take_profit" value="Take Profit" />
                            <x-text-input id="take_profit" name="take_profit" type="number" step="0.00001" class="mt-1 block w-full" :value="old('take_profit')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="position_size" value="Taille de position" />
                            <x-text-input id="position_size" name="position_size" type="number" step="0.01" class="mt-1 block w-full" :value="old('position_size')" required />
                            <x-input-error :messages="$errors->get('position_size')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="pnl" value="P&L ($)" />
                            <x-text-input id="pnl" name="pnl" type="number" step="0.01" class="mt-1 block w-full" :value="old('pnl')" placeholder="laisser vide si trade ouvert" />
                        </div>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="close_status" value="Statut de clôture" />
                        <select id="close_status" name="close_status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="open" {{ old('close_status', 'open') === 'open' ? 'selected' : '' }}>Ouvert</option>
                            <option value="sl_hit" {{ old('close_status') === 'sl_hit' ? 'selected' : '' }}>SL touché</option>
                            <option value="tp_hit" {{ old('close_status') === 'tp_hit' ? 'selected' : '' }}>TP touché</option>
                            <option value="manual" {{ old('close_status') === 'manual' ? 'selected' : '' }}>Clôturé manuellement</option>
                            <option value="breakeven" {{ old('close_status') === 'breakeven' ? 'selected' : '' }}>Breakeven</option>
                        </select>
                        <x-input-error :messages="$errors->get('close_status')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="opened_at" value="Date/heure d'ouverture" />
                            <x-text-input id="opened_at" name="opened_at" type="datetime-local" class="mt-1 block w-full" :value="old('opened_at')" required />
                            <x-input-error :messages="$errors->get('opened_at')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="closed_at" value="Date/heure de clôture" />
                            <x-text-input id="closed_at" name="closed_at" type="datetime-local" class="mt-1 block w-full" :value="old('closed_at')" />
                        </div>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="tags" value="Tags / stratégie" />
                        <select id="tags" name="tags[]" multiple class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Ctrl/Cmd + clic pour sélectionner plusieurs tags. <a href="{{ route('tags.create') }}" class="text-indigo-600 hover:underline">Créer un nouveau tag</a></p>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="photos" value="Screenshots (optionnel, plusieurs possibles)" />
                        <input id="photos" name="photos[]" type="file" accept="image/*" multiple class="mt-1 block w-full text-sm" />
                        <x-input-error :messages="$errors->get('photos.0')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="notes" value="Notes" />
                        <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Pourquoi ce trade ? Qu'as-tu appris ?">{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('accounts.show', $account) }}" class="px-4 py-2 text-sm text-gray-600">Annuler</a>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">
                            Enregistrer le trade
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>