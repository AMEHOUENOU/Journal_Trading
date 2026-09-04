<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mes comptes de trading</h2>
            <a href="{{ route('accounts.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">
                + Nouveau compte
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse ($accounts as $account)
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-bold text-gray-900">{{ $account->name }}</h3>
                            <span class="text-xs px-2 py-1 rounded-full {{ $account->current_balance >= $account->initial_balance ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $account->currency }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">{{ $account->broker ?? 'Broker non renseigné' }}</p>

                        <div class="mt-4">
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ number_format($account->current_balance, 2) }} {{ $account->currency }}
                            </p>
                            <p class="text-sm text-gray-500">
                                Départ : {{ number_format($account->initial_balance, 2) }} {{ $account->currency }}
                            </p>
                        </div>

                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('accounts.show', $account) }}" class="text-sm text-indigo-600 hover:underline">Voir</a>
                            <a href="{{ route('accounts.edit', $account) }}" class="text-sm text-gray-600 hover:underline">Modifier</a>
                            <form action="{{ route('accounts.destroy', $account) }}" method="POST" onsubmit="return confirm('Supprimer ce compte et tous ses trades ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-gray-500">
                        Aucun compte pour l'instant. <a href="{{ route('accounts.create') }}" class="text-indigo-600 hover:underline">Crée ton premier compte</a>.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>