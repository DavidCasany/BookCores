<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __($llibre->titol) }} - BookCores</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,900|georgia:400&display=swap" rel="stylesheet" />

    {{-- ⚡ SCRIPT CRÍTIC ANTIFLAIX --}}
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Figtree', 'sans-serif'],
                        serif: ['Georgia', 'serif']
                    },
                    animation: {
                        'spin-slow': 'spin 4s linear infinite'
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="antialiased bg-slate-100 text-slate-800 dark:bg-slate-900 dark:text-slate-100 transition-colors duration-500"
    x-data="{ 
        scrollAtTop: false,
        darkMode: document.documentElement.classList.contains('dark'),
        toggleTheme() {
            this.darkMode = !this.darkMode;
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        }
    }">

    {{-- 📊 CÀLCUL DINÀMIC DE LA NOTA I CONTROL DE RUTA ANTERIOR --}}
    @php
        $ressenyes = $llibre->ressenyes;
        $teRessenyes = $ressenyes->count() > 0;
        $mitjana = $teRessenyes ? $ressenyes->avg('puntuacio') : null;

        // Detectar si venim de la cerca per tornar allà
        $previousUrl = url()->previous();
        $isFromSearch = str_contains($previousUrl, 'cerca');
    @endphp

    {{-- HEADER SIMPLIFICAT (Sense Biblioteca ni Cistella) --}}
    <header class="fixed w-full z-50 py-3 transition-colors duration-300">
        <div class="absolute inset-0 bg-white/20 backdrop-blur-md border-b border-white/20 shadow-sm -z-10 transition-colors duration-300"
            :class="scrollAtTop ? 'bg-white/10 border-white/20' : 'bg-white/70 dark:bg-slate-900/80 border-slate-200 dark:border-slate-700 shadow-md'"></div>

        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex justify-between items-center">
                {{-- LOGO --}}
                <div class="flex-shrink-0 flex items-center gap-2">
                    <a href="{{ route('home') }}" class="font-serif text-2xl font-bold flex items-center gap-2 transition-colors">
                        <svg class="h-8 w-8 drop-shadow-md transition-colors duration-300" viewBox="0 0 24 24" fill="none"
                            :stroke="scrollAtTop ? '#3b82f6' : (darkMode ? '#60a5fa' : '#2563eb')" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        <span class="font-bold transition-colors duration-300" :class="scrollAtTop ? 'text-white' : 'text-slate-900 dark:text-white'">Book</span>
                        <span class="drop-shadow-md font-bold transition-colors duration-300" :class="scrollAtTop ? 'text-blue-500' : 'text-blue-600 dark:text-blue-400'">Cores</span>
                    </a>
                </div>

                <div class="flex items-center space-x-6">
                    
                    {{-- LUPA (Visible sempre) --}}
                    <a href="{{ route('cerca.index') }}" class="p-2 transition transform hover:scale-110"
                        :class="scrollAtTop ? 'text-white hover:text-blue-200' : 'text-slate-600 dark:text-slate-300 hover:text-blue-600'"
                        title="{{ __('Cerca') }}">
                        <svg class="w-6 h-6 drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </a>

                    {{-- IDIOMA --}}
                    <form action="{{ route('home') }}" method="GET" class="hidden sm:flex items-center">
                        <div class="relative group">
                            <select name="lang" onchange="this.form.submit()" class="appearance-none bg-transparent rounded-full py-1 pl-4 pr-8 text-sm font-bold cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 border"
                                :class="scrollAtTop ? 'text-white border-white/50 hover:bg-white/10' : 'text-slate-900 dark:text-white border-slate-300 dark:border-slate-600 hover:border-blue-500'">
                                <option value="ca" class="text-slate-900" {{ app()->getLocale() == 'ca' ? 'selected' : '' }}>CA</option>
                                <option value="es" class="text-slate-900" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>ES</option>
                                <option value="en" class="text-slate-900" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
                                <option value="ja" class="text-slate-900" {{ app()->getLocale() == 'ja' ? 'selected' : '' }}>JA</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 transition-colors duration-300" :class="scrollAtTop ? 'text-white' : 'text-slate-600 dark:text-slate-300'">
                                <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" /></svg>
                            </div>
                        </div>
                    </form>

                    <div class="hidden sm:block h-6 w-px transition-colors duration-300" :class="scrollAtTop ? 'bg-white/40' : 'bg-slate-300 dark:bg-slate-600'"></div>

                    {{-- MENÚ USUARI O LOGIN --}}
                    @auth
                        {{-- SI ESTÀ LOGUEJAT: BOLA BLAVA --}}
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300 shadow-lg hover:scale-105 focus:outline-none bg-blue-600 border-blue-600 text-white">
                                <span class="font-bold text-lg leading-none pt-0.5">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            </button>
                            <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-3 w-56 rounded-xl shadow-2xl py-2 z-50 border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800" x-cloak
                                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 -translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 mb-2">
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Hola,') }}</p>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-blue-600 transition-colors">{{ __('El meu perfil') }}</a>
                                <div class="border-t border-slate-100 dark:border-slate-700 mt-2 pt-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">{{ __('Tancar sessió') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- SI ÉS CONVIDAT: LINKS DE TEXT --}}
                        <div class="flex items-center gap-4">
                            <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 dark:text-slate-300 hover:text-blue-600 transition-colors">{{ __('Inicia sessió') }}</a>
                            <a href="{{ route('register') }}" class="hidden sm:inline-block text-sm font-bold px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition shadow-md">{{ __("Registra't") }}</a>
                        </div>
                    @endauth

                </div>
            </div>
        </div>
    </header>

    {{-- BOTÓ TEMA --}}
    <div class="fixed bottom-6 right-6 z-[60] flex items-center justify-center group">
        <div class="absolute inset-0 -m-[2px] rounded-full blur-md opacity-60 animate-spin-slow transition-all duration-500"
            :style="darkMode ? 'background: conic-gradient(from 0deg, #ef4444, #f97316, #eab308, #ef4444);' : 'background: conic-gradient(from 0deg, #a855f7, #3b82f6, #06b6d4, #a855f7);'"></div>
        <button @click="toggleTheme()" class="relative z-10 p-4 rounded-full transition-all duration-300 transform hover:scale-110 border border-slate-200/20 dark:border-slate-700/50"
            :class="darkMode ? 'bg-slate-800 text-yellow-400' : 'bg-white text-slate-800'">
            <svg x-show="darkMode" class="h-8 w-8 animate-[spin_10s_linear_infinite]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            <svg x-show="!darkMode" class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
        </button>
    </div>

    {{-- CONTINGUT PRINCIPAL --}}
    <main class="pt-24 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- 🔙 ENLLAÇ DE TORNAR DINÀMIC --}}
            <a href="{{ $isFromSearch ? $previousUrl : route('home') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 mb-8 transition-colors group">
                <svg class="w-4 h-4 mr-1 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ $isFromSearch ? __('Tornar a la cerca') : __('Tornar al catàleg') }}
            </a>

            {{-- FITXA --}}
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl overflow-hidden border border-slate-200 dark:border-slate-700 mb-16">
                <div class="grid grid-cols-1 md:grid-cols-12">
                    <div class="md:col-span-5 bg-slate-100 dark:bg-slate-900/50 p-8 flex items-center justify-center">
                        <div class="relative group">
                            @if($llibre->img_portada)
                            <img src="{{ asset('img/' . $llibre->img_portada) }}" alt="{{ __($llibre->titol) }}" class="w-64 rounded-lg shadow-2xl transform group-hover:scale-105 transition duration-500">
                            @else
                            <div class="w-64 h-96 bg-slate-200 dark:bg-slate-700 rounded-lg flex items-center justify-center text-6xl shadow-inner">📘</div>
                            @endif
                            
                            {{-- ETIQUETA NOTA --}}
                            <div class="absolute -top-4 -right-4 bg-white dark:bg-slate-900 px-4 py-2 rounded-full shadow-lg border border-slate-100 dark:border-slate-700 flex items-center gap-1">
                                <span class="text-xl {{ $teRessenyes ? 'text-yellow-500' : 'text-slate-300' }}">★</span>
                                <span class="font-black text-slate-900 dark:text-white">
                                    {{ $teRessenyes ? number_format($mitjana, 1) : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-7 p-8 md:p-12 flex flex-col justify-center">
                        <div class="mb-6">
                            <span class="inline-block px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-bold uppercase tracking-wider mb-4">
                                {{ __($llibre->genere ?? 'General') }}
                            </span>
                            <h1 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white mb-2 leading-tight">{{ __($llibre->titol) }}</h1>
                            <p class="text-xl text-slate-500 dark:text-slate-400 font-serif italic">{{ __('per') }} {{ $llibre->autor ? $llibre->autor->nom : __('Autor Desconegut') }}</p>
                        </div>

                        <div class="prose dark:prose-invert text-slate-600 dark:text-slate-300 mb-8">
                            <p>{{ __($llibre->descripcio) }}</p>
                        </div>

                        <div class="mt-auto pt-8 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between">
                            <div>
                                <span class="text-sm text-slate-400 uppercase font-bold">{{ __('Preu') }}</span>
                                <p class="text-4xl font-black text-slate-900 dark:text-white">{{ number_format($llibre->preu, 2) }} €</p>
                            </div>

                            @auth
                            <form action="{{ route('cistella.afegir', $llibre->id_llibre) }}" method="POST">
                                @csrf
                                <button class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-1">
                                    {{ __('Afegir a la Cistella') }}
                                </button>
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="px-8 py-3 bg-slate-800 dark:bg-slate-700 text-white rounded-xl font-bold shadow-lg hover:bg-slate-700 transition">
                                {{ __('Login per comprar') }}
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            {{-- ZONA RESSENYES --}}
            <div class="max-w-4xl mx-auto">
                <h3 class="text-3xl font-bold text-slate-900 dark:text-white mb-8 flex items-center gap-3">
                    <span>💬</span> {{ __('Comunitat de lectors') }}
                </h3>

                @auth
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 mb-10">
                    <h4 class="font-bold text-lg mb-4 dark:text-white">{{ __('Publicar una ressenya') }}</h4>

                    <form action="{{ route('ressenyes.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="llibre_id" value="{{ $llibre->id_llibre }}">

                        <div class="mb-4" x-data="{ rating: 0, hover: 0 }">
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">{{ __('Puntuació') }}</label>
                            <div class="flex gap-2" @mouseleave="hover = 0">
                                @for($i=1; $i<=5; $i++)
                                    <label class="cursor-pointer">
                                    <input type="radio" name="puntuacio" value="{{ $i }}" class="hidden" @click="rating = {{ $i }}">
                                    <svg @mouseenter="hover = {{ $i }}"
                                        class="w-8 h-8 transition-colors duration-200"
                                        :class="(hover >= {{ $i }} || (hover === 0 && rating >= {{ $i }})) ? 'text-yellow-400' : 'text-slate-300'"
                                        fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                    </svg>
                                    </label>
                                @endfor
                            </div>
                        </div>

                        <textarea name="text" rows="3" class="w-full bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl p-4 focus:ring-2 focus:ring-blue-500 dark:text-white" placeholder="{{ __("Què t'ha semblat?") }}" required></textarea>

                        <div class="mt-4 text-right">
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">{{ __('Publicar') }}</button>
                        </div>
                    </form>
                </div>
                @else
                <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-2xl text-center mb-10 border border-blue-100 dark:border-blue-800">
                    <p class="text-blue-800 dark:text-blue-200 font-medium mb-3">{{ __('Vols participar?') }}</p>
                    <a href="{{ route('login') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-full font-bold hover:bg-blue-700 transition">{{ __('Inicia sessió') }}</a>
                </div>
                @endauth

                {{-- LLISTAT --}}
                <div class="space-y-8">
                    @foreach($llibre->ressenyes->whereNull('resposta_a_id') as $ressenya)
                    <div x-data="{ replyOpen: false }">

                        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 relative">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                        {{ substr($ressenya->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h5 class="font-bold text-slate-900 dark:text-white">{{ $ressenya->user->name }}</h5>
                                        <span class="text-xs text-slate-400">{{ $ressenya->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                @if($ressenya->puntuacio)
                                <div class="flex text-yellow-400 text-sm bg-slate-50 dark:bg-slate-900 px-2 py-1 rounded-lg">
                                    @for($i=0; $i<5; $i++) <span>{{ $i < $ressenya->puntuacio ? '★' : '☆' }}</span> @endfor
                                </div>
                                @endif
                            </div>
                            <p class="text-slate-600 dark:text-slate-300 leading-relaxed ml-13">{{ $ressenya->text }}</p>
                            <div class="mt-4 flex justify-end">
                                <button @click="replyOpen = !replyOpen" class="text-sm font-bold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                    </svg>
                                    {{ __('Contestar') }}
                                </button>
                            </div>
                        </div>

                        @if(method_exists($ressenya, 'respostes') && $ressenya->respostes->count() > 0)
                        <div class="ml-8 mt-2 space-y-2 border-l-2 border-slate-200 dark:border-slate-700 pl-4">
                            @foreach($ressenya->respostes as $resposta)
                            <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-100 dark:border-slate-700/50">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-400 flex items-center justify-center text-white text-xs">
                                        {{ substr($resposta->user->name, 0, 1) }}
                                    </div>
                                    <span class="font-bold text-sm text-slate-700 dark:text-slate-200">{{ $resposta->user->name }}</span>
                                    <span class="text-xs text-slate-400">• {{ $resposta->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-slate-600 dark:text-slate-400">{{ $resposta->text }}</p>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <div x-show="replyOpen" x-cloak class="ml-8 mt-3 animate-fade-in-up">
                            @auth
                            <form action="{{ route('ressenyes.store') }}" method="POST" class="bg-slate-100 dark:bg-slate-900 p-4 rounded-xl">
                                @csrf
                                <input type="hidden" name="resposta_a_id" value="{{ $ressenya->id }}">
                                <input type="hidden" name="llibre_id" value="{{ $llibre->id_llibre }}">

                                <div class="flex gap-2">
                                    <input type="text" name="text" class="w-full bg-white dark:bg-slate-800 border-none rounded-lg focus:ring-2 focus:ring-blue-500 text-sm dark:text-white" placeholder="{{ __('Escriu resposta...') }}" required>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700">{{ __('Enviar') }}</button>
                                </div>
                            </form>
                            @else
                            <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg text-xs text-yellow-800 dark:text-yellow-200">
                                <a href="{{ route('login') }}" class="underline font-bold">{{ __('Login') }}</a> {{ __('per contestar.') }}
                            </div>
                            @endauth
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>
</body>
</html>