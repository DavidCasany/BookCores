<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('La teva llibreria') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,900&display=swap" rel="stylesheet" />

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
                        'breathing-glow-purple': 'breathingGlowPurple 4s ease-in-out infinite',
                        'breathing-glow-warm': 'breathingGlowWarm 4s ease-in-out infinite',
                    },
                    keyframes: {
                        breathingGlowPurple: {
                            '0%, 100%': { boxShadow: '0 0 4px 1px rgba(139, 92, 246, 0.4), 0 0 8px 2px rgba(59, 130, 246, 0.2)' },
                            '50%': { boxShadow: '0 0 8px 2px rgba(139, 92, 246, 0.5), 0 0 12px 4px rgba(59, 130, 246, 0.3)' },
                        },
                        breathingGlowWarm: {
                            '0%, 100%': { boxShadow: '0 0 4px 1px rgba(220, 38, 38, 0.4), 0 0 8px 2px rgba(234, 88, 12, 0.3), 0 0 12px 4px rgba(250, 204, 21, 0.1)' }, 
                            '50%': { boxShadow: '0 0 8px 2px rgba(220, 38, 38, 0.5), 0 0 12px 4px rgba(234, 88, 12, 0.4), 0 0 16px 6px rgba(250, 204, 21, 0.2)' },
                        }
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="antialiased bg-slate-300 text-slate-800 dark:bg-slate-900 dark:text-slate-100 transition-colors duration-500"
      x-data="{ 
          scrollAtTop: true,
          darkMode: true,
          footerVisible: false,
          strongShadow: true,
          toggleTheme() {
              this.darkMode = !this.darkMode;
              if (this.darkMode) {
                  document.documentElement.classList.add('dark');
              } else {
                  document.documentElement.classList.remove('dark');
              }
          },
          handleScroll() {
              this.scrollAtTop = (window.scrollY < window.innerHeight - 80);
              const distanceToBottom = document.documentElement.scrollHeight - window.scrollY - window.innerHeight;
              
              // Footer ajustat al 30% de la pantalla (30vh)
              this.footerVisible = distanceToBottom < (window.innerHeight * 0.30);
              this.strongShadow = distanceToBottom > 50;
          }
      }"
      @scroll.window="handleScroll()">

    @php
        $llibresCollection = $llibresRecents->map(function($llibre) {
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

        $titolPart1 = __('Troba la teva');
        $titolPart2 = __('història preferida');
    @endphp

    <script>
        window.sliderData = <?php echo json_encode($llibresCollection); ?>;
    </script>

    {{-- BOTÓ FLOTANT --}}
    <div class="fixed bottom-6 right-6 z-[60] flex items-center justify-center group">
        <div class="absolute inset-0 -m-[2px] rounded-full blur-md opacity-60 animate-spin-slow transition-all duration-500"
             :style="darkMode 
                ? 'background: conic-gradient(from 0deg, #ef4444, #f97316, #eab308, #ef4444);' 
                : 'background: conic-gradient(from 0deg, #a855f7, #3b82f6, #06b6d4, #a855f7);'">
        </div>
        <button @click="toggleTheme()" 
                class="relative z-10 p-4 rounded-full transition-all duration-300 transform hover:scale-110 border border-slate-200/20 dark:border-slate-700/50"
                :class="darkMode ? 'bg-slate-800 text-yellow-400' : 'bg-white text-slate-800'">
            <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 animate-[spin_10s_linear_infinite]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>
    </div>

    {{-- CARRUSEL FIXE AL FONS --}}
    <div class="fixed inset-0 w-full h-screen z-0 bg-slate-900 overflow-hidden"
         x-data="heroSlider()">
        <div class="flex h-full w-full will-change-transform"
             :class="isAnimating ? 'transition-transform duration-1000 ease-in-out -translate-x-full' : ''"
             @transitionend="handleTransitionEnd()">
            <div class="relative min-w-full w-full h-full flex-shrink-0 flex items-center justify-center">
                <template x-if="books[0]">
                    <div class="w-full h-full relative">
                        <template x-if="books[0].img">
                            <div class="absolute inset-0">
                                <img :src="books[0].img" class="w-full h-full object-cover brightness-50">
                                <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-transparent to-black/80"></div>
                            </div>
                        </template>
                        <template x-if="!books[0].img">
                            <div class="w-full h-full flex items-center justify-center bg-slate-800 text-slate-600"><span class="text-6xl">📘</span></div>
                        </template>
                    </div>
                </template>
            </div>
            <div class="relative min-w-full w-full h-full flex-shrink-0 flex items-center justify-center">
                <template x-if="books[1]">
                    <div class="w-full h-full relative">
                        <template x-if="books[1].img">
                            <div class="absolute inset-0">
                                <img :src="books[1].img" class="w-full h-full object-cover brightness-50">
                                <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-transparent to-black/80"></div>
                            </div>
                        </template>
                        <template x-if="!books[1].img">
                            <div class="w-full h-full flex items-center justify-center bg-slate-800 text-slate-600"><span class="text-6xl">📘</span></div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- FONS SÒLID INTERMEDI --}}
    <div class="fixed inset-0 w-full h-full bg-slate-300 dark:bg-slate-900 transition-opacity duration-700 pointer-events-none"
         :class="footerVisible ? 'opacity-100 z-5' : 'opacity-0 -z-10'">
    </div>

    {{-- HEADER --}}
    <header class="fixed w-full z-50 py-3 transition-colors duration-300">
        <div class="absolute inset-0 bg-white/20 backdrop-blur-md border-b border-white/20 shadow-sm -z-10 transition-colors duration-300"
             :class="scrollAtTop ? 'bg-white/10 border-white/20' : 'bg-white/70 dark:bg-slate-900/80 border-slate-200 dark:border-slate-700 shadow-md'"></div>
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex justify-between items-center">
                <div class="flex-shrink-0 flex items-center gap-2">
                    <a href="/" class="font-serif text-2xl font-bold flex items-center gap-2 transition-colors">
                        <svg class="h-8 w-8 drop-shadow-md transition-colors duration-300" viewBox="0 0 24 24" fill="none" 
                             :stroke="scrollAtTop ? '#3b82f6' : (darkMode ? '#60a5fa' : '#2563eb')" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        <span class="font-bold transition-colors duration-300"
                              :class="scrollAtTop ? 'text-white' : 'text-slate-900 dark:text-white'">Book</span>
                        <span class="drop-shadow-md font-bold transition-colors duration-300"
                              :class="scrollAtTop ? 'text-blue-500' : 'text-blue-600 dark:text-blue-400'">Cores</span>
                    </a>
                </div>
                <div class="flex items-center space-x-6">
                    <form action="{{ route('home') }}" method="GET" class="hidden sm:flex items-center">
                        <div class="relative group">
                            <select name="lang" onchange="this.form.submit()" 
                                    class="appearance-none bg-transparent rounded-full py-1 pl-4 pr-8 text-sm font-bold cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 border"
                                    :class="scrollAtTop ? 'text-white border-white/50 hover:bg-white/10' : 'text-slate-900 dark:text-white border-slate-300 dark:border-slate-600 hover:border-blue-500'">
                                <option value="ca" class="text-slate-900 bg-white" {{ app()->getLocale() == 'ca' ? 'selected' : '' }}>CA</option>
                                <option value="es" class="text-slate-900 bg-white" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>ES</option>
                                <option value="en" class="text-slate-900 bg-white" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
                                <option value="ja" class="text-slate-900 bg-white" {{ app()->getLocale() == 'ja' ? 'selected' : '' }}>JA</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 transition-colors duration-300"
                                 :class="scrollAtTop ? 'text-white' : 'text-slate-600 dark:text-slate-300'">
                                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </form>
                    <div class="hidden sm:block h-6 w-px transition-colors duration-300"
                         :class="scrollAtTop ? 'bg-white/40' : 'bg-slate-300 dark:bg-slate-600'"></div>
                    <nav class="flex space-x-4 items-center">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-bold transition-colors duration-300 hover:text-blue-500"
                                   :class="scrollAtTop ? 'text-white' : 'text-slate-900 dark:text-white'">{{ __('Dashboard') }}</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-bold transition-colors duration-300 hover:text-blue-500"
                                   :class="scrollAtTop ? 'text-white' : 'text-slate-900 dark:text-white'">{{ __('Inicia sessió') }}</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="text-sm font-bold px-5 py-2.5 rounded-full transition shadow-lg bg-blue-600 text-white hover:bg-blue-700">{{ __('Registra\'t') }}</a>
                                @endif
                            @endauth
                        @endif
                    </nav>
                </div>
            </div>
        </div>
    </header>

    {{-- HERO TEXT --}}
    <div class="relative z-20 w-full h-screen flex flex-col items-center justify-center pointer-events-none pb-20"
         x-data="{
            text1: '',
            text2: '',
            fullText1: @js($titolPart1),
            fullText2: @js($titolPart2),
            cursor: true,
            init() { setTimeout(() => this.type(), 300); },
            type() {
                let i = 0; let speed = 90;
                let int1 = setInterval(() => {
                    this.text1 += this.fullText1.charAt(i); i++;
                    if (i > this.fullText1.length) {
                        clearInterval(int1); i = 0;
                        let int2 = setInterval(() => {
                            this.text2 += this.fullText2.charAt(i); i++;
                            if (i > this.fullText2.length) {
                                clearInterval(int2); this.cursor = false;
                            }
                        }, speed);
                    }
                }, speed);
            }
         }">
        <div class="text-center px-4 sm:px-6 lg:px-8 pointer-events-auto">
            <h1 class="text-5xl md:text-8xl tracking-tight font-black text-white drop-shadow-2xl mb-8 leading-tight">
                <span class="block">
                    <span x-text="text1"></span><span x-show="cursor && text2.length === 0" class="text-blue-200 animate-pulse">|</span>
                </span>
                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-200 via-white to-indigo-200 min-h-[1.1em]">
                    <span x-text="text2"></span><span x-show="cursor && text2.length > 0" class="text-white animate-pulse">|</span>
                </span>
            </h1>
            <div class="animate-fade-in-up opacity-0" style="animation-delay: 3s; animation-fill-mode: forwards;">
                <p class="mt-4 max-w-2xl mx-auto text-xl md:text-2xl text-white/90 drop-shadow-lg font-medium">
                    {{ __('Explora el nostre catàleg i descobreix mons nous.') }}
                </p>
                <div class="mt-12 flex flex-col sm:flex-row justify-center gap-4">
                    <a href="#novetats" class="px-8 py-4 text-lg font-bold rounded-full text-slate-900 bg-white hover:bg-blue-50 transition transform hover:-translate-y-1 shadow-[0_10px_20px_rgba(0,0,0,0.3)]">
                        {{ __('Veure catàleg') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-10 animate-bounce">
            <svg class="w-8 h-8 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
        </div>
    </div>

    {{-- NOVETATS --}}
    <main id="novetats" class="relative z-30 mt-[40vh] bg-slate-50/90 dark:bg-slate-800/90 backdrop-blur-md min-h-screen rounded-b-[3rem] mb-[30vh] transition-all duration-700 ease-out"
          :class="strongShadow ? 'shadow-[0_20px_60px_rgba(0,0,0,0.3)]' : 'shadow-[0_10px_30px_rgba(0,0,0,0.1)]'">
        
        <div class="absolute -top-12 left-0 w-full h-12 bg-slate-50/90 dark:bg-slate-800/90 backdrop-blur-md rounded-t-[3rem] transition-colors duration-500"></div>
        
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="flex justify-between items-end mb-12 border-b border-slate-200 dark:border-slate-700 pb-4">
                <div>
                    <h2 class="text-4xl font-bold text-slate-900 dark:text-white">{{ __('Els més ben valorats ⭐') }}</h2>
                    <p class="text-slate-500 dark:text-slate-400 mt-2 text-lg">{{ __('Llibres amb les millors ressenyes de la comunitat') }}</p>
                </div>
                <a href="#" class="text-base font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center gap-1">
                    {{ __('Veure tot') }} 
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                @forelse ($llibres as $llibre)
                    <div class="group relative bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-3xl p-5 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 flex flex-col h-full">
                        <a href="{{ route('llibres.show', $llibre->id_llibre) }}" class="block">
                            <div class="aspect-w-2 aspect-h-3 w-full overflow-hidden rounded-2xl bg-slate-100 dark:bg-slate-700 mb-5 relative shadow-inner group-hover:shadow-md transition">
                                @if($llibre->img_portada)
                                    <img src="{{ asset('img/' . $llibre->img_portada) }}" alt="{{ $llibre->titol }}" class="object-cover w-full h-72 group-hover:scale-110 transition duration-700 ease-in-out">
                                @else
                                    <div class="flex items-center justify-center h-72 text-slate-400"><span class="text-5xl">📖</span></div>
                                @endif
                                <span class="absolute top-3 right-3 bg-white/95 dark:bg-slate-900/95 backdrop-blur text-slate-900 dark:text-white text-sm font-bold px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1">
                                    <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    {{ number_format($llibre->nota_promig, 1) }}
                                </span>
                            </div>
                        </a>
                        <div class="flex flex-col flex-grow">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white line-clamp-2 leading-tight group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                <a href="{{ route('llibres.show', $llibre->id_llibre) }}">{{ $llibre->titol }}</a>
                            </h3>
                            <p class="text-base text-slate-500 dark:text-slate-400 mt-2 font-medium">{{ $llibre->autor ? $llibre->autor->nom : __('Autor Desconegut') }}</p>
                            <div class="mt-auto pt-6 flex items-center justify-between border-t border-slate-50 dark:border-slate-700">
                                <p class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ number_format($llibre->preu, 2, ',', '.') }} €</p>
                                <button class="relative z-10 p-3 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-500 dark:hover:text-white transition-all duration-200 shadow-sm hover:shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-24 text-center bg-white dark:bg-slate-800 rounded-3xl border border-dashed border-slate-300 dark:border-slate-600">
                        <span class="text-6xl mb-4">📚</span>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('Encara no hi ha llibres') }}</h3>
                        <p class="text-slate-500 dark:text-slate-400 max-w-sm mx-auto mt-2">{{ __('Sembla que la base de dades està buida.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    {{-- FOOTER COMPACTE (30vh) --}}
    <footer class="fixed bottom-0 left-0 w-full h-[30vh] bg-slate-300 dark:bg-slate-900 text-slate-800 dark:text-slate-300 flex flex-col justify-between overflow-hidden transition-colors duration-500"
            :class="footerVisible ? 'z-10' : '-z-10'">
        
        <div class="flex-grow flex items-center relative z-10 py-10">
            <div class="w-full max-w-full mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-start gap-12">
                
                {{-- Esquerra: Contacte (2 emails) --}}
                <div class="text-left">
                    <h4 class="text-lg font-bold uppercase tracking-widest mb-6 opacity-60">{{ __('Contacte') }}</h4>
                    <ul class="space-y-2 text-xl font-serif">
                        <li><a href="mailto:hola@bookcores.cat" class="hover:text-blue-600 dark:hover:text-blue-300 transition-colors">alex.tarazaga@uvic.cat</a></li>
                        <li><a href="mailto:suport@bookcores.cat" class="hover:text-blue-600 dark:hover:text-blue-300 transition-colors">david.casany@uvic.cat</a></li>
                    </ul>
                </div>

                {{-- Dreta: Xarxes i Legals --}}
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

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('heroSlider', () => ({
                books: window.sliderData || [],
                isAnimating: false,
                autoplay: null,
                init() { this.startAutoplay(); },
                startAutoplay() { if (this.books.length > 1) { this.autoplay = setInterval(() => { this.slide(); }, 5000); } },
                slide() { this.isAnimating = true; },
                handleTransitionEnd() { if (!this.isAnimating) return; this.isAnimating = false; const firstBook = this.books.shift(); this.books.push(firstBook); },
                stopAutoplay() { if (this.autoplay) clearInterval(this.autoplay); }
            }));
        });
    </script>

    <style>
        .animate-fade-in-up { animation: fadeInUp 1s ease-out forwards; opacity: 0; transform: translateY(20px); }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }

        /* Ocultar barra de desplaçament */
        html {
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE i Edge */
        }
        html::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }
    </style>

</body>
</html>