<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cerca - BookCores</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        [x-cloak] { display: none !important; }
        .animate-fade-in-down { animation: fadeInDown 0.5s ease-out; }
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        
        /* Animació d'error (sacsejada) */
        .shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
        @keyframes shake { 10%, 90% { transform: translate3d(-1px, 0, 0); } 20%, 80% { transform: translate3d(2px, 0, 0); } 30%, 50%, 70% { transform: translate3d(-4px, 0, 0); } 40%, 60% { transform: translate3d(4px, 0, 0); } }
    </style>
</head>
<body class="bg-slate-900 text-white min-h-screen font-sans">

    {{-- Botó de tancar / tornar --}}
    <a href="{{ route('home') }}" class="fixed top-6 right-6 z-50 p-2 bg-slate-800 rounded-full hover:bg-slate-700 transition shadow-lg border border-slate-700">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </a>

    {{-- Iniciem l'app i llegim paràmetres URL al init() --}}
    <div x-data="cercaApp()" x-init="init()" class="max-w-7xl mx-auto px-4 pt-24">

        {{-- BARRA DE CERCA --}}
        <div class="flex flex-col md:flex-row gap-4 mb-12 animate-fade-in-down">
            
            <div class="relative">
                <select x-model="type" @change="updateUrl()" class="bg-slate-800 text-white border border-slate-700 rounded-xl py-4 pl-6 pr-10 text-lg font-bold focus:ring-2 focus:ring-blue-500 outline-none appearance-none cursor-pointer shadow-sm hover:border-blue-500 transition">
                    <option value="llibre">📖 Llibre</option>
                    <option value="autor">✍️ Autor</option>
                    <option value="editorial">🏢 Editorial</option>
                    <option value="tag">🏷️ Tag / Gènere</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-white">
                    <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                </div>
            </div>

            <div class="relative flex-grow">
                <div class="flex items-center bg-slate-800 rounded-xl border border-slate-700 focus-within:ring-2 focus-within:ring-blue-500/50 transition-all shadow-sm"
                     :class="inputError ? 'border-red-500 ring-2 ring-red-500 shake' : 'focus-within:border-blue-500'">
                    
                    <svg class="w-6 h-6 ml-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    
                    <input x-model="query" 
                           @input.debounce.500ms="type !== 'tag' ? performSearch() : null" 
                           @keydown.enter="type === 'tag' ? addTag() : performSearch()"
                           @input="inputError = false"
                           type="text" 
                           class="w-full bg-transparent border-none text-white text-xl placeholder-slate-500 px-4 py-4 focus:ring-0 focus:outline-none" 
                           :placeholder="placeholderText">
                    
                    {{-- Spinner de càrrega per a la validació --}}
                    <div x-show="validating" class="mr-4">
                        <div class="animate-spin rounded-full h-5 w-5 border-2 border-blue-500 border-t-transparent"></div>
                    </div>

                    <button x-show="type === 'tag' && query.length > 0 && !validating" 
                            @click="addTag()"
                            class="mr-2 bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-bold transition">
                        Afegir
                    </button>
                </div>

                {{-- Missatge d'error --}}
                <div x-show="inputError" x-cloak class="absolute -bottom-6 left-0 text-red-400 text-xs font-bold ml-2">
                    🚫 Tag no vàlid (No existeix com a Gènere, Autor o Editorial)
                </div>

                {{-- TAGS ACTIUS --}}
                <div x-show="type === 'tag' && tags.length > 0" class="flex flex-wrap gap-2 mt-4 animate-fade-in-down">
                    <template x-for="(tag, index) in tags" :key="index">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-600/20 text-blue-200 border border-blue-500/30">
                            <span x-text="tag"></span>
                            <button @click="removeTag(index)" class="ml-2 text-blue-400 hover:text-white focus:outline-none bg-blue-900/50 rounded-full w-5 h-5 flex items-center justify-center hover:bg-red-500 transition">×</button>
                        </span>
                    </template>
                </div>
            </div>
        </div>

        {{-- RESULTATS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 pb-20">
            <div x-show="loading" class="col-span-full text-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-slate-700 border-t-blue-500 mx-auto"></div>
                <p class="mt-4 text-slate-400 animate-pulse">Cercant...</p>
            </div>

            <template x-for="item in results" :key="item.id_llibre || item.id">
                <div class="contents">
                    {{-- CAS A: AUTOR/EDITORIAL --}}
                    <template x-if="item.llibres">
                        <div class="col-span-full bg-slate-800/50 rounded-2xl p-6 mb-4 border border-slate-700 hover:border-slate-600 transition">
                            <h2 class="text-2xl font-bold mb-4 text-white flex items-center gap-3">
                                <span class="bg-blue-600 w-2 h-8 rounded-full"></span>
                                <span x-text="item.nom"></span>
                                <span class="text-xs bg-slate-700 text-slate-300 px-3 py-1 rounded-full border border-slate-600 font-normal">Resultat Exacte</span>
                            </h2>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                <template x-for="llibre in item.llibres" :key="llibre.id_llibre">
                                    <a :href="'/llibre/' + llibre.id_llibre" class="group block bg-slate-900 rounded-xl p-3 hover:bg-slate-800 transition shadow-md hover:shadow-xl hover:-translate-y-1">
                                        {{-- ⚠️ CORREGIT: Ús de /img/ en lloc de /storage/ --}}
                                        <div class="aspect-[2/3] w-full overflow-hidden rounded-lg mb-3 bg-slate-800">
                                            <img :src="llibre.img_portada ? '/img/' + llibre.img_portada : 'https://placehold.co/400x600?text=No+Img'" 
                                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                        </div>
                                        <p class="text-sm font-bold text-white truncate group-hover:text-blue-400 transition" x-text="llibre.titol"></p>
                                        <p class="text-xs mt-1 font-bold flex items-center gap-1" :class="(llibre.ressenyes_avg_puntuacio > 0) ? 'text-yellow-500' : 'text-slate-600'">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            <span x-text="(llibre.ressenyes_avg_puntuacio > 0) ? parseFloat(llibre.ressenyes_avg_puntuacio).toFixed(1) : '-'"></span>
                                        </p>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>

                    {{-- CAS B: LLIBRE INDIVIDUAL --}}
                    <template x-if="!item.llibres">
                        <div class="group relative bg-slate-800 border border-slate-700 rounded-2xl overflow-hidden hover:shadow-2xl hover:border-slate-600 hover:-translate-y-2 transition duration-300">
                            <a :href="'/llibre/' + (item.id_llibre || item.id)" class="block h-full flex flex-col">
                                <div class="aspect-[2/3] w-full bg-slate-700 overflow-hidden relative">
                                    {{-- ⚠️ CORREGIT: Ús de /img/ en lloc de /storage/ --}}
                                    <img :src="item.img_portada ? '/img/' + item.img_portada : 'https://placehold.co/400x600?text=No+Img'" 
                                         class="object-cover w-full h-full opacity-90 group-hover:opacity-100 group-hover:scale-110 transition duration-500">
                                    
                                    {{-- Nota flotant --}}
                                    <div class="absolute top-2 right-2 backdrop-blur text-xs font-bold px-2 py-1 rounded-full shadow-lg flex items-center gap-1 border border-slate-700"
                                         :class="(item.ressenyes_avg_puntuacio > 0) ? 'bg-slate-900/90 text-white' : 'bg-slate-800/80 text-slate-400'">
                                        <svg class="w-3 h-3" :class="(item.ressenyes_avg_puntuacio > 0) ? 'text-yellow-500' : 'text-slate-600'" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <span x-text="(item.ressenyes_avg_puntuacio > 0) ? parseFloat(item.ressenyes_avg_puntuacio).toFixed(1) : '-'"></span>
                                    </div>
                                </div>
                                <div class="p-5 flex flex-col flex-grow">
                                    <h3 class="font-bold text-lg leading-tight mb-1 text-white group-hover:text-blue-400 transition line-clamp-2" x-text="item.titol"></h3>
                                    <p class="text-slate-400 text-sm font-medium mb-4" x-text="item.autor ? item.autor.nom : 'Autor desconegut'"></p>
                                    <div class="mt-auto flex items-center justify-between border-t border-slate-700 pt-4">
                                        <span class="text-white font-extrabold text-lg" x-text="parseFloat(item.preu).toFixed(2) + ' €'"></span>
                                        <span x-show="item.genere" class="text-[10px] uppercase tracking-wider bg-slate-700 px-2 py-1 rounded text-slate-300 font-bold" x-text="item.genere"></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </template>
                </div>
            </template>

            <div x-show="!loading && results.length === 0 && (query.length > 1 || tags.length > 0)" class="col-span-full text-center py-20">
                <div class="text-6xl mb-4">🔍</div>
                <p class="text-xl text-slate-300 font-bold">No s'han trobat resultats</p>
                <p class="text-slate-500 mt-2">Prova amb altres paraules o tags.</p>
            </div>
        </div>
    </div>

    <script>
        function cercaApp() {
            return {
                query: '',
                type: 'llibre',
                results: [],
                tags: [],
                loading: false,
                validating: false, // Estat de validació
                inputError: false, // Per mostrar error visual

                get placeholderText() {
                    if (this.type === 'llibre') return 'Escriu el títol del llibre...';
                    if (this.type === 'autor') return 'Busca un autor...';
                    if (this.type === 'editorial') return 'Busca una editorial...';
                    return 'Escriu Gènere, Autor o Editorial i prem Enter...';
                },

                // ⚡ INIT: LLEGIM URL PER RESTAURAR CERCA
                init() {
                    const params = new URLSearchParams(window.location.search);
                    if (params.has('q')) this.query = params.get('q');
                    if (params.has('type')) this.type = params.get('type');
                    
                    if (this.query.length > 1 || this.type === 'tag') {
                        if(this.query) this.performSearch();
                    }
                },

                // ✅ 1. FUNCIÓ VALIDAR I AFEGIR TAG (Nova)
                async addTag() {
                    const text = this.query.trim();
                    if (text === '' || this.tags.includes(text)) return;

                    this.validating = true;
                    this.inputError = false;

                    try {
                        // Cridem al backend per veure si existeix a la BD
                        const response = await fetch(`/api/validar-tag?tag=${encodeURIComponent(text)}`);
                        const data = await response.json();

                        if (data.valid) {
                            this.tags.push(text);
                            this.query = '';
                            this.performSearch();
                        } else {
                            // ❌ NO ÉS VÀLID
                            this.inputError = true;
                            setTimeout(() => this.inputError = false, 2000);
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

                // ⚡ UPDATE URL
                updateUrl() {
                    const url = new URL(window.location);
                    if(this.query) url.searchParams.set('q', this.query);
                    else url.searchParams.delete('q');
                    
                    url.searchParams.set('type', this.type);
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
                            type: this.type
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