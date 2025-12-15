<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('BookCores - La teva llibreria') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="antialiased bg-slate-50 text-slate-800">

    {{-- 
        =================================================================
        1. CARRUSEL DE FONS (SENSE POPPING - LÒGICA DE CLON)
        =================================================================
    --}}
    @php
        $llibresJS = $llibresRecents->map(function($llibre) {
            $imgSrc = null;
            if ($llibre->img_hero) {
                $imgSrc = asset('img/' . $llibre->img_hero);
            } elseif ($llibre->img_portada) {
                $imgSrc = asset('img/' . $llibre->img_portada);
            }
            return [
                'id' => $llibre->id_llibre,
                'titol' => $llibre->titol,
                'img' => $imgSrc, 
            ];
        })->values();
    @endphp

    <div class="fixed inset-0 w-full h-screen z-0 bg-slate-900 overflow-hidden"
         x-data="{
            books: {{ $llibresJS }},
            currentIndex: 0,
            totalSlides: {{ $llibresJS->count() }},
            isTransitioning: true,
            autoplay: null,

            init() {
                this.startAutoplay();
            },

            startAutoplay() {
                if (this.totalSlides > 1) {
                    this.autoplay = setInterval(() => {
                        this.next();
                    }, 5000); 
                }
            },

            stopAutoplay() {
                if (this.autoplay) {
                    clearInterval(this.autoplay);
                    this.autoplay = null;
                }
            },

            next() {
                // 1. Comprovem si estem 'encallats' al clon (per inactivitat de la pestanya)
                if (this.currentIndex >= this.totalSlides) {
                    // Reset d'emergència a 0 sense animació
                    this.isTransitioning = false;
                    this.currentIndex = 0;
                    
                    // Donem temps al navegador (50ms) i llavors movem a l'1 normalment
                    setTimeout(() => {
                        this.isTransitioning = true;
                        this.currentIndex = 1;
                    }, 50);
                } else {
                    // 2. Moviment normal
                    this.isTransitioning = true;
                    this.currentIndex++;
                }
            },

            handleTransitionEnd() {
                // Quan l'animació acaba, si hem arribat al clon (index == totalSlides)
                if (this.currentIndex >= this.totalSlides) {
                    // Desactivem animació
                    this.isTransitioning = false;
                    // Saltem instantàniament a l'inici real (0)
                    this.currentIndex = 0;
                    
                    // Reactivem l'animació després d'un momentet, llestos pel següent torn
                    setTimeout(() => {
                        this.isTransitioning = true;
                    }, 50);
                }
            }
         }"
         x-init="init()">

        <!-- PISTA VISUAL -->
        <div class="flex h-full w-full will-change-transform"
             :class="isTransitioning ? 'transition-transform duration-1000 ease-in-out' : 'duration-0'"
             :style="'transform: translateX(-' + (currentIndex * 100) + '%)'"
             @transitionend.self="handleTransitionEnd()">

            <!-- 1. BUCLE DELS LLIBRES REALS -->
            <template x-for="book in books" :key="book.id">
                <div class="relative min-w-full w-full h-full flex-shrink-0 flex items-center justify-center">
                    <div class="w-full h-full relative">
                        <template x-if="book.img">
                            <div class="contents">
                                <!-- FONS -->
                                <div class="absolute inset-0">
                                    <img :src="book.img" class="w-full h-full object-cover brightness-50">
                                    <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-transparent to-black/80"></div>
                                </div>
                            </div>
                        </template>
                        <!-- Fallback -->
                        <template x-if="!book.img">
                            <div class="w-full h-full flex items-center justify-center bg-slate-800 text-slate-600"><span class="text-6xl">📘</span></div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- 2. EL CLON (Còpia exacta del PRIMER llibre al final) -->
            <template x-if="books.length > 0">
                <div class="relative min-w-full w-full h-full flex-shrink-0 flex items-center justify-center">
                    <div class="w-full h-full relative">
                        <template x-if="books[0].img">
                            <div class="contents">
                                <div class="absolute inset-0">
                                    <img :src="books[0].img" class="w-full h-full object-cover brightness-50">
                                    <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-transparent to-black/80"></div>
                                </div>
                            </div>
                        </template>
                        <template x-if="!books[0].img">
                            <div class="w-full h-full flex items-center justify-center bg-slate-800 text-slate-600"><span class="text-6xl">📘</span></div>
                        </template>
                    </div>
                </div>
            </template>

        </div>
    </div>

    {{-- RESTA DE LA PÀGINA (HEADER, HERO TEXT, NOVETATS...) --}}
    
    <header class="fixed w-full z-50 transition-all duration-300" 
            :class="window.scrollY > 50 ? 'bg-white/95 backdrop-blur-md shadow-sm border-b border-gray-200 py-2' : 'bg-transparent py-4'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div class="flex-shrink-0 flex items-center gap-2">
                    <a href="/" class="font-serif text-2xl font-bold flex items-center gap-2 transition-colors" :class="window.scrollY > 50 ? 'text-slate-900' : 'text-white'">
                        <svg class="h-8 w-8 drop-shadow-md" viewBox="0 0 24 24" fill="none" :stroke="window.scrollY > 50 ? '#3b82f6' : '#ffffff'" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        <span class="drop-shadow-md">Book<span :class="window.scrollY > 50 ? 'text-blue-600' : 'text-blue-300'">Cores</span></span>
                    </a>
                </div>
                <div class="flex items-center space-x-6">
                    <form action="{{ route('home') }}" method="GET" class="hidden sm:flex items-center">
                        <select name="lang" onchange="this.form.submit()" class="bg-transparent text-sm font-bold focus:ring-0 border-none cursor-pointer outline-none transition-colors"
                                :class="window.scrollY > 50 ? 'text-slate-600 hover:text-blue-900' : 'text-white/90 hover:text-white'">
                            <option value="ca" class="text-slate-900" {{ app()->getLocale() == 'ca' ? 'selected' : '' }}>CA</option>
                            <option value="es" class="text-slate-900" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>ES</option>
                            <option value="en" class="text-slate-900" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
                            <option value="ja" class="text-slate-900" {{ app()->getLocale() == 'ja' ? 'selected' : '' }}>JA</option>
                        </select>
                    </form>
                    <div class="hidden sm:block h-6 w-px transition-colors" :class="window.scrollY > 50 ? 'bg-slate-300' : 'bg-white/40'"></div>
                    <nav class="flex space-x-4 items-center">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-bold transition-colors" :class="window.scrollY > 50 ? 'text-slate-600 hover:text-blue-900' : 'text-white hover:text-blue-200'">{{ __('Dashboard') }}</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-bold transition-colors" :class="window.scrollY > 50 ? 'text-slate-600 hover:text-blue-900' : 'text-white hover:text-blue-200'">{{ __('Inicia sessió') }}</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="text-sm font-bold px-5 py-2.5 rounded-full transition shadow-lg" :class="window.scrollY > 50 ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-white text-slate-900 hover:bg-blue-50'">{{ __('Registra\'t') }}</a>
                                @endif
                            @endauth
                        @endif
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <div class="relative z-10 w-full h-screen flex flex-col items-center justify-center pointer-events-none pb-20">
        <div class="text-center px-4 sm:px-6 lg:px-8 pointer-events-auto animate-fade-in-up">
            <h1 class="text-5xl md:text-8xl tracking-tight font-black text-white drop-shadow-2xl mb-8 leading-tight">
                {{ __('Troba la teva') }} <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 via-white to-indigo-200">{{ __('història preferida') }}</span>
            </h1>
            <p class="mt-4 max-w-2xl mx-auto text-xl md:text-2xl text-white/90 drop-shadow-lg font-medium">
                {{ __('Explora el nostre catàleg i descobreix mons nous.') }}
            </p>
            <div class="mt-12 flex flex-col sm:flex-row justify-center gap-4">
                <a href="#novetats" class="px-8 py-4 text-lg font-bold rounded-full text-slate-900 bg-white hover:bg-blue-50 transition transform hover:-translate-y-1 shadow-[0_10px_20px_rgba(0,0,0,0.3)]">
                    {{ __('Veure catàleg') }}
                </a>
            </div>
        </div>
        <div class="absolute bottom-10 animate-bounce">
            <svg class="w-8 h-8 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
        </div>
    </div>

    <main id="novetats" class="relative z-20 bg-slate-50 min-h-screen shadow-[0_-20px_60px_rgba(0,0,0,0.5)] rounded-t-[3rem]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="flex justify-between items-end mb-12 border-b border-slate-200 pb-4">
                <div>
                    <h2 class="text-4xl font-bold text-slate-900">{{ __('Els més ben valorats ⭐') }}</h2>
                    <p class="text-slate-500 mt-2 text-lg">{{ __('Llibres amb les millors ressenyes de la comunitat') }}</p>
                </div>
                <a href="#" class="text-base font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                    {{ __('Veure tot') }} 
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                @forelse ($llibres as $llibre)
                    <div class="group relative bg-white border border-slate-100 rounded-3xl p-5 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex flex-col h-full">
                        <a href="{{ route('llibres.show', $llibre->id_llibre) }}" class="block">
                            <div class="aspect-w-2 aspect-h-3 w-full overflow-hidden rounded-2xl bg-slate-100 mb-5 relative shadow-inner group-hover:shadow-md transition">
                                @if($llibre->img_portada)
                                    <img src="{{ asset('img/' . $llibre->img_portada) }}" alt="{{ $llibre->titol }}" class="object-cover w-full h-72 group-hover:scale-110 transition duration-700 ease-in-out">
                                @else
                                    <div class="flex items-center justify-center h-72 text-slate-400"><span class="text-5xl">📖</span></div>
                                @endif
                                <span class="absolute top-3 right-3 bg-white/95 backdrop-blur text-slate-900 text-sm font-bold px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1">
                                    <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    {{ number_format($llibre->nota_promig, 1) }}
                                </span>
                            </div>
                        </a>
                        <div class="flex flex-col flex-grow">
                            <h3 class="text-xl font-bold text-slate-900 line-clamp-2 leading-tight group-hover:text-blue-600 transition-colors">
                                <a href="{{ route('llibres.show', $llibre->id_llibre) }}">{{ $llibre->titol }}</a>
                            </h3>
                            <p class="text-base text-slate-500 mt-2 font-medium">{{ $llibre->autor ? $llibre->autor->nom : __('Autor Desconegut') }}</p>
                            <div class="mt-auto pt-6 flex items-center justify-between border-t border-slate-50">
                                <p class="text-2xl font-extrabold text-slate-900">{{ number_format($llibre->preu, 2, ',', '.') }} €</p>
                                <button class="relative z-10 p-3 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-24 text-center bg-white rounded-3xl border border-dashed border-slate-300">
                        <span class="text-6xl mb-4">📚</span>
                        <h3 class="text-xl font-bold text-slate-900">{{ __('Encara no hi ha llibres') }}</h3>
                        <p class="text-slate-500 max-w-sm mx-auto mt-2">{{ __('Sembla que la base de dades està buida.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <footer class="bg-slate-100 border-t border-slate-200 mt-0">
            <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white rounded-lg shadow-sm"><svg class="h-6 w-6 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg></div>
                    <span class="font-serif font-bold text-xl text-slate-900">BookCores</span>
                </div>
                <div class="flex gap-8 text-sm font-medium text-slate-600">
                    <a href="#" class="hover:text-blue-600 transition">{{ __('Sobre nosaltres') }}</a>
                    <a href="#" class="hover:text-blue-600 transition">{{ __('Contacte') }}</a>
                    <a href="#" class="hover:text-blue-600 transition">{{ __('Política de Privacitat') }}</a>
                </div>
                <p class="text-sm text-slate-400">&copy; {{ date('Y') }} BookCores. {{ __('Tots els drets reservats.') }}</p>
            </div>
        </footer>
    </main>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('scroll', { y: 0 });
            window.addEventListener('scroll', () => { Alpine.store('scroll').y = window.scrollY; });
        });
    </script>

    <style>
        .animate-fade-in-up { animation: fadeInUp 1s ease-out forwards; opacity: 0; transform: translateY(20px); }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
    </style>

</body>
</html>