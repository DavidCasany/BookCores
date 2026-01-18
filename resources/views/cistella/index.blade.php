<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('La teva cistella') }} - BookCores</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,900|georgia:400&display=swap" rel="stylesheet" />

    // codi per evitar flash al carregar pag
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
    <style>[x-cloak] { display: none !important; }</style>
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

    // header
    <header class="fixed w-full z-50 py-4 transition-colors duration-300 bg-white/70 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            // tornar clicant el logo
            <a href="{{ route('home') }}" class="flex items-center gap-2 group text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition">
                <div class="p-2 rounded-full bg-slate-100 dark:bg-slate-800 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span class="font-bold text-sm tracking-wide">{{ __('Tornar al catàleg') }}</span>
            </a>

            <div class="flex items-center gap-4">
                // idioma
                <form action="{{ route('cistella.index') }}" method="GET" class="hidden sm:flex items-center">
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

                <div class="h-6 w-px bg-slate-300 dark:bg-slate-600 hidden sm:block"></div>

                // títol
                <div class="flex items-center gap-2">
                    <span class="text-2xl">🛒</span>
                    <span class="font-black text-xl tracking-tight">{{ __('Cistella') }}</span>
                </div>
            </div>
        </div>
    </header>

    <main class="pt-32 pb-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 min-h-screen">

        @if(!$cistella || $cistella->llibres->isEmpty())
            // cistella buida
            <div class="flex flex-col items-center justify-center py-20 text-center animate-fade-in-up">
                <div class="w-48 h-48 bg-blue-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-8 shadow-inner border border-blue-100 dark:border-slate-700">
                    <svg class="w-24 h-24 text-blue-200 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <h2 class="text-3xl font-black text-slate-900 dark:text-white mb-4">{{ __('La teva cistella està buida') }}</h2>
                <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-8 text-lg">
                    {{ __('Sembla que encara no has afegit cap llibre. Explora el catàleg per començar la teva col·lecció!') }}
                </p>
                <a href="{{ route('home') }}" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-1">
                    {{ __('Explorar el catàleg') }}
                </a>
            </div>
        @else
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
                
                // llista de productes
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-bold text-slate-500 dark:text-slate-400 uppercase text-xs tracking-wider">{{ $cistella->llibres->count() }} {{ __('Articles') }}</h3>
                    </div>

                    @foreach($cistella->llibres as $llibre)
                    <div class="group relative bg-white dark:bg-slate-800 p-5 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row gap-6">
                        
                        // img llibre
                        <a href="{{ route('llibres.show', $llibre->id_llibre) }}" class="shrink-0 w-full sm:w-28 aspect-[2/3] rounded-xl overflow-hidden shadow-md relative bg-slate-200 dark:bg-slate-700">
                            <img src="{{ $llibre->img_portada ? asset('img/' . $llibre->img_portada) : 'https://placehold.co/400x600' }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        </a>

                        // info compra
                        <div class="flex-grow flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start">
                                    <h3 class="font-bold text-xl text-slate-900 dark:text-white leading-tight mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                                        <a href="{{ route('llibres.show', $llibre->id_llibre) }}">{{ __($llibre->titol) }}</a>
                                    </h3>
                                    
                                    // boto paperera/eliminar
                                    <form action="{{ route('cistella.eliminar', $llibre->id_llibre) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-slate-300 hover:text-red-500 transition p-2 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full" title="{{ __('Eliminar') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                                <p class="text-slate-500 dark:text-slate-400 font-medium">{{ $llibre->autor ? $llibre->autor->nom : __('Autor Desconegut') }}</p>
                                <p class="text-xs text-slate-400 mt-1 uppercase">{{ __($llibre->genere) }}</p>
                            </div>

                            // preu i quantitat
                            <div class="flex items-end justify-between mt-4 sm:mt-0">
                                <div class="flex items-center bg-slate-100 dark:bg-slate-900 rounded-xl p-1 shadow-inner border border-slate-200 dark:border-slate-700">
                                    <form action="{{ route('cistella.actualitzar', $llibre->id_llibre) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="quantitat" value="{{ $llibre->pivot->quantitat - 1 }}">
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center text-slate-500 hover:text-blue-600 hover:bg-white dark:hover:bg-slate-700 rounded-lg transition" {{ $llibre->pivot->quantitat <= 1 ? 'disabled opacity-50' : '' }}>-</button>
                                    </form>
                                    
                                    <span class="w-10 text-center font-bold text-slate-900 dark:text-white select-none">{{ $llibre->pivot->quantitat }}</span>
                                    
                                    <form action="{{ route('cistella.actualitzar', $llibre->id_llibre) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="quantitat" value="{{ $llibre->pivot->quantitat + 1 }}">
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center text-slate-500 hover:text-blue-600 hover:bg-white dark:hover:bg-slate-700 rounded-lg transition">+</button>
                                    </form>
                                </div>
                                
                                <div class="text-right">
                                    <span class="block text-xs text-slate-400">{{ __('Unitat:') }} {{ number_format($llibre->pivot->preu_unitari, 2) }}€</span>
                                    <span class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($llibre->pivot->preu_unitari * $llibre->pivot->quantitat, 2) }} €</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                // resum de la compra
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-xl border border-slate-100 dark:border-slate-700 sticky top-28">
                        <h3 class="text-xl font-black text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                            <span>🧾</span> {{ __('Resum de la comanda') }}
                        </h3>
                        
                        <div class="flex justify-between items-end mb-8">
                            <span class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Total a pagar') }}</span>
                            <span class="text-4xl font-black text-blue-600 dark:text-blue-400">{{ number_format($cistella->total, 2) }} €</span>
                        </div>

                        // botó pagament
                        <form action="{{ route('pagament.checkout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-lg rounded-2xl shadow-lg shadow-blue-500/40 transition transform hover:-translate-y-1 flex items-center justify-center gap-3">
                                <span>🔒 {{ __('Tramitar comanda') }}</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </main>

    // botó tema
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