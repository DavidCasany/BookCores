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
    </style>
</head>

<body class="antialiased bg-slate-100 text-slate-800 dark:bg-slate-900 dark:text-slate-100 transition-colors duration-500"
      x-data="{ 
          scrollAtTop: true,
          darkMode: true,
          toggleTheme() {
              this.darkMode = !this.darkMode;
              if (this.darkMode) {
                  document.documentElement.classList.add('dark');
              } else {
                  document.documentElement.classList.remove('dark');
              }
          },
          handleScroll() {
              this.scrollAtTop = (window.scrollY < 50);
          }
      }"
      @scroll.window="handleScroll()">

    {{-- HEADER / NAV --}}
    <header class="fixed top-0 w-full z-50 py-3 transition-all duration-300"
            :class="scrollAtTop ? 'bg-transparent py-4' : 'bg-white/80 dark:bg-slate-900/90 backdrop-blur-md shadow-md py-2'">
        
        <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            
            {{-- LOGO --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <svg class="h-8 w-8 transition-colors duration-300" viewBox="0 0 24 24" fill="none" 
                     :stroke="scrollAtTop ? '#fff' : '#3b82f6'" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                <span class="font-serif text-xl font-bold transition-colors duration-300"
                      :class="scrollAtTop ? 'text-white' : 'text-slate-900 dark:text-white'">Book</span>
                <span class="font-sans font-bold transition-colors duration-300"
                      :class="scrollAtTop ? 'text-blue-200' : 'text-blue-600 dark:text-blue-400'">Cores</span>
            </a>

            {{-- DRETA: Cerca, Idioma, User Menu --}}
            <div class="flex items-center gap-6">
                
                {{-- Selector Idioma (Petit) --}}
                <form action="{{ route('home') }}" method="GET" class="hidden sm:block">
                    <select name="lang" onchange="this.form.submit()" 
                            class="bg-transparent text-sm font-bold border-none focus:ring-0 cursor-pointer"
                            :class="scrollAtTop ? 'text-white/80 hover:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-blue-500'">
                        <option value="ca" class="text-slate-900" {{ app()->getLocale() == 'ca' ? 'selected' : '' }}>CA</option>
                        <option value="es" class="text-slate-900" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>ES</option>
                        <option value="en" class="text-slate-900" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
                    </select>
                </form>

                {{-- MENÚ D'USUARI (LA BOLETA) --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300 shadow-lg hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            :class="scrollAtTop ? 'bg-white/20 border-white/30 text-white backdrop-blur-sm' : 'bg-blue-600 border-blue-600 text-white'">
                        <span class="font-bold text-lg leading-none pt-0.5">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </span>
                    </button>

                    {{-- Desplegable --}}
                    <div x-show="open" 
                         @click.outside="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-3 w-56 rounded-xl shadow-2xl py-2 z-50 border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800"
                         x-cloak>
                        
                        <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 mb-2">
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Hola,') }}</p>
                            <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-blue-600 transition-colors">
                            {{ __('El meu perfil') }}
                        </a>
                        
                        {{-- Botó Dark Mode dins del menú --}}
                        <button @click="toggleTheme()" class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-blue-600 transition-colors flex items-center justify-between">
                            <span>{{ __('Canviar tema') }}</span>
                            <span x-text="darkMode ? '☀️' : '🌙'"></span>
                        </button>

                        <div class="border-t border-slate-100 dark:border-slate-700 mt-2 pt-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    {{ __('Tancar sessió') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>

    {{-- CARRUSEL SUPERIOR (60vh, Relative) --}}
    <div class="relative w-full h-[60vh] bg-slate-900 overflow-hidden" 
         x-data="{ 
             activeSlide: 0, 
             slides: {{ json_encode($llibresRecents->map(fn($l) => [
                 'img' => $l->img_hero ? asset('img/'.$l->img_hero) : ($l->img_portada ? asset('img/'.$l->img_portada) : null), 
                 'titol' => $l->titol,
                 'autor' => $l->autor->nom ?? 'Autor desconegut'
             ])) }},
             init() { setInterval(() => { this.activeSlide = (this.activeSlide + 1) % this.slides.length }, 6000); }
         }">
        
        <template x-for="(slide, index) in slides" :key="index">
            <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
                 :class="activeSlide === index ? 'opacity-100 z-10' : 'opacity-0 z-0'">
                
                {{-- Imatge de fons amb gradient --}}
                <div class="absolute inset-0 bg-slate-800">
                    <img :src="slide.img" class="w-full h-full object-cover opacity-60">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-100 dark:from-slate-900 via-transparent to-black/60"></div>
                </div>

                {{-- Contingut del slide --}}
                <div class="absolute bottom-0 left-0 w-full p-8 md:p-16 pb-20 flex items-end">
                    <div class="max-w-7xl mx-auto w-full">
                        <div class="animate-fade-in-up">
                            <span class="inline-block py-1 px-3 rounded-full bg-blue-600/90 backdrop-blur text-white text-xs font-bold uppercase tracking-wider mb-4 shadow-lg">
                                {{ __('Recomanat per a tu') }}
                            </span>
                            <h2 class="text-4xl md:text-6xl font-black text-white drop-shadow-2xl leading-tight mb-2" x-text="slide.titol"></h2>
                            <p class="text-xl text-white/90 font-serif italic" x-text="slide.autor"></p>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        {{-- Indicadors (Punts) --}}
        <div class="absolute bottom-6 right-8 z-20 flex space-x-2">
            <template x-for="(slide, index) in slides" :key="index">
                <button @click="activeSlide = index" 
                        class="w-3 h-3 rounded-full transition-all duration-300 shadow-sm border border-white/30"
                        :class="activeSlide === index ? 'bg-white scale-110' : 'bg-white/30 hover:bg-white/60'"></button>
            </template>
        </div>
    </div>

    {{-- CONTINGUT PRINCIPAL --}}
    <main class="relative z-10 -mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Barra de Filtres / Títol --}}
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 dark:border-slate-700 p-6 mb-10 flex flex-col md:flex-row justify-between items-center gap-4">
                <h3 class="text-2xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <span>📚</span> {{ __('Catàleg Complet') }}
                </h3>
                <div class="flex gap-2">
                    {{-- Exemples de botons de filtre --}}
                    <button class="px-4 py-2 rounded-full text-sm font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 hover:bg-blue-200 transition">{{ __('Tots') }}</button>
                    <button class="px-4 py-2 rounded-full text-sm font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition">{{ __('Ficció') }}</button>
                    <button class="px-4 py-2 rounded-full text-sm font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition">{{ __('Història') }}</button>
                </div>
            </div>

            {{-- GRID DE LLIBRES --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-20">
                @foreach($llibres as $llibre)
                    <div class="group relative bg-white dark:bg-slate-800 rounded-2xl shadow-sm hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 dark:border-slate-700 flex flex-col overflow-hidden">
                        
                        {{-- Imatge --}}
                        <a href="{{ route('llibres.show', $llibre->id_llibre) }}" class="relative aspect-[2/3] overflow-hidden bg-slate-200 dark:bg-slate-700">
                            @if($llibre->img_portada)
                                <img src="{{ asset('img/' . $llibre->img_portada) }}" alt="{{ $llibre->titol }}" 
                                     class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400"><span class="text-5xl">📖</span></div>
                            @endif
                            
                            {{-- Badge Nota --}}
                            <div class="absolute top-3 right-3 bg-white/95 dark:bg-slate-900/95 backdrop-blur px-2.5 py-1 rounded-full text-xs font-bold shadow-md flex items-center gap-1 text-slate-900 dark:text-white">
                                <svg class="w-3 h-3 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                {{ number_format($llibre->nota_promig, 1) }}
                            </div>
                        </a>

                        {{-- Info --}}
                        <div class="p-5 flex flex-col flex-grow">
                            <h4 class="font-bold text-lg text-slate-900 dark:text-white leading-tight mb-1 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                <a href="{{ route('llibres.show', $llibre->id_llibre) }}">{{ $llibre->titol }}</a>
                            </h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">{{ $llibre->autor->nom ?? 'Autor desconegut' }}</p>
                            
                            <div class="mt-auto flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-700">
                                <span class="font-black text-xl text-slate-900 dark:text-white">{{ number_format($llibre->preu, 2, ',', '.') }} €</span>
                                <button class="p-2 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </main>

    {{-- FOOTER SIMPLE --}}
    <footer class="bg-slate-200 dark:bg-slate-950 py-10 text-center text-slate-500 text-sm">
        <p>&copy; {{ date('Y') }} BookCores. {{ __('Tots els drets reservats.') }}</p>
    </footer>

    <style>
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; opacity: 0; transform: translateY(20px); }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
    </style>
</body>
</html>