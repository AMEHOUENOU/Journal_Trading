<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nouveau compte</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <form method="POST" action="{{ route('accounts.store') }}">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="name" value="Nom du compte" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required placeholder="ex: FTMO 100k" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="broker" value="Broker (optionnel)" />
                        <x-text-input id="broker" name="broker" type="text" class="mt-1 block w-full" :value="old('broker')" placeholder="ex: FTMO, IC Markets" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="initial_balance" value="Capital de départ" />
                        <x-text-input id="initial_balance" name="initial_balance" type="number" step="0.01" class="mt-1 block w-full" :value="old('initial_balance')" required />
                        <x-input-error :messages="$errors->get('initial_balance')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="currency" value="Devise" />
                        <select id="currency" name="currency" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="USD" selected>USD</option>
                            <option value="EUR">EUR</option>
                            <option value="XOF">XOF</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('accounts.index') }}" class="px-4 py-2 text-sm text-gray-600">Annuler</a>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">
                            Créer le compte
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>