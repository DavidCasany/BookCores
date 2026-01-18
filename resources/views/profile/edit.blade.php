<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('El meu perfil') }} - BookCores</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,900|georgia:400&display=swap" rel="stylesheet" />

    <!-- // evitar flash al carregar -->
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
                    fontFamily: { sans: ['Figtree', 'sans-serif'], serif: ['Georgia', 'serif'] },
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        [x-cloak] { display: none !important; }
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="antialiased bg-slate-100 text-slate-800 dark:bg-slate-900 dark:text-slate-100 transition-colors duration-500"
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

    <!-- // header -->
    <header class="fixed w-full z-50 py-3 transition-colors duration-300">
        <div class="absolute inset-0 bg-white/70 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-700 shadow-md -z-10 transition-colors duration-300"></div>

        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex justify-between items-center">
                <!-- // logo -->
                <div class="flex-shrink-0 flex items-center gap-2">
                    <a href="{{ route('home') }}" class="font-serif text-2xl font-bold flex items-center gap-2 transition-colors">
                        <svg class="h-8 w-8 text-blue-600 dark:text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        <span class="text-slate-900 dark:text-white">Book</span><span class="text-blue-600 dark:text-blue-400">Cores</span>
                    </a>
                </div>

                <div class="flex items-center space-x-6">
                    <!-- // biblio -->
                    <a href="{{ route('biblioteca') }}"
                        class="mr-4 font-bold text-sm text-slate-600 dark:text-slate-300 hover:text-blue-600 transition-colors border-b-2 border-transparent hover:border-blue-500">
                        {{ __('BIBLIOTECA') }}
                    </a>
                    
                    <!-- // lupa -->
                    <a href="{{ route('cerca.index') }}" class="p-2 transition transform hover:scale-110 text-slate-600 dark:text-slate-300 hover:text-blue-600"
                        title="{{ __('Cerca') }}">
                        <svg class="w-6 h-6 drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </a>

                    <!-- // icona cistella -->
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
                        class="relative p-2 transition transform hover:scale-110 mr-2 text-slate-600 dark:text-slate-300 hover:text-blue-600"
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

                    <!-- // idioma -->
                    <form action="{{ route('profile.edit') }}" method="GET" class="hidden sm:flex items-center">
                        <div class="relative group">
                            <select name="lang" onchange="this.form.submit()" class="appearance-none bg-transparent rounded-full py-1 pl-4 pr-8 text-sm font-bold cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 border border-slate-300 dark:border-slate-600 hover:border-blue-500 text-slate-600 dark:text-slate-300">
                                <option value="ca" class="text-slate-900" {{ app()->getLocale() == 'ca' ? 'selected' : '' }}>CA</option>
                                <option value="es" class="text-slate-900" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>ES</option>
                                <option value="en" class="text-slate-900" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
                                <option value="ja" class="text-slate-900" {{ app()->getLocale() == 'ja' ? 'selected' : '' }}>JA</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-600 dark:text-slate-300">
                                <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                </svg>
                            </div>
                        </div>
                    </form>

                    <div class="hidden sm:block h-6 w-px bg-slate-300 dark:bg-slate-600"></div>

                    <!-- // bola usuari -->
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
                            <span class="block px-4 py-2 text-sm text-blue-600 dark:text-blue-400 font-bold bg-slate-50 dark:bg-slate-700/50">{{ __('El meu perfil') }}</span>
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

    <main class="pt-32 pb-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="relative bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl shadow-xl overflow-hidden mb-10 p-8 md:p-12 flex flex-col md:flex-row items-center gap-8 text-white">
            <div class="w-32 h-32 rounded-full bg-white/20 backdrop-blur-md border-4 border-white/30 flex items-center justify-center shadow-2xl">
                <span class="text-6xl font-black">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
            </div>
            
            <div class="text-center md:text-left">
                <h2 class="text-4xl font-black mb-2 tracking-tight">{{ $user->name }}</h2>
                <p class="text-blue-100 text-lg font-medium">{{ $user->email }}</p>
                <div class="mt-4 flex flex-wrap gap-3 justify-center md:justify-start">
                    <span class="px-4 py-1 bg-white/20 rounded-full text-sm font-bold backdrop-blur-sm border border-white/10">
                        📚 {{ __('Lector') }}
                    </span>
                    <span class="px-4 py-1 bg-white/20 rounded-full text-sm font-bold backdrop-blur-sm border border-white/10">
                        📅 {{ __('Membre des de') }} {{ $user->created_at->format('Y') }}
                    </span>
                </div>
            </div>

            <!-- // fons -->
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-blue-400/20 rounded-full blur-2xl"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- // info i contrassenya, columna esq -->
            <div class="space-y-8">
                <!-- // info -->
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-lg border border-slate-200 dark:border-slate-700">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <!-- // contra -->
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-lg border border-slate-200 dark:border-slate-700">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- // columna dreta -->
            <div class="space-y-8">
                <div class="bg-blue-50 dark:bg-slate-800/50 p-8 rounded-3xl border border-blue-100 dark:border-slate-700 text-center">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">🌟 {{ __('El teu estat') }}</h3>
                    <p class="text-slate-600 dark:text-slate-400 mb-6">
                        {{ __('Tens tot al dia! Continua explorant el catàleg per trobar la teva propera lectura.') }}
                    </p>
                    <a href="{{ route('home') }}" class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg transition transform hover:-translate-y-1">
                        {{ __('Explorar Llibres') }}
                    </a>
                </div>

                <!-- // borrar compte -->
                <div class="bg-red-50 dark:bg-red-900/10 p-8 rounded-3xl border border-red-100 dark:border-red-900/30">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </main>

    
    <footer class="bg-slate-200 dark:bg-slate-800 py-8 mt-auto border-t border-slate-300 dark:border-slate-700">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-slate-600 dark:text-slate-400 text-sm font-medium">© {{ date('Y') }} BookCores. {{ __('La teva llibreria digital.') }}</p>
        </div>
    </footer>

    <!-- // botó temma -->
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