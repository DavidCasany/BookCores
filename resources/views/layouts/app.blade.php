<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BookCores') }} - {{ __('El meu Perfil') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

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
        <style> [x-cloak] { display: none !important; } </style>

        {{-- ⚡ SCRIPT CRÍTIC ANTIFLAIX --}}
        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body class="font-sans antialiased bg-slate-100 text-slate-800 dark:bg-slate-900 dark:text-slate-100 transition-colors duration-500"
        x-data="{ 
            scrollAtTop: true,
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
            },
            handleScroll() {
                this.scrollAtTop = (window.scrollY < 50);
            }
        }"
        @scroll.window="handleScroll()">
        
        {{-- 
            =============================================
            HEADER (Còpia exacta del Home-Auth)
            =============================================
        --}}
        <header class="fixed w-full z-50 py-3 transition-colors duration-300">
            <div class="absolute inset-0 bg-white/20 backdrop-blur-md border-b border-white/20 shadow-sm -z-10 transition-colors duration-300"
                :class="scrollAtTop ? 'bg-slate-900/10 border-slate-900/10 dark:bg-white/10 dark:border-white/10' : 'bg-white/70 dark:bg-slate-900/80 border-slate-200 dark:border-slate-700 shadow-md'"></div>
            
            <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="flex justify-between items-center">
                    {{-- LOGO --}}
                    <div class="flex-shrink-0 flex items-center gap-2">
                        <a href="{{ route('home') }}" class="font-serif text-2xl font-bold flex items-center gap-2 transition-colors">
                            <svg class="h-8 w-8 drop-shadow-md transition-colors duration-300" viewBox="0 0 24 24" fill="none"
                                :stroke="scrollAtTop ? '#2563eb' : (darkMode ? '#60a5fa' : '#2563eb')" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                            <span class="font-bold transition-colors duration-300" :class="scrollAtTop ? 'text-slate-900 dark:text-white' : 'text-slate-900 dark:text-white'">Book</span>
                            <span class="drop-shadow-md font-bold transition-colors duration-300" :class="scrollAtTop ? 'text-blue-600 dark:text-blue-400' : 'text-blue-600 dark:text-blue-400'">Cores</span>
                        </a>
                    </div>
    
                    <div class="flex items-center space-x-6">
                        {{-- ENLLAÇ BIBLIOTECA --}}
                        <a href="{{ route('biblioteca') }}" 
                           class="mr-4 font-bold text-sm transition-colors border-b-2 border-transparent hover:border-blue-500"
                           :class="scrollAtTop ? 'text-slate-600 dark:text-slate-300 hover:text-blue-600' : 'text-slate-600 dark:text-slate-300 hover:text-blue-600'">
                            {{ __('BIBLIOTECA') }}
                        </a>

                        {{-- LUPA --}}
                        <a href="{{ route('cerca.index') }}" class="p-2 transition transform hover:scale-110"
                           :class="scrollAtTop ? 'text-slate-600 dark:text-slate-300 hover:text-blue-600' : 'text-slate-600 dark:text-slate-300 hover:text-blue-600'"
                           title="{{ __('Cerca') }}">
                            <svg class="w-6 h-6 drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </a>
    
                        {{-- 🛒 CISTELLA (LÒGICA INTEGRADA) --}}
                        @php
                            $totalItems = 0;
                            if(auth()->check()){
                                $cistella = \App\Models\Compra::where('user_id', auth()->id())
                                    ->where('estat', 'en_proces') 
                                    ->latest()
                                    ->first();
                                
                                if($cistella) {
                                    $totalItems = $cistella->llibres->sum('pivot.quantitat');
                                }
                            }
                        @endphp
    
                        <a href="{{ route('cistella.index') }}" 
                           class="relative p-2 transition transform hover:scale-110 mr-2"
                           :class="scrollAtTop ? 'text-slate-600 dark:text-slate-300 hover:text-blue-600' : 'text-slate-600 dark:text-slate-300 hover:text-blue-600'"
                           title="{{ __('La teva cistella') }}">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
    
                            @if($totalItems > 0)
                                <span class="absolute top-0 right-0 -mt-1 -mr-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full shadow-sm">
                                    {{ $totalItems }}
                                </span>
                            @endif
                        </a>
    
                        {{-- IDIOMA --}}
                    <form action="{{ url()->current() }}" method="GET" class="hidden sm:flex items-center">
                        
                        {{-- Mantenim els paràmetres actuals (com cerques o filtres) al canviar idioma --}}
                        @foreach(request()->except('lang') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach

                        <div class="relative group">
                            <select name="lang" onchange="this.form.submit()" class="appearance-none bg-transparent rounded-full py-1 pl-4 pr-8 text-sm font-bold cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 border"
                                :class="scrollAtTop ? 'text-slate-900 dark:text-white border-slate-300 dark:border-slate-600 hover:border-blue-500' : 'text-slate-900 dark:text-white border-slate-300 dark:border-slate-600 hover:border-blue-500'">
                                <option value="ca" class="text-slate-900" {{ app()->getLocale() == 'ca' ? 'selected' : '' }}>CA</option>
                                <option value="es" class="text-slate-900" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>ES</option>
                                <option value="en" class="text-slate-900" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
                                <option value="ja" class="text-slate-900" {{ app()->getLocale() == 'ja' ? 'selected' : '' }}>JA</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 transition-colors duration-300" :class="scrollAtTop ? 'text-slate-600 dark:text-slate-300' : 'text-slate-600 dark:text-slate-300'">
                                <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" /></svg>
                            </div>
                        </div>
                    </form>
    
                        <div class="hidden sm:block h-6 w-px transition-colors duration-300" :class="scrollAtTop ? 'bg-slate-300 dark:bg-slate-600' : 'bg-slate-300 dark:bg-slate-600'"></div>
    
                        {{-- MENÚ USUARI --}}
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300 shadow-lg hover:scale-105 focus:outline-none"
                                :class="scrollAtTop ? 'bg-blue-600 border-blue-600 text-white' : 'bg-blue-600 border-blue-600 text-white'">
                                <span class="font-bold text-lg leading-none pt-0.5">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            </button>
                            <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-3 w-56 rounded-xl shadow-2xl py-2 z-50 border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800" x-cloak
                                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 -translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 mb-2">
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Hola,') }}</p>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                                </div>
                                <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-blue-600 transition-colors">{{ __('Anar a l\'Inici') }}</a>
                                <div class="border-t border-slate-100 dark:border-slate-700 mt-2 pt-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">{{ __('Tancar sessió') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="pt-24 pb-12">
            {{ $slot }}
        </main>

        {{-- BOTÓ TEMA FLOTANT --}}
        <div class="fixed bottom-6 right-6 z-[60] flex items-center justify-center group">
            <div class="absolute inset-0 -m-[2px] rounded-full blur-md opacity-60 animate-spin-slow transition-all duration-500"
                :style="darkMode ? 'background: conic-gradient(from 0deg, #ef4444, #f97316, #eab308, #ef4444);' : 'background: conic-gradient(from 0deg, #a855f7, #3b82f6, #06b6d4, #a855f7);'"></div>
            <button @click="toggleTheme()" class="relative z-10 p-4 rounded-full transition-all duration-300 transform hover:scale-110 border border-slate-200/20 dark:border-slate-700/50"
                :class="darkMode ? 'bg-slate-800 text-yellow-400' : 'bg-white text-slate-800'">
                <svg x-show="darkMode" class="h-8 w-8 animate-[spin_10s_linear_infinite]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                <svg x-show="!darkMode" class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
            </button>
        </div>
    </body>
</html>