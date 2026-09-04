<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'TradeLog') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-950 text-gray-100 overflow-x-hidden">

    {{-- Nav --}}
    <nav class="max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <span class="w-9 h-9 bg-gradient-to-br from-violet-500 to-indigo-600 rounded-lg flex items-center justify-center text-white text-lg">📊</span>
            <span class="font-bold text-xl text-white">TradeLog</span>
        </div>
        <div class="hidden lg:flex items-center gap-8 text-sm text-gray-300">
            <a href="#features" class="hover:text-white transition">Fonctionnalités</a>
            <div class="relative group">
                <a href="#" class="hover:text-white transition flex items-center gap-1">Ressources <span class="text-xs">▾</span></a>
            </div>
            <a href="#about" class="hover:text-white transition">À propos</a>
        </div>
        <div class="flex items-center gap-4">
            <button class="hidden sm:flex items-center gap-1 text-sm text-gray-300 hover:text-white transition">
                🌐 FR <span class="text-xs">▾</span>
            </button>
            @auth
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-300 hover:text-white transition font-medium">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-sm text-gray-300 hover:text-white transition font-medium">Se connecter</a>
                <a href="{{ route('register') }}" class="text-sm px-5 py-2.5 bg-gradient-to-r from-violet-500 to-indigo-600 rounded-lg hover:opacity-90 transition font-semibold">
                    Commencer gratuitement
                </a>
            @endauth
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6">

        {{-- Hero --}}
        <section class="pt-16 pb-24 grid lg:grid-cols-2 gap-12 items-center">

            {{-- Left --}}
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gray-900 border border-gray-800 text-xs text-gray-300 mb-6">
                    ✨ Votre performance. Vos données. Votre avantage.
                </div>

                <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight leading-[1.1] text-white">
                    Le journal de trading<br>
                    qui fait de vous un<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-indigo-400">meilleur trader.</span>
                </h1>

                <p class="mt-6 text-lg text-gray-400 max-w-md">
                    Trackez, analysez et améliorez vos performances. Prenez de meilleures
                    décisions grâce à des statistiques puissantes et des insights basés sur vos données.
                </p>

                <div class="mt-8 flex flex-wrap gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-6 py-3.5 bg-gradient-to-r from-violet-500 to-indigo-600 rounded-lg font-semibold hover:opacity-90 transition inline-flex items-center gap-2">
                            Aller au dashboard →
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="px-6 py-3.5 bg-gradient-to-r from-violet-500 to-indigo-600 rounded-lg font-semibold hover:opacity-90 transition inline-flex items-center gap-2">
                            Commencer gratuitement →
                        </a>
                        <a href="#demo" class="px-6 py-3.5 border border-gray-700 rounded-lg font-semibold text-gray-200 hover:border-gray-500 transition inline-flex items-center gap-2">
                            ▷ Voir la démo
                        </a>
                    @endauth
                </div>

                <div class="mt-10 flex flex-wrap gap-6">
                    <div class="flex items-center gap-2">
                        <span class="w-9 h-9 bg-violet-500/10 rounded-lg flex items-center justify-center text-violet-400">📈</span>
                        <span class="text-sm text-gray-300">Suivi complet<br><span class="text-gray-500 text-xs">de vos trades</span></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-9 h-9 bg-violet-500/10 rounded-lg flex items-center justify-center text-violet-400">🛡️</span>
                        <span class="text-sm text-gray-300">Données sécurisées<br><span class="text-gray-500 text-xs">et privées</span></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-9 h-9 bg-violet-500/10 rounded-lg flex items-center justify-center text-violet-400">🧠</span>
                        <span class="text-sm text-gray-300">Insights IA<br><span class="text-gray-500 text-xs">pour progresser</span></span>
                    </div>
                </div>
            </div>

            {{-- Right: dashboard mockup --}}
            <div class="relative">
                <div class="bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl shadow-violet-950/40 overflow-hidden flex">

                    {{-- Sidebar --}}
                    <div class="hidden md:block w-40 bg-gray-950/60 border-r border-gray-800 p-4 text-xs">
                        <div class="flex items-center gap-2 mb-6">
                            <span class="w-6 h-6 bg-gradient-to-br from-violet-500 to-indigo-600 rounded flex items-center justify-center text-[10px]">📊</span>
                            <span class="font-bold text-white text-sm">TradeLog</span>
                        </div>
                        <div class="space-y-1">
                            <div class="px-2 py-1.5 bg-violet-600 rounded-md text-white font-medium">📋 Dashboard</div>
                            <div class="px-2 py-1.5 text-gray-400">📑 Mes trades</div>
                            <div class="px-2 py-1.5 text-gray-400">📈 Analytics</div>
                            <div class="px-2 py-1.5 text-gray-400">📅 Calendrier</div>
                            <div class="px-2 py-1.5 text-gray-400">🧠 Psychologie</div>
                            <div class="px-2 py-1.5 text-gray-400">🎯 Objectifs</div>
                            <div class="px-2 py-1.5 text-gray-400">📝 Notes</div>
                            <div class="px-2 py-1.5 text-gray-400">📄 Rapports</div>
                        </div>
                        <div class="mt-8 space-y-1 text-gray-500">
                            <div class="px-2 py-1.5">⚙️ Paramètres</div>
                            <div class="px-2 py-1.5">❓ Aide & support</div>
                        </div>
                    </div>

                    {{-- Main --}}
                    <div class="flex-1 p-5">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <p class="text-white font-semibold text-sm">Bonjour Parfait 👋</p>
                                <p class="text-gray-500 text-xs">Voici un aperçu de vos performances.</p>
                            </div>
                        </div>

                        {{-- Stats row --}}
                        <div class="grid grid-cols-3 gap-2 mb-4">
                            <div class="bg-gray-950 border border-gray-800 rounded-lg p-2.5">
                                <p class="text-[9px] text-gray-500 uppercase">P&L net</p>
                                <p class="text-sm font-bold text-emerald-400">+2 430,75 $</p>
                            </div>
                            <div class="bg-gray-950 border border-gray-800 rounded-lg p-2.5">
                                <p class="text-[9px] text-gray-500 uppercase">Win rate</p>
                                <p class="text-sm font-bold text-white">68.2%</p>
                            </div>
                            <div class="bg-gray-950 border border-gray-800 rounded-lg p-2.5">
                                <p class="text-[9px] text-gray-500 uppercase">Profit factor</p>
                                <p class="text-sm font-bold text-white">2.13</p>
                            </div>
                        </div>

                        {{-- Equity curve --}}
                        <div class="bg-gray-950 border border-gray-800 rounded-lg p-3 mb-3">
                            <p class="text-[10px] text-gray-500 uppercase mb-2">Courbe d'équity</p>
                            <div class="h-16 flex items-end gap-0.5">
                                @php $mini = [30,45,38,55,48,62,58,70,65,78,72,85,80,92,88,100]; @endphp
                                @foreach ($mini as $b)
                                    <div class="flex-1 bg-gradient-to-t from-violet-600 to-indigo-400 rounded-t" style="height: {{ $b }}%"></div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Trading score + trades --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-gray-950 border border-gray-800 rounded-lg p-3">
                                <p class="text-[10px] text-gray-500 uppercase mb-2">Trading score</p>
                                <div class="flex items-center gap-2">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold text-white"
                                         style="background: conic-gradient(#a78bfa 0% 82%, #1f2937 82% 100%)">
                                        <div class="w-7 h-7 bg-gray-950 rounded-full flex items-center justify-center text-[10px]">82</div>
                                    </div>
                                    <p class="text-[9px] text-gray-500">Discipline 92%<br>Gestion risque 88%</p>
                                </div>
                            </div>
                            <div class="bg-gray-950 border border-gray-800 rounded-lg p-3">
                                <p class="text-[10px] text-gray-500 uppercase mb-2">Derniers trades</p>
                                <div class="space-y-1 text-[10px]">
                                    <div class="flex justify-between"><span class="text-gray-300">EUR/USD</span><span class="text-emerald-400">+180 $</span></div>
                                    <div class="flex justify-between"><span class="text-gray-300">XAU/USD</span><span class="text-red-400">-75.50 $</span></div>
                                    <div class="flex justify-between"><span class="text-gray-300">NAS100</span><span class="text-emerald-400">+230.25 $</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

             
            </div>
        </section>

        

        {{-- 4-column features --}}
        <section id="features" class="py-16 border-t border-gray-900 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div>
                <div class="w-11 h-11 bg-violet-500/10 rounded-xl flex items-center justify-center mb-4 text-violet-400 text-xl">📊</div>
                <h3 class="font-semibold text-white mb-2">Journalisez sans effort</h3>
                <p class="text-sm text-gray-400">Ajoutez vos trades en quelques secondes et centralisez toutes vos données au même endroit.</p>
            </div>
            <div>
                <div class="w-11 h-11 bg-violet-500/10 rounded-xl flex items-center justify-center mb-4 text-violet-400 text-xl">🥧</div>
                <h3 class="font-semibold text-white mb-2">Analysez en profondeur</h3>
                <p class="text-sm text-gray-400">Des statistiques avancées et des graphiques clairs pour comprendre vos forces et vos faiblesses.</p>
            </div>
            <div>
                <div class="w-11 h-11 bg-violet-500/10 rounded-xl flex items-center justify-center mb-4 text-violet-400 text-xl">🧠</div>
                <h3 class="font-semibold text-white mb-2">Progressez avec l'IA</h3>
                <p class="text-sm text-gray-400">Recevez des insights personnalisés et des recommandations basées sur vos performances.</p>
            </div>
            <div>
                <div class="w-11 h-11 bg-violet-500/10 rounded-xl flex items-center justify-center mb-4 text-violet-400 text-xl">🎯</div>
                <h3 class="font-semibold text-white mb-2">Atteignez vos objectifs</h3>
                <p class="text-sm text-gray-400">Fixez vos objectifs, suivez vos progrès et restez discipliné grâce à des outils conçus pour votre réussite.</p>
            </div>
        </section>

    </main>

    <footer class="max-w-7xl mx-auto px-6 py-10 text-center text-sm text-gray-600 border-t border-gray-900">
        © {{ date('Y') }} TradeLog — Construit avec Laravel.
    </footer>

</body>
</html>