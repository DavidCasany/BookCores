<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('El teu espai - BookCores') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,900|georgia:400&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Figtree', 'sans-serif'],
                        serif: ['Georgia', 'serif'],
                    },
                    animation: {
                        'spin-slow': 'spin 4s linear infinite',
                    }
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

<body class="antialiased bg-slate-300 text-slate-800 dark:bg-slate-900 dark:text-slate-100 transition-colors duration-500"
    x-data="{ 
          scrollAtTop: true,
          darkMode: true,
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
          },
          init() {
              if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                  this.darkMode = true;
                  document.documentElement.classList.add('dark');
              } else {
                  this.darkMode = false;
                  document.documentElement.classList.remove('dark');
              }
          }
      }"
    @scroll.window="handleScroll()">

    {{-- Dades PHP per al Slider --}}
    @php
    $llibresCollection = $llibresRecents->map(function($llibre) {
        return [
            'id' => $llibre->id_llibre, // Passem l'ID correcte a JS
            'titol' => $llibre->titol,
            'autor' => $llibre->autor ? $llibre->autor->nom : __('Autor Desconegut'),
            'img' => $llibre->img_hero ? asset('img/' . $llibre->img_hero) : ($llibre->img_portada ? asset('img/' . $llibre->img_portada) : null),
        ];
    })->values();
    @endphp

    <script>
        window.sliderData = <?php echo json_encode($llibresCollection); ?>;
    </script>

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

    {{-- HEADER --}}
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
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        <span class="font-bold transition-colors duration-300" :class="scrollAtTop ? 'text-white' : 'text-slate-900 dark:text-white'">Book</span>
                        <span class="drop-shadow-md font-bold transition-colors duration-300" :class="scrollAtTop ? 'text-blue-500' : 'text-blue-600 dark:text-blue-400'">Cores</span>
                    </a>
                </div>

                <div class="flex items-center space-x-6">
                    {{-- LUPA --}}
                    <a href="{{ route('cerca.index') }}" class="p-2 transition transform hover:scale-110"
                       :class="scrollAtTop ? 'text-white hover:text-blue-200' : 'text-slate-600 dark:text-slate-300 hover:text-blue-600'"
                       title="{{ __('Cerca') }}">
                        <svg class="w-6 h-6 drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </a>

                    {{-- 🛒 CISTELLA DE LA COMPRA (Afegida aquí) --}}
                    @php
                        $totalItems = 0;
                        if(auth()->check()){
                            $cistella = \App\Models\Compra::where('user_id', auth()->id())->latest()->first();
                            if($cistella) {
                                $totalItems = $cistella->llibres->sum('pivot.quantitat');
                            }
                        }
                    @endphp

                    <a href="{{ route('cistella.index') }}"
                       class="relative p-2 transition transform hover:scale-110 mr-2"
                       :class="scrollAtTop ? 'text-white hover:text-blue-200' : 'text-slate-600 dark:text-slate-300 hover:text-blue-600'"
                       title="La teva cistella">
                        
                        {{-- Icona del carretó --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>

                        {{-- Comptador vermell (només si hi ha items) --}}
                        @if($totalItems > 0)
                            <span class="absolute top-0 right-0 -mt-1 -mr-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full shadow-sm">
                                {{ $totalItems }}
                            </span>
                        @endif
                    </a>

                    {{-- IDIOMA --}}
                    <form action="{{ route('home') }}" method="GET" class="hidden sm:flex items-center">
                        <div class="relative group">
                            <select name="lang" onchange="this.form.submit()" class="appearance-none bg-transparent rounded-full py-1 pl-4 pr-8 text-sm font-bold cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 border"
                                :class="scrollAtTop ? 'text-white border-white/50 hover:bg-white/10' : 'text-slate-900 dark:text-white border-slate-300 dark:border-slate-600 hover:border-blue-500'">
                                <option value="ca" class="text-slate-900" {{ app()->getLocale() == 'ca' ? 'selected' : '' }}>CA</option>
                                <option value="es" class="text-slate-900" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>ES</option>
                                <option value="en" class="text-slate-900" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 transition-colors duration-300" :class="scrollAtTop ? 'text-white' : 'text-slate-600 dark:text-slate-300'">
                                <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" /></svg>
                            </div>
                        </div>
                    </form>

                    <div class="hidden sm:block h-6 w-px transition-colors duration-300" :class="scrollAtTop ? 'bg-white/40' : 'bg-slate-300 dark:bg-slate-600'"></div>

                    {{-- MENÚ USUARI --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300 shadow-lg hover:scale-105 focus:outline-none"
                            :class="scrollAtTop ? 'bg-white/20 border-white/30 text-white backdrop-blur-sm' : 'bg-blue-600 border-blue-600 text-white'">
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
                </div>
            </div>
        </div>
    </header>

    {{-- CARRUSEL HERO (60vh - Clicable) --}}
    <div class="relative w-full h-[60vh] z-0 bg-slate-900 overflow-hidden" x-data="heroSlider()">
        <div class="flex h-full w-full will-change-transform"
            :class="isAnimating ? 'transition-transform duration-1000 ease-in-out -translate-x-full' : ''"
            @transitionend="handleTransitionEnd()">
            
            <template x-for="(book, index) in [books[0], books[1]]" :key="index">
                <div class="relative min-w-full w-full h-full flex-shrink-0">
                    <template x-if="book">
                        <a :href="'/llibre/' + book.id" class="block w-full h-full relative cursor-pointer group">
                            {{-- Imatge --}}
                            <div class="absolute inset-0">
                                <template x-if="book.img">
                                    <img :src="book.img" class="w-full h-full object-cover brightness-50 group-hover:brightness-75 transition duration-500">
                                </template>
                                <template x-if="!book.img">
                                    <div class="w-full h-full bg-slate-800 flex items-center justify-center text-slate-600"><span class="text-6xl">📘</span></div>
                                </template>
                                <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-transparent to-black/80"></div>
                            </div>

                            {{-- Text --}}
                            <div class="absolute bottom-0 left-0 w-full z-20 pb-16 pl-4 md:pl-12 lg:pl-16 flex items-end">
                                <div class="max-w-3xl p-4">
                                    <h1 class="text-4xl md:text-7xl font-black text-white drop-shadow-2xl leading-none mb-2" x-text="book.titol"></h1>
                                    <p class="text-xl md:text-3xl text-blue-200 font-serif italic drop-shadow-lg" x-text="book.autor"></p>
                                </div>
                            </div>
                        </a>
                    </template>
                </div>
            </template>
        </div>
    </div>

    {{-- CONTINGUT (Llistes Horitzontals, Tot clicable) --}}
    <main id="novetats" class="relative z-30 -mt-12 bg-slate-50/90 dark:bg-slate-800/90 backdrop-blur-md min-h-screen rounded-t-[3rem] transition-all duration-700 ease-out">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-16">
            
            {{-- SECCIÓ TENDÈNCIES --}}
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2 ml-2">
                    {{ __('Tendències ara mateix') }} <span class="text-yellow-500">🔥</span>
                </h2>
                <div class="flex gap-6 overflow-x-auto hide-scroll pb-8 snap-x snap-mandatory">
                    @forelse ($llibres as $llibre)
                    <div class="flex-shrink-0 w-64 snap-center">
                        <a href="{{ route('llibres.show', $llibre->id_llibre) }}" class="group relative bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-4 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex flex-col h-full cursor-pointer decoration-0">
                            
                            <div class="aspect-[2/3] w-full overflow-hidden rounded-xl bg-slate-100 dark:bg-slate-700 relative shadow-md group-hover:shadow-lg transition mb-4">
                                @if($llibre->img_portada)
                                <img src="{{ asset('img/' . $llibre->img_portada) }}" alt="{{ $llibre->titol }}" class="object-cover w-full h-full group-hover:scale-110 transition duration-700 ease-in-out">
                                @else
                                <div class="flex items-center justify-center h-full text-slate-400"><span class="text-4xl">📖</span></div>
                                @endif
                                <span class="absolute top-2 right-2 bg-white/95 dark:bg-slate-900/95 backdrop-blur text-slate-900 dark:text-white text-xs font-bold px-2 py-1 rounded-full shadow-md flex items-center gap-1">
                                    <svg class="w-3 h-3 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    {{ number_format($llibre->nota_promig, 1) }}
                                </span>
                            </div>

                            <div class="flex flex-col flex-grow">
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white line-clamp-2 leading-tight group-hover:text-blue-600 transition-colors">
                                    {{ $llibre->titol }}
                                </h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 font-medium">{{ $llibre->autor ? $llibre->autor->nom : __('Autor Desconegut') }}</p>
                                <div class="mt-auto pt-4 flex items-center justify-between border-t border-slate-50 dark:border-slate-700">
                                    <p class="text-lg font-extrabold text-slate-900 dark:text-white">{{ number_format($llibre->preu, 2, ',', '.') }} €</p>
                                    <div class="p-2 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @empty
                    <div class="w-full text-center py-10 text-slate-500">{{ __('No hi ha llibres disponibles.') }}</div>
                    @endforelse
                </div>
            </div>

            {{-- SECCIÓ RECOMANATS --}}
            <div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2 ml-2">
                    {{ __('Recomanats per a tu') }} <span>✨</span>
                </h2>
                <div class="flex gap-6 overflow-x-auto hide-scroll pb-8 snap-x snap-mandatory">
                    @foreach($llibres->reverse() as $llibre)
                    <div class="flex-shrink-0 w-64 snap-center">
                        <a href="{{ route('llibres.show', $llibre->id_llibre) }}" class="group relative bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-4 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex flex-col h-full cursor-pointer decoration-0">
                            <div class="aspect-[2/3] w-full overflow-hidden rounded-xl bg-slate-100 dark:bg-slate-700 relative shadow-md mb-4">
                                @if($llibre->img_portada)
                                <img src="{{ asset('img/' . $llibre->img_portada) }}" class="object-cover w-full h-full group-hover:scale-110 transition duration-700">
                                @else
                                <div class="flex items-center justify-center h-full text-slate-400"><span class="text-4xl">📘</span></div>
                                @endif
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white line-clamp-1 group-hover:text-blue-600 transition">{{ $llibre->titol }}</h3>
                            <p class="text-lg font-extrabold text-blue-600 dark:text-blue-400 mt-1">{{ number_format($llibre->preu, 2) }} €</p>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="fixed bottom-0 left-0 w-full h-[30vh] bg-slate-300 dark:bg-slate-900 text-slate-800 dark:text-slate-300 flex flex-col justify-between overflow-hidden transition-colors duration-500"
        :class="footerVisible ? 'z-10' : '-z-10'">
        <div class="flex-grow flex items-center relative z-10 py-10">
            <div class="w-full max-w-full mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-start gap-12">
                <div class="text-left">
                    <h4 class="text-lg font-bold uppercase tracking-widest mb-6 opacity-60">{{ __('Contacte') }}</h4>
                    <ul class="space-y-2 text-xl font-serif">
                        <li><a href="mailto:hola@bookcores.cat" class="hover:text-blue-600 dark:hover:text-blue-300 transition-colors">alex.tarazaga@uvic.cat</a></li>
                        <li><a href="mailto:suport@bookcores.cat" class="hover:text-blue-600 dark:hover:text-blue-300 transition-colors">david.casany@uvic.cat</a></li>
                    </ul>
                </div>
                <div class="text-left md:text-right md:pr-24">
                    <h4 class="text-lg font-bold uppercase tracking-widest mb-6 opacity-60">{{ __('Segueix-nos') }}</h4>
                    <div class="flex gap-6 text-xl md:justify-end">
                        <a href="#" class="hover:text-blue-600 dark:hover:text-blue-300 transition-colors">Instagram</a>
                        <a href="#" class="hover:text-blue-600 dark:hover:text-blue-300 transition-colors">Twitter</a>
                        <a href="#" class="hover:text-blue-600 dark:hover:text-blue-300 transition-colors">LinkedIn</a>
                    </div>
                    <div class="mt-8 flex gap-4 text-sm opacity-50 md:justify-end">
                        <a href="#" class="hover:underline">{{ __('Avís Legal') }}</a>
                        <a href="#" class="hover:underline">{{ __('Cookies') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    {{-- SCRIPT SLIDER --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('heroSlider', () => ({
                books: window.sliderData || [],
                isAnimating: false,
                autoplay: null,
                init() { this.startAutoplay(); },
                startAutoplay() { 
                    if (this.books.length > 1) { 
                        this.autoplay = setInterval(() => { this.slide(); }, 6000); 
                    } 
                },
                slide() { this.isAnimating = true; },
                handleTransitionEnd() { 
                    if (!this.isAnimating) return; 
                    this.isAnimating = false; 
                    const firstBook = this.books.shift(); 
                    this.books.push(firstBook); 
                },
                stopAutoplay() { if (this.autoplay) clearInterval(this.autoplay); }
            }));
        });
    </script>

    <style>
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; opacity: 0; transform: translateY(20px); }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
    </style>
</body>
</html>