<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('BookCores - La teva llibreria') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="antialiased bg-slate-50 text-slate-800">

    {{-- 
        =================================================================
        1. CARRUSEL DE FONS (FIX)
        Aquest bloc es queda quiet al fons de la pantalla.
        Només es veurà quan estiguem a dalt de tot.
        =================================================================
    --}}
    @php
        // Preparem les dades per a Alpine.js
        $llibresJS = $llibresRecents->map(function($llibre) {
            return [
                'id' => $llibre->id_llibre,
                'titol' => $llibre->titol,
                'img' => $llibre->img_portada ? asset('img/' . $llibre->img_portada) : null,
            ];
        })->values();
    @endphp

    <div class="fixed inset-0 w-full h-screen z-0 bg-slate-900 overflow-hidden"
         x-data="{
            books: {{ $llibresJS }},
            isAnimating: false,
            autoplay: null,

            startAutoplay() {
                if (this.books.length > 1) {
                    this.autoplay = setInterval(() => { this.slide(); }, 5000); 
                }
            },

            slide() {
                this.isAnimating = true;
            },

            handleTransitionEnd() {
                if (!this.isAnimating) return;
                this.isAnimating = false;
                const firstBook = this.books.shift();
                this.books.push(firstBook);
            },

            stopAutoplay() {
                if (this.autoplay) { clearInterval(this.autoplay); this.autoplay = null; }
            },
            
            init() { this.startAutoplay(); }
         }"
         x-init="init()">

        <!-- PISTA VISUAL -->
        <div class="flex h-full w-full will-change-transform"
             :class="isAnimating ? 'transition-transform duration-1000 ease-in-out -translate-x-full' : ''"
             @transitionend="handleTransitionEnd()">

            <!-- NODE 1 -->
            <div class="relative w-full h-full flex-shrink-0 flex items-center justify-center">
                <template x-if="books[0]">
                    <div class="w-full h-full relative">
                        <template x-if="books[0].img">
                            <div class="absolute inset-0">
                                <!-- Imatge de fons molt fosca i borrosa -->
                                <img :src="books[0].img" class="w-full h-full object-cover blur-sm opacity-40">
                                <div class="absolute inset-0 bg-black/60"></div> <!-- Capa fosca extra -->
                            </div>
                            <!-- Imatge principal centrada -->
                            <div class="relative z-10 w-full h-full flex items-center justify-center p-8 pt-24">
                                <img :src="books[0].img" :alt="books[0].titol" class="max-h-[60vh] max-w-full object-contain drop-shadow-2xl shadow-black/80 rounded-sm">
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <!-- NODE 2 -->
            <div class="relative w-full h-full flex-shrink-0 flex items-center justify-center">
                <template x-if="books[1]">
                    <div class="w-full h-full relative">
                        <template x-if="books[1].img">
                            <div class="absolute inset-0">
                                <img :src="books[1].img" class="w-full h-full object-cover blur-sm opacity-40">
                                <div class="absolute inset-0 bg-black/60"></div>
                            </div>
                            <div class="relative z-10 w-full h-full flex items-center justify-center p-8 pt-24">
                                <img :src="books[1].img" :alt="books[1].titol" class="max-h-[60vh] max-w-full object-contain drop-shadow-2xl shadow-black/80 rounded-sm">
                            </div>
                        </template>
                    </div>
                </template>
            </div>

        </div>
    </div>

    {{-- 
        =================================================================
        2. CONTINGUT FLOTANT (HEADER + HERO TEXT)
        Aquest contingut està per sobre del fons fix (z-10) però té fons transparent
        perquè es vegin les imatges.
        =================================================================
    --}}
    
    <!-- HEADER TRANSPARENT -->
    <header class="fixed w-full z-50 transition-all duration-300" 
            :class="window.scrollY > 50 ? 'bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-200' : 'bg-transparent text-white'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- LOGO -->
                <div class="flex-shrink-0 flex items-center gap-2">
                    <a href="/" class="font-serif text-2xl font-bold flex items-center gap-2" :class="window.scrollY > 50 ? 'text-slate-900' : 'text-white'">
                        <svg class="h-8 w-8 drop-shadow-sm" viewBox="0 0 24 24" fill="none" :stroke="window.scrollY > 50 ? '#3b82f6' : '#ffffff'" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        <span>Book<span :class="window.scrollY > 50 ? 'text-blue-900' : 'text-blue-200'">Cores</span></span>
                    </a>
                </div>

                <!-- MENÚ -->
                <div class="flex items-center space-x-6">
                    <!-- Idioma -->
                    <form action="{{ route('home') }}" method="GET" class="hidden sm:flex items-center">
                        <select name="lang" onchange="this.form.submit()" class="bg-transparent text-sm font-medium focus:ring-0 border-none cursor-pointer outline-none"
                                :class="window.scrollY > 50 ? 'text-slate-600 hover:text-blue-900' : 'text-white hover:text-blue-200'">
                            <option value="ca" class="text-slate-900" {{ app()->getLocale() == 'ca' ? 'selected' : '' }}>CA</option>
                            <option value="es" class="text-slate-900" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>ES</option>
                            <option value="en" class="text-slate-900" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
                            <option value="ja" class="text-slate-900" {{ app()->getLocale() == 'ja' ? 'selected' : '' }}>JA</option>
                        </select>
                    </form>

                    <div class="hidden sm:block h-6 w-px" :class="window.scrollY > 50 ? 'bg-slate-300' : 'bg-white/30'"></div>

                    <!-- Auth -->
                    <nav class="flex space-x-4 items-center">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-medium transition" :class="window.scrollY > 50 ? 'text-slate-600 hover:text-blue-900' : 'text-white hover:text-blue-200'">
                                    {{ __('Dashboard') }}
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium transition" :class="window.scrollY > 50 ? 'text-slate-600 hover:text-blue-900' : 'text-white hover:text-blue-200'">
                                    {{ __('Inicia sessió') }}
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="text-sm font-semibold px-4 py-2 rounded-full transition shadow-sm"
                                       :class="window.scrollY > 50 ? 'bg-blue-600 text-white hover:bg-blue-800' : 'bg-white text-slate-900 hover:bg-slate-100'">
                                        {{ __('Registra\'t') }}
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- HERO TEXT (A sobre del fons fix) -->
    <div class="relative z-10 w-full h-screen flex items-center justify-center pointer-events-none">
        <div class="text-center px-4 sm:px-6 lg:px-8 pointer-events-auto">
            <h1 class="text-5xl md:text-7xl tracking-tight font-extrabold text-white drop-shadow-lg mb-6">
                {{ __('Troba la teva pròxima') }} <br>
                <span class="text-blue-300">{{ __('història preferida') }}</span>
            </h1>
            <p class="mt-4 max-w-2xl mx-auto text-xl text-slate-200 drop-shadow-md">
                {{ __('Explora el nostre catàleg, llegeix ressenyes de la comunitat i troba les millors històries seleccionades per a tu a BookCores.') }}
            </p>
            <div class="mt-10 flex justify-center gap-4">
                <a href="#novetats" class="px-8 py-3 text-base font-medium rounded-full text-slate-900 bg-white hover:bg-slate-100 transition shadow-lg">
                    {{ __('Veure catàleg') }}
                </a>
                <a href="#" class="px-8 py-3 text-base font-medium rounded-full text-white bg-blue-600/80 hover:bg-blue-600 transition shadow-lg backdrop-blur-sm">
                    {{ __('Saber-ne més') }}
                </a>
            </div>
        </div>
    </div>

    {{-- 
        =================================================================
        3. CONTINGUT QUE PUJA (NOVETATS)
        Aquest bloc té fons sòlid (bg-slate-50) i un z-index alt (z-20)
        per tapar el fons fix quan puges.
        =================================================================
    --}}
    <main id="novetats" class="relative z-20 bg-slate-50 min-h-screen shadow-[0_-20px_60px_rgba(0,0,0,0.15)]">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-slate-900">{{ __('Els més ben valorats ⭐') }}</h2>
                    <p class="text-slate-500 mt-2">{{ __('Llibres amb les millors ressenyes de la comunitat') }}</p>
                </div>
                <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-800">{{ __('Veure tot') }} &rarr;</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse ($llibres as $llibre)
                    <div class="group relative bg-white border border-slate-200 rounded-2xl p-4 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                        <div class="aspect-w-2 aspect-h-3 w-full overflow-hidden rounded-xl bg-slate-100 mb-4 relative shadow-sm">
                            @if($llibre->img_portada)
                                <img src="{{ asset('img/' . $llibre->img_portada) }}" alt="{{ $llibre->titol }}" class="object-cover w-full h-64 group-hover:scale-105 transition duration-500 ease-in-out">
                            @else
                                <div class="flex items-center justify-center h-64 text-slate-400">
                                    <span class="text-4xl">📖</span>
                                </div>
                            @endif
                            
                            <span class="absolute top-2 right-2 bg-white/90 backdrop-blur text-slate-900 text-xs font-bold px-2 py-1 rounded-full shadow-sm flex items-center gap-1">
                                ⭐️ {{ number_format($llibre->nota_promig, 1) }}
                            </span>
                        </div>

                        <div class="flex flex-col flex-grow">
                            <h3 class="text-lg font-bold text-slate-900 line-clamp-2 leading-tight group-hover:text-blue-700 transition-colors">
                                <a href="#">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    {{ $llibre->titol }}
                                </a>
                            </h3>
                            <p class="text-sm text-slate-500 mt-1 font-medium">
                                {{ $llibre->autor ? $llibre->autor->nom : __('Autor Desconegut') }}
                            </p>
                            <div class="mt-auto pt-4 flex items-center justify-between">
                                <p class="text-xl font-extrabold text-slate-900">
                                    {{ number_format($llibre->preu, 2, ',', '.') }} €
                                </p>
                                <button class="relative z-10 p-2 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                        <p class="text-slate-500">{{ __('Sembla que la base de dades està buida.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <footer class="bg-white border-t border-slate-200 mt-12">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-2">
                    <span class="font-serif font-bold text-xl text-slate-900">BookCores</span>
                </div>
                <div class="flex gap-6 text-sm text-slate-500">
                    <a href="#" class="hover:text-blue-900 transition">{{ __('Sobre nosaltres') }}</a>
                    <a href="#" class="hover:text-blue-900 transition">{{ __('Contacte') }}</a>
                </div>
                <p class="text-sm text-slate-400">
                    &copy; {{ date('Y') }} BookCores. {{ __('Tots els drets reservats.') }}
                </p>
            </div>
        </footer>
    </main>

    <!-- Script per detectar scroll i canviar el color del header -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('scroll', { y: 0 });
            window.addEventListener('scroll', () => {
                Alpine.store('scroll').y = window.scrollY;
            });
        });
    </script>

</body>
</html>