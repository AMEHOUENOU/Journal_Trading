<nav class="sticky top-0 z-50 bg-gray-900 border-b border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-4">

            {{-- Logo --}}
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 shrink-0">
                <span class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center font-bold text-gray-950 text-sm">T</span>
                <span class="font-semibold text-white text-lg hidden sm:block">TradeLog</span>
            </a>

            {{-- Navigation, centrée --}}
            <div class="flex-1 flex justify-center overflow-x-auto">
                <div class="flex items-center gap-1">

                    @php
                        $navItems = [
                            ['route' => 'dashboard', 'pattern' => 'dashboard', 'label' => 'Accueil', 'icon' => 'home'],
                            ['route' => 'accounts.index', 'pattern' => 'accounts.*', 'label' => 'Comptes', 'icon' => 'wallet'],
                            ['route' => 'tags.index', 'pattern' => 'tags.*', 'label' => 'Stratégies', 'icon' => 'target'],
                            ['route' => 'calendar.index', 'pattern' => 'calendar.*', 'label' => 'Calendrier', 'icon' => 'calendar'],
                            ['route' => 'daily-notes.index', 'pattern' => 'daily-notes.*', 'label' => 'Journal', 'icon' => 'book'],
                        ];
                    @endphp

                    @foreach ($navItems as $item)
                        @php $isActive = request()->routeIs($item['pattern']) || request()->routeIs($item['route']); @endphp
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-1.5 rounded-full transition-all duration-200 h-9 px-3
                                  {{ $isActive
                                        ? 'bg-emerald-500 text-gray-950 shadow-lg shadow-emerald-500/20'
                                        : 'text-gray-400 hover:text-white hover:bg-gray-800/70' }}">

                            @switch($item['icon'])
                                @case('home')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    @break
                                @case('wallet')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6.75A2.25 2.25 0 0018.75 4.5H5.25A2.25 2.25 0 003 6.75V9" />
                                    </svg>
                                    @break
                                @case('target')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="8" stroke-linecap="round" stroke-linejoin="round" />
                                        <circle cx="12" cy="12" r="4" stroke-linecap="round" stroke-linejoin="round" />
                                        <circle cx="12" cy="12" r="0.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    @break
                                @case('calendar')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0V11.25A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                    @break
                                @case('book')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                    </svg>
                                    @break
                            @endswitch

                            <span class="text-sm font-semibold whitespace-nowrap overflow-hidden transition-all duration-200
                                         {{ $isActive ? 'max-w-[100px] opacity-100' : 'max-w-0 opacity-0' }}">
                                {{ $item['label'] }}
                            </span>
                        </a>
                    @endforeach

                </div>
            </div>

            {{-- Profil --}}
            <div class="relative shrink-0" x-data="{ open: false }">
                <button @click="open = !open" class="w-9 h-9 rounded-full bg-gray-800 flex items-center justify-center text-gray-300 hover:text-white transition text-sm font-medium">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </button>
                <div x-show="open" @click.away="open = false" x-cloak
                    class="absolute right-0 mt-2 w-48 bg-gray-900 border border-gray-800 rounded-lg shadow-lg py-1 z-50">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-800">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-800">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</nav>
