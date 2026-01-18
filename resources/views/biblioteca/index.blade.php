<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('La meva biblioteca') }} - BookCores</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,900|georgia:400&display=swap" rel="stylesheet" />

    <!-- // codi per evitar flas al carregar la pàgina -->
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
                    animation: { 'spin-slow': 'spin 4s linear infinite' }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>[x-cloak] { display: none !important; } .hide-scroll::-webkit-scrollbar { display: none; } .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }</style>
</head>

<body class="antialiased bg-slate-100 text-slate-800 dark:bg-slate-900 dark:text-slate-100 transition-colors duration-500"
    x-data="{ 
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

    <!-- // header -->
    <header class="fixed w-full z-50 py-3 transition-colors duration-300 bg-white/70 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-700 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex justify-between items-center">

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
                    <a href="{{ route('home') }}" class="font-bold text-sm text-slate-600 dark:text-slate-300 hover:text-blue-600 transition-colors hidden sm:block">
                        {{ __('TORNAR AL CATÀLEG') }}
                    </a>

                    <!-- // idioma -->
                    <form action="{{ route('biblioteca') }}" method="GET" class="hidden sm:flex items-center">
                        <div class="relative group">
                            <select name="lang" onchange="this.form.submit()" class="appearance-none bg-transparent rounded-full py-1 pl-4 pr-8 text-sm font-bold cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 border border-slate-300 dark:border-slate-600 hover:border-blue-500 text-slate-600 dark:text-slate-300">
                                <option value="ca" class="text-slate-900" {{ app()->getLocale() == 'ca' ? 'selected' : '' }}>CA</option>
                                <option value="es" class="text-slate-900" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>ES</option>
                                <option value="en" class="text-slate-900" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
                                <option value="ja" class="text-slate-900" {{ app()->getLocale() == 'ja' ? 'selected' : '' }}>JA</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-600 dark:text-slate-300">
                                <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" /></svg>
                            </div>
                        </div>
                    </form>
                    
                    <!-- // bola usuari -->
                    @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white font-bold border-2 border-blue-600 hover:scale-105 transition shadow-lg">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </button>
                        <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-3 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-2xl py-2 z-50 border border-slate-100 dark:border-slate-700" x-cloak>
                            <div class="px-4 py-2 text-xs text-slate-400 border-b border-slate-100 dark:border-slate-700 mb-1">
                                {{ Auth::user()->name }}
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700">{{ __('El meu perfil') }}</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">{{ __('Tancar sessió') }}</button>
                            </form>
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- // boto tema -->
    <div class="fixed bottom-6 right-6 z-[60]">
        <button @click="toggleTheme()" class="p-4 rounded-full bg-slate-800 text-yellow-400 shadow-lg hover:scale-110 transition border border-slate-700 group">
            <svg x-show="darkMode" class="h-6 w-6 animate-[spin_10s_linear_infinite]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            <svg x-show="!darkMode" class="h-6 w-6 text-white group-hover:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
        </button>
    </div>


    <main class="pt-32 pb-20 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- // buscador -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
                <div class="flex items-center gap-3">
                    <span class="text-4xl">📚</span>
                    <h1 class="text-3xl font-black text-slate-900 dark:text-white">{{ __('La meva biblioteca') }}</h1>
                </div>

                <form action="{{ route('biblioteca') }}" method="GET" class="w-full md:w-auto relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400 group-focus-within:text-blue-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Busca els teus llibres...') }}" 
                           class="pl-10 pr-4 py-2.5 w-full md:w-64 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-slate-900 dark:text-white shadow-sm transition-all focus:w-full md:focus:w-80">
                </form>
            </div>

            <!-- // bucle segons gènere -->
            @if($llibresPerGenere->isEmpty())
                <div class="col-span-full py-20 text-center bg-white dark:bg-slate-800 rounded-3xl border border-dashed border-slate-300 dark:border-slate-700">
                    <div class="text-6xl mb-4 opacity-50 grayscale">📭</div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">
                        {{ request('q') ? __("No s'han trobat llibres amb aquesta cerca.") : __('La teva biblioteca està buida') }}
                    </h3>
                    
                    @if(request('q'))
                        <a href="{{ route('biblioteca') }}" class="text-blue-500 hover:underline font-bold">{{ __('Esborrar cerca') }}</a>
                    @else
                        <p class="text-slate-500 dark:text-slate-400 mb-6 max-w-sm mx-auto">{{ __('Sembla que encara no has comprat cap llibre. Explora el catàleg per començar la teva col·lecció!') }}</p>
                        <a href="{{ route('home') }}" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-full font-bold shadow-lg transition transform hover:scale-105">
                            {{ __('Explorar el catàleg') }}
                        </a>
                    @endif
                </div>
            @else
            
                @foreach ($llibresPerGenere as $genere => $llibres)
                <div class="mb-12" x-data="{ 
                    scrollLeft() { $refs.container.scrollBy({ left: -300, behavior: 'smooth' }); },
                    scrollRight() { $refs.container.scrollBy({ left: 300, behavior: 'smooth' }); }
                }">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2 ml-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                        {{ __($genere) }} <span class="text-sm bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 px-2 py-1 rounded-full">{{ $llibres->count() }}</span>
                    </h2>
                    
                    <div class="relative group/carousel">
                        <button @click="scrollLeft" class="absolute left-0 top-1/2 -translate-y-1/2 z-20 p-3 bg-black/50 hover:bg-black/80 text-white rounded-full opacity-0 group-hover/carousel:opacity-100 transition-opacity duration-300 -ml-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>

                        <div x-ref="container" class="flex gap-6 overflow-x-auto hide-scroll pb-8 snap-x snap-mandatory">
                            @foreach ($llibres as $llibre)
                            <div class="flex-shrink-0 w-56 snap-center">
                                <div class="group bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-lg border border-slate-100 dark:border-slate-700 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                                    {{-- PORTADA --}}
                                    <div class="aspect-[2/3] rounded-xl overflow-hidden mb-4 relative shadow-md bg-slate-200 dark:bg-slate-700 group-hover:shadow-lg transition">
                                        @if($llibre->img_portada)
                                        <img src="{{ asset('img/' . $llibre->img_portada) }}" alt="{{ __($llibre->titol) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                        @else
                                        <div class="w-full h-full flex items-center justify-center text-4xl">📘</div>
                                        @endif
                                        
                                        {{-- Overlay "Veure fitxa" --}}
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <a href="{{ route('llibres.show', $llibre->id_llibre) }}" class="px-4 py-1 bg-white/90 text-slate-900 rounded-full text-xs font-bold shadow-lg transform translate-y-2 group-hover:translate-y-0 transition">
                                                {{ __('Veure fitxa') }}
                                            </a>
                                        </div>
                                    </div>

                                    <div class="flex-grow">
                                        <h3 class="font-bold text-lg text-slate-900 dark:text-white line-clamp-1 mb-1" title="{{ __($llibre->titol) }}">{{ __($llibre->titol) }}</h3>
                                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">{{ $llibre->autor ? $llibre->autor->nom : __('Autor Desconegut') }}</p>
                                    </div>

                                    <!-- // llegir llibre -->
                                    <a href="{{ route('llibre.llegir', $llibre->id_llibre) }}" target="_blank" class="block w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-center font-bold rounded-xl transition shadow-md shadow-blue-500/20 flex items-center justify-center gap-2 group-hover:shadow-blue-500/40">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                        {{ __('Llegir ara') }}
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <button @click="scrollRight" class="absolute right-0 top-1/2 -translate-y-1/2 z-20 p-3 bg-black/50 hover:bg-black/80 text-white rounded-full opacity-0 group-hover/carousel:opacity-100 transition-opacity duration-300 -mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </div>
                @endforeach

            @endif

        </div>
    </main>

    
    <footer class="bg-slate-200 dark:bg-slate-800 py-8 mt-auto border-t border-slate-300 dark:border-slate-700">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-slate-600 dark:text-slate-400 text-sm font-medium">© {{ date('Y') }} BookCores. {{ __('La teva llibreria digital.') }}</p>
        </div>
    </footer>

</body>
</html>