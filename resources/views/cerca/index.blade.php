<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Cerca') }} - BookCores</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,900|georgia:400&display=swap" rel="stylesheet" />

    {{-- ⚡ SCRIPT CRÍTIC ANTIFLAIX (Per evitar que es vegi blanc un moment si és fosc) --}}
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Figtree', 'sans-serif'] },
                    animation: { 'spin-slow': 'spin 4s linear infinite' }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        [x-cloak] { display: none !important; }
        .animate-fade-in-down { animation: fadeInDown 0.5s ease-out; }
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        
        .shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
        @keyframes shake { 10%, 90% { transform: translate3d(-1px, 0, 0); } 20%, 80% { transform: translate3d(2px, 0, 0); } 30%, 50%, 70% { transform: translate3d(-4px, 0, 0); } 40%, 60% { transform: translate3d(4px, 0, 0); } }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 dark:bg-slate-900 dark:text-white min-h-screen font-sans selection:bg-blue-500 selection:text-white transition-colors duration-500"
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

    {{-- Botó de tancar / tornar (Adaptat Dark/Light) --}}
    <a href="{{ route('home') }}" class="fixed top-6 right-6 z-50 p-3 bg-white/80 dark:bg-slate-800/80 backdrop-blur rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition shadow-lg border border-slate-200 dark:border-slate-700 group">
        <svg class="w-6 h-6 text-slate-500 dark:text-slate-300 group-hover:text-blue-600 dark:group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </a>

    {{-- 🌞/🌙 BOTÓ FLOTANT DE TEMA --}}
    <div class="fixed bottom-6 right-6 z-[60] flex items-center justify-center group">
        <div class="absolute inset-0 -m-[2px] rounded-full blur-md opacity-60 animate-spin-slow transition-all duration-500"
            :style="darkMode ? 'background: conic-gradient(from 0deg, #ef4444, #f97316, #eab308, #ef4444);' : 'background: conic-gradient(from 0deg, #a855f7, #3b82f6, #06b6d4, #a855f7);'"></div>
        <button @click="toggleTheme()" class="relative z-10 p-4 rounded-full transition-all duration-300 transform hover:scale-110 border border-slate-200/20 dark:border-slate-700/50"
            :class="darkMode ? 'bg-slate-800 text-yellow-400' : 'bg-white text-slate-800'">
            <svg x-show="darkMode" class="h-8 w-8 animate-[spin_10s_linear_infinite]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            <svg x-show="!darkMode" class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
        </button>
    </div>

    <div x-data="cercaApp()" x-init="init()" class="max-w-7xl mx-auto px-4 pt-24">

        {{-- 🔎 BARRA DE CERCA PRINCIPAL --}}
        <div class="flex flex-col md:flex-row gap-4 mb-8 animate-fade-in-down">
            
            {{-- Desplegable Tipus --}}
            <div class="relative shrink-0">
                <select x-model="type" @change="updateUrl()" class="w-full md:w-auto bg-white dark:bg-slate-800 text-slate-800 dark:text-white border border-slate-200 dark:border-slate-700 rounded-2xl py-4 pl-6 pr-12 text-lg font-bold focus:ring-2 focus:ring-blue-500 outline-none appearance-none cursor-pointer shadow-lg hover:border-blue-500 transition">
                    <option value="llibre">📖 {{ __('Llibre') }}</option>
                    <option value="autor">✍️ {{ __('Autor') }}</option>
                    <option value="editorial">🏢 {{ __('Editorial') }}</option>
                    <option value="tag">🏷️ {{ __('Tag / Gènere') }}</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500 dark:text-slate-400">
                    <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                </div>
            </div>

            {{-- Input Text --}}
            <div class="relative flex-grow">
                <div class="flex items-center bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 focus-within:ring-2 focus-within:ring-blue-500/50 focus-within:border-blue-500 transition-all shadow-lg"
                     :class="inputError ? 'border-red-500 ring-2 ring-red-500/50 shake' : ''">
                    
                    <svg class="w-6 h-6 ml-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    
                    <input x-model="query" 
                           @input.debounce.500ms="type !== 'tag' ? performSearch() : null" 
                           @keydown.enter="type === 'tag' ? addTag() : performSearch()"
                           @input="inputError = false"
                           type="text" 
                           class="w-full bg-transparent border-none text-slate-900 dark:text-white text-xl placeholder-slate-400 dark:placeholder-slate-500 px-5 py-4 focus:ring-0 focus:outline-none" 
                           :placeholder="placeholderText">
                    
                    {{-- Spinner --}}
                    <div x-show="validating" class="mr-5">
                        <div class="animate-spin rounded-full h-5 w-5 border-2 border-blue-500 border-t-transparent"></div>
                    </div>

                    {{-- Botó Afegir --}}
                    <button x-show="type === 'tag' && query.length > 0 && !validating" 
                            @click="addTag()"
                            class="mr-3 bg-blue-600 hover:bg-blue-500 text-white px-5 py-2 rounded-xl text-sm font-bold transition shadow-md">
                        {{ __('Afegir') }}
                    </button>
                </div>

                {{-- Error Tag --}}
                <div x-show="inputError" x-cloak x-transition class="absolute -bottom-8 left-2 text-red-500 dark:text-red-400 text-xs font-bold flex items-center gap-1 bg-red-100 dark:bg-red-900/20 px-2 py-1 rounded border border-red-500/30">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    {{ __('Tag no trobat (Prova un altre gènere o autor)') }}
                </div>

                {{-- Tags Actius --}}
                <div x-show="type === 'tag' && tags.length > 0" class="flex flex-wrap gap-2 mt-4 animate-fade-in-down px-1">
                    <template x-for="(tag, index) in tags" :key="index">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-200 border border-blue-200 dark:border-blue-500/30 shadow-sm backdrop-blur-sm">
                            <span x-text="tag"></span>
                            <button @click="removeTag(index)" class="ml-2 text-blue-400 dark:text-blue-400 hover:text-blue-600 dark:hover:text-white focus:outline-none hover:bg-blue-200 dark:hover:bg-blue-600/50 rounded p-0.5 transition">
                                <svg class="w-3 h-3" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </span>
                    </template>
                </div>
            </div>
        </div>

        {{-- 🎛️ BARRA D'EINES --}}
        <div class="flex flex-col sm:flex-row justify-between items-end sm:items-center mb-6 animate-fade-in-down border-b border-slate-200 dark:border-slate-800 pb-4 gap-4" 
             x-show="results.length > 0 || loading || (query.length > 1)">
            
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">
                <span x-show="!loading">
                    {{ __("S'han trobat") }} <span class="text-slate-900 dark:text-white font-bold text-lg" x-text="results.length"></span> {{ __('resultats') }}
                </span>
                <span x-show="loading" class="animate-pulse">{{ __('Cercant...') }}</span>
            </p>

            {{-- ✨ CUSTOM DROPDOWN --}}
            <div x-data="{ openSort: false }" class="relative z-20">
                <button @click="openSort = !openSort" @click.outside="openSort = false"
                        class="flex items-center gap-3 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-white pl-4 pr-3 py-2.5 rounded-xl font-bold transition shadow-md border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 text-sm">
                    <span class="text-slate-400 dark:text-slate-400 font-normal uppercase text-xs tracking-wider">{{ __('Ordenar:') }}</span>
                    <span x-text="sortLabel" class="min-w-[140px] text-left"></span>
                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="openSort ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <div x-show="openSort" x-cloak 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     class="absolute right-0 mt-2 w-56 bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden py-1">
                    
                    <template x-for="(label, key) in sortOptions" :key="key">
                        <button @click="sort = key; performSearch(); openSort = false"
                                class="w-full text-left px-4 py-3 text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-700 transition flex items-center justify-between group"
                                :class="sort === key ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-slate-700/50' : 'text-slate-600 dark:text-slate-300'">
                            <span x-text="label"></span>
                            <svg x-show="sort === key" class="w-4 h-4 text-blue-600 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        {{-- GRAELLA DE RESULTATS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 pb-20">
            
            {{-- Skeleton Loading --}}
            <template x-if="loading">
                <div class="contents">
                    <template x-for="i in 4">
                        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 animate-pulse h-96 shadow-sm border border-slate-100 dark:border-slate-700">
                            <div class="bg-slate-200 dark:bg-slate-700 h-64 rounded-xl mb-4"></div>
                            <div class="bg-slate-200 dark:bg-slate-700 h-4 w-3/4 rounded mb-2"></div>
                            <div class="bg-slate-200 dark:bg-slate-700 h-3 w-1/2 rounded"></div>
                        </div>
                    </template>
                </div>
            </template>

            {{-- LLISTA REAL --}}
            <template x-for="item in results" :key="item.id_llibre || item.id">
                <div class="contents">
                    {{-- TIPUS A: GRUP (Autor/Editorial) --}}
                    <template x-if="item.llibres">
                        <div class="col-span-full bg-blue-50 dark:bg-slate-800/30 rounded-3xl p-8 mb-8 border border-blue-100 dark:border-slate-700/50">
                            <h2 class="text-3xl font-black mb-6 text-slate-900 dark:text-white flex items-center gap-3">
                                <span class="bg-gradient-to-r from-blue-600 to-purple-600 w-1.5 h-8 rounded-full"></span>
                                <span x-text="item.nom"></span>
                                <span class="text-xs bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-200 px-3 py-1 rounded-full border border-blue-200 dark:border-blue-500/30 font-bold uppercase tracking-wider">{{ __('Coincidència Exacta') }}</span>
                            </h2>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                                <template x-for="llibre in item.llibres" :key="llibre.id_llibre">
                                    <a :href="'/llibre/' + llibre.id_llibre" class="group block bg-white dark:bg-slate-900 rounded-xl p-3 hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-lg hover:shadow-2xl hover:-translate-y-1 border border-slate-100 dark:border-transparent hover:border-slate-300 dark:hover:border-slate-600">
                                        <div class="aspect-[2/3] w-full overflow-hidden rounded-lg mb-3 bg-slate-200 dark:bg-slate-800 relative shadow-md">
                                            <img :src="llibre.img_portada ? '/img/' + llibre.img_portada : 'https://placehold.co/400x600?text=No+Img'" 
                                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                        </div>
                                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition" x-text="llibre.titol"></p>
                                        <p class="text-xs mt-2 font-bold flex items-center gap-1" :class="(llibre.ressenyes_avg_puntuacio > 0) ? 'text-yellow-500 dark:text-yellow-400' : 'text-slate-400 dark:text-slate-600'">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            <span x-text="(llibre.ressenyes_avg_puntuacio > 0) ? parseFloat(llibre.ressenyes_avg_puntuacio).toFixed(1) : '-'"></span>
                                        </p>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>

                    {{-- TIPUS B: LLIBRE INDIVIDUAL --}}
                    <template x-if="!item.llibres">
                        <div class="group relative bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/50 rounded-2xl overflow-hidden hover:shadow-2xl hover:border-slate-300 dark:hover:border-slate-500/50 hover:-translate-y-1 transition duration-300 h-full flex flex-col shadow-sm">
                            <a :href="'/llibre/' + (item.id_llibre || item.id)" class="flex-grow flex flex-col">
                                <div class="aspect-[2/3] w-full bg-slate-200 dark:bg-slate-700 overflow-hidden relative">
                                    <img :src="item.img_portada ? '/img/' + item.img_portada : 'https://placehold.co/400x600?text=No+Img'" 
                                         class="object-cover w-full h-full opacity-90 group-hover:opacity-100 group-hover:scale-105 transition duration-700 ease-out">
                                    
                                    {{-- Nota flotant --}}
                                    <div class="absolute top-3 right-3 backdrop-blur-md px-2.5 py-1 rounded-full shadow-lg flex items-center gap-1 border border-white/20 dark:border-white/10"
                                         :class="(item.ressenyes_avg_puntuacio > 0) ? 'bg-slate-900/80 text-white' : 'bg-slate-200/90 dark:bg-slate-800/80 text-slate-500 dark:text-slate-400'">
                                        <svg class="w-3.5 h-3.5" :class="(item.ressenyes_avg_puntuacio > 0) ? 'text-yellow-400' : 'text-slate-400 dark:text-slate-600'" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <span class="text-xs font-bold" x-text="(item.ressenyes_avg_puntuacio > 0) ? parseFloat(item.ressenyes_avg_puntuacio).toFixed(1) : '-'"></span>
                                    </div>
                                </div>

                                <div class="p-5 flex flex-col flex-grow">
                                    <h3 class="font-bold text-lg leading-tight mb-1 text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition line-clamp-2" x-text="item.titol"></h3>
                                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mb-4 line-clamp-1" x-text="item.autor ? item.autor.nom : '{{ __('Autor Desconegut') }}'"></p>
                                    
                                    <div class="mt-auto flex items-center justify-between border-t border-slate-100 dark:border-slate-700/50 pt-4">
                                        <span class="text-slate-900 dark:text-white font-black text-xl tracking-tight" x-text="parseFloat(item.preu).toFixed(2) + ' €'"></span>
                                        <span x-show="item.genere" class="text-[10px] uppercase tracking-wider bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 px-2.5 py-1 rounded-lg font-bold border border-slate-200 dark:border-slate-600/30" x-text="item.genere"></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </template>
                </div>
            </template>

            {{-- SENSE RESULTATS --}}
            <div x-show="!loading && results.length === 0 && (query.length > 1 || tags.length > 0)" class="col-span-full py-20 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-slate-200 dark:bg-slate-800 mb-6">
                    <span class="text-5xl">🔍</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">{{ __("No s'han trobat llibres") }}</h3>
                <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto">{{ __("Prova de canviar els filtres, revisar l'ortografia o utilitzar termes més generals.") }}</p>
            </div>
        </div>
    </div>

    <script>
        function cercaApp() {
            return {
                query: '',
                type: 'llibre',
                sort: 'relevance',
                results: [],
                tags: [],
                loading: false,
                validating: false,
                inputError: false,

                // Opcions d'ordenació (TRADUÏDES)
                sortOptions: {
                    'relevance': '⭐ {{ __("Millor Valorants") }}',
                    'preu_asc': '📉 {{ __("Preu: Baix a Alt") }}',
                    'preu_desc': '📈 {{ __("Preu: Alt a Baix") }}',
                    'newest': '📅 {{ __("Més recents") }}'
                },

                get sortLabel() {
                    return this.sortOptions[this.sort];
                },

                get placeholderText() {
                    if (this.type === 'llibre') return '{{ __("Escriu el títol del llibre...") }}';
                    if (this.type === 'autor') return '{{ __("Busca un autor...") }}';
                    if (this.type === 'editorial') return '{{ __("Busca una editorial...") }}';
                    return '{{ __("Escriu un gènere i prem Enter...") }}';
                },

                init() {
                    const params = new URLSearchParams(window.location.search);
                    if (params.has('q')) this.query = params.get('q');
                    if (params.has('type')) this.type = params.get('type');
                    if (params.has('sort')) this.sort = params.get('sort');
                    
                    const tagsFromUrl = params.getAll('tags[]');
                    if (tagsFromUrl.length > 0) this.tags = tagsFromUrl;
                    
                    if ((this.query && this.query.length > 1) || this.tags.length > 0) {
                        this.performSearch();
                    }
                },

                async addTag() {
                    const text = this.query.trim();
                    if (text === '' || this.tags.includes(text)) return;

                    this.validating = true;
                    this.inputError = false;

                    try {
                        const response = await fetch(`/api/validar-tag?tag=${encodeURIComponent(text)}`);
                        const data = await response.json();

                        if (data.valid) {
                            this.tags.push(text);
                            this.query = '';
                            this.performSearch();
                        } else {
                            this.inputError = true;
                            setTimeout(() => this.inputError = false, 3000);
                        }
                    } catch (e) {
                        console.error("Error validant tag", e);
                    } finally {
                        this.validating = false;
                    }
                },

                removeTag(index) {
                    this.tags.splice(index, 1);
                    this.performSearch();
                },

                updateUrl() {
                    const url = new URL(window.location);
                    
                    if(this.query) url.searchParams.set('q', this.query);
                    else url.searchParams.delete('q');
                    
                    url.searchParams.set('type', this.type);
                    url.searchParams.set('sort', this.sort);

                    url.searchParams.delete('tags[]');
                    this.tags.forEach(tag => url.searchParams.append('tags[]', tag));

                    window.history.pushState({}, '', url);
                },

                async performSearch() {
                    if (this.type === 'tag' && this.tags.length === 0 && this.query.length < 2) {
                        this.results = [];
                        this.updateUrl();
                        return;
                    }
                    if (this.type !== 'tag' && this.query.length < 2) {
                        this.results = [];
                        return;
                    }

                    this.loading = true;
                    this.updateUrl();

                    try {
                        const params = new URLSearchParams({
                            q: this.query,
                            type: this.type,
                            sort: this.sort
                        });

                        this.tags.forEach(t => params.append('tags[]', t));

                        const response = await fetch(`/api/cerca?${params.toString()}`);
                        this.results = await response.json();

                    } catch (error) {
                        console.error('Error cercant:', error);
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</body>
</html>