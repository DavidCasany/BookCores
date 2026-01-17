<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cerca - BookCores</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-slate-900 text-white min-h-screen font-sans">

    {{-- Botó de tancar / tornar --}}
    <a href="{{ route('home') }}" class="fixed top-6 right-6 z-50 p-2 bg-slate-800 rounded-full hover:bg-slate-700 transition">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </a>

    <div x-data="cercaApp()" x-init="$watch('query', value => performSearch()); $watch('type', () => { query=''; results=[]; tags=[]; });" class="max-w-5xl mx-auto px-4 pt-24">

        {{-- BARRA DE CERCA --}}
        <div class="flex flex-col md:flex-row gap-4 mb-12 animate-fade-in-down">
            
            {{-- Desplegable --}}
            <select x-model="type" class="bg-slate-800 text-white border-none rounded-xl py-4 px-6 text-lg font-bold focus:ring-2 focus:ring-blue-500 outline-none appearance-none cursor-pointer">
                <option value="llibre">📖 Llibre</option>
                <option value="autor">✍️ Autor</option>
                <option value="editorial">🏢 Editorial</option>
                <option value="tag">🏷️ Tag / Gènere</option>
            </select>

            {{-- Input i Tags --}}
            <div class="relative flex-grow">
                <div class="flex items-center bg-slate-800 rounded-xl border border-slate-700 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-500/50 transition-all">
                    <svg class="w-6 h-6 ml-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    
                    <input x-model="query" 
                           @keydown.enter="type === 'tag' ? addTag() : null"
                           type="text" 
                           class="w-full bg-transparent border-none text-white text-xl placeholder-slate-500 px-4 py-4 focus:ring-0" 
                           :placeholder="placeholderText">
                    
                    {{-- Botó Afegir Tag (Només visible en mode Tag) --}}
                    <button x-show="type === 'tag' && query.length > 0" 
                            @click="addTag()"
                            class="mr-2 bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-bold transition">
                        Afegir
                    </button>
                </div>

                {{-- Zona de Tags Actius --}}
                <div x-show="type === 'tag' && tags.length > 0" class="flex flex-wrap gap-2 mt-4">
                    <template x-for="(tag, index) in tags" :key="index">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-900 text-blue-200 border border-blue-700">
                            <span x-text="tag"></span>
                            <button @click="removeTag(index)" class="ml-2 text-blue-400 hover:text-white focus:outline-none">×</button>
                        </span>
                    </template>
                </div>
            </div>
        </div>

        {{-- RESULTATS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 pb-20">
            
            {{-- LOADING --}}
            <div x-show="loading" class="col-span-full text-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white mx-auto"></div>
            </div>

            {{-- LOOP RESULTATS --}}
            <template x-for="item in results" :key="item.id_llibre || item.id">
                
                {{-- LÒGICA INTEL·LIGENT: --}}
                {{-- Si l'item té propietat 'llibres', és un Autor o Editorial --}}
                {{-- Si no, és un llibre normal --}}

                <div class="contents">
                    {{-- OPCIÓ A: És un AUTOR o EDITORIAL (té llista de llibres) --}}
                    <template x-if="item.llibres">
                        <div class="col-span-full bg-slate-800 rounded-2xl p-6 mb-4 border border-slate-700">
                            <h2 class="text-2xl font-bold mb-4 text-blue-400 flex items-center gap-2">
                                <span x-text="item.nom"></span>
                                <span class="text-xs bg-slate-900 text-slate-400 px-2 py-1 rounded-full border border-slate-600">Resultat Exacte</span>
                            </h2>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                <template x-for="llibre in item.llibres" :key="llibre.id_llibre">
                                    <a :href="'/llibre/' + llibre.id_llibre" class="block bg-slate-900 rounded-lg p-2 hover:bg-slate-700 transition">
                                        <img :src="llibre.img_portada ? '/storage/' + llibre.img_portada : 'https://placehold.co/400x600'" class="w-full h-32 object-cover rounded mb-2">
                                        <p class="text-sm font-bold truncate" x-text="llibre.titol"></p>
                                        <p class="text-xs text-yellow-500">★ <span x-text="llibre.nota_promig"></span></p>
                                    </a>
                                </template>
                                <div x-show="item.llibres.length === 0" class="text-slate-500 text-sm p-4">
                                    Aquest autor/editorial existeix però no té llibres registrats encara.
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- OPCIÓ B: És un LLIBRE INDIVIDUAL --}}
                    <template x-if="!item.llibres">
                        <div class="group relative bg-slate-800 rounded-lg overflow-hidden hover:scale-105 transition duration-300">
                            <a :href="'/llibres/' + (item.id_llibre || item.id)" class="block">
                                <div class="aspect-w-2 aspect-h-3 w-full bg-slate-700">
                                    <img :src="item.img_portada ? '/storage/' + item.img_portada : 'https://placehold.co/400x600?text=No+Img'" 
                                         class="object-cover w-full h-64 opacity-80 group-hover:opacity-100 transition">
                                </div>
                                <div class="p-4">
                                    <h3 class="font-bold text-lg leading-tight mb-1" x-text="item.titol"></h3>
                                    <p class="text-slate-400 text-sm" x-text="item.autor ? item.autor.nom : 'Autor desconegut'"></p>
                                    <div class="mt-2 flex items-center justify-between">
                                        <span class="text-yellow-400 font-bold text-sm">★ <span x-text="parseFloat(item.nota_promig || 0).toFixed(1)"></span></span>
                                        <span x-show="item.genere" class="text-xs bg-slate-700 px-2 py-1 rounded text-slate-300" x-text="item.genere"></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </template>
                </div>

            </template>

            <div x-show="!loading && results.length === 0 && query.length > 1" class="col-span-full text-center text-slate-500 py-12">
                No s'han trobat resultats per "<span x-text="query"></span>" 🐢
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
                
                get placeholderText() {
                    if (this.type === 'llibre') return 'Escriu el títol del llibre...';
                    if (this.type === 'autor') return 'Busca un autor...';
                    if (this.type === 'editorial') return 'Busca una editorial...';
                    return 'Escriu un gènere i prem Enter...';
                },

                addTag() {
                    if (this.query.trim() !== '' && !this.tags.includes(this.query.trim())) {
                        this.tags.push(this.query.trim());
                        this.query = ''; 
                        this.performSearch(); 
                    }
                },

                removeTag(index) {
                    this.tags.splice(index, 1);
                    this.performSearch();
                },

                async performSearch() {
                    if (this.query.length < 2 && this.tags.length === 0) {
                        this.results = [];
                        return;
                    }

                    this.loading = true;

                    try {
                        const params = new URLSearchParams({
                            q: this.query,
                            type: this.type
                        });

                        this.tags.forEach(t => params.append('tags[]', t));

                        const response = await fetch(`/api/cerca?${params.toString()}`);
                        this.results = await response.json();
                        
                        // DEBUG: Descomenta si vols veure què arriba exactament
                        // console.log("Resultats:", this.results);

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