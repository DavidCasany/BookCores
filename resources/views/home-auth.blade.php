<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('BookCores - Panell') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 dark:bg-slate-900 text-slate-800 dark:text-slate-100 font-sans antialiased">

    {{-- BARRA DE NAVEGACIÓ SUPERIOR (Estil Dashboard) --}}
    @include('layouts.navigation')

    {{-- CARRUSEL SUPERIOR (50% ALÇADA) --}}
    <div class="relative w-full h-[50vh] bg-slate-800 overflow-hidden shadow-xl" 
         x-data="{ 
             activeSlide: 0, 
             slides: {{ json_encode($llibresRecents->map(fn($l) => ['img' => $l->img_hero ? asset('img/'.$l->img_hero) : ($l->img_portada ? asset('img/'.$l->img_portada) : null), 'titol' => $l->titol])) }},
             init() { setInterval(() => { this.activeSlide = (this.activeSlide + 1) % this.slides.length }, 5000); }
         }">
        
        <template x-for="(slide, index) in slides" :key="index">
            <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
                 :class="activeSlide === index ? 'opacity-100' : 'opacity-0'">
                <img :src="slide.img" class="w-full h-full object-cover opacity-60">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
                
                <div class="absolute bottom-0 left-0 w-full p-8 md:p-12">
                    <div class="max-w-7xl mx-auto">
                        <span class="inline-block py-1 px-3 rounded-full bg-blue-600 text-white text-xs font-bold uppercase tracking-wider mb-2">Novetat</span>
                        <h2 class="text-3xl md:text-5xl font-black text-white drop-shadow-lg" x-text="slide.titol"></h2>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- CONTINGUT PRINCIPAL (Llibres i Categories) --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <div class="flex justify-between items-end mb-8">
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Explora la col·lecció') }}</h3>
            {{-- Aquí podries posar filtres de categories en el futur --}}
        </div>

        {{-- Graella de Llibres --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach($llibres as $llibre)
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group border border-slate-200 dark:border-slate-700">
                    <a href="{{ route('llibres.show', $llibre->id_llibre) }}" class="block relative aspect-[2/3] overflow-hidden">
                        @if($llibre->img_portada)
                            <img src="{{ asset('img/' . $llibre->img_portada) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-slate-200 dark:bg-slate-700 text-4xl">📘</div>
                        @endif
                        <div class="absolute top-2 right-2 bg-white/90 dark:bg-slate-900/90 backdrop-blur px-2 py-1 rounded-md text-xs font-bold shadow-sm">
                            ⭐ {{ number_format($llibre->nota_promig, 1) }}
                        </div>
                    </a>
                    <div class="p-4">
                        <h4 class="font-bold text-lg text-slate-900 dark:text-white leading-tight mb-1 truncate">{{ $llibre->titol }}</h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">{{ $llibre->autor->nom ?? 'Autor desconegut' }}</p>
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-blue-600 dark:text-blue-400">{{ number_format($llibre->preu, 2) }} €</span>
                            <button class="text-slate-400 hover:text-blue-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</body>
</html>