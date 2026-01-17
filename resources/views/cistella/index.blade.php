<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>La teva Cistella - BookCores</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>
</head>
<body class="bg-slate-900 text-white font-sans min-h-screen">

    {{-- Header simple per tornar --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex justify-between items-center">
        <a href="{{ route('home') }}" class="flex items-center text-blue-400 hover:text-blue-300 transition">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Tornar a la botiga
        </a>
        <h1 class="text-2xl font-bold">La teva Cistella 🛒</h1>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        @if(!$cistella || $cistella->llibres->isEmpty())
            {{-- Cistella Buida --}}
            <div class="text-center py-20 bg-slate-800 rounded-3xl border border-slate-700">
                <div class="text-6xl mb-4">😢</div>
                <h2 class="text-2xl font-bold mb-2">La teva cistella està buida</h2>
                <p class="text-slate-400 mb-6">Sembla que encara no has afegit cap llibre.</p>
                <a href="{{ route('home') }}" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-8 rounded-full transition">Explorar Llibres</a>
            </div>
        @else
            {{-- Taula de Productes --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Llista d'Items --}}
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cistella->llibres as $llibre)
                    <div class="relative flex items-center bg-slate-800 p-4 rounded-xl border border-slate-700 group hover:border-blue-500/50 transition">
                        
                        {{-- 1. BOTÓ ELIMINAR (CREU) --}}
                        <form action="{{ route('cistella.eliminar', $llibre->id_llibre) }}" method="POST" class="absolute top-2 right-2 z-20">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-slate-500 hover:text-red-500 transition p-1 rounded-full hover:bg-slate-700" title="Eliminar llibre">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </form>

                        {{-- 2. ENLLAÇ AL DETALL (Imatge i Info) --}}
                        <a href="{{ route('llibres.show', $llibre->id_llibre) }}" class="flex items-center flex-grow mr-4 z-10">
                            {{-- Imatge --}}
                            <div class="w-20 h-28 flex-shrink-0 bg-slate-700 rounded overflow-hidden shadow-lg">
                                <img src="{{ $llibre->img_portada ? asset('storage/' . $llibre->img_portada) : 'https://placehold.co/400x600' }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            </div>
                            
                            {{-- Info --}}
                            <div class="ml-4 flex-grow pr-8">
                                <h3 class="font-bold text-lg text-white hover:text-blue-400 transition">{{ $llibre->titol }}</h3>
                                <p class="text-slate-400 text-sm">{{ $llibre->autor ? $llibre->autor->nom : 'Autor desconegut' }}</p>
                                <p class="text-blue-400 font-bold mt-1">{{ number_format($llibre->pivot->preu_unitari, 2) }} €</p>
                            </div>
                        </a>

                        {{-- 3. CONTROLS DE QUANTITAT --}}
                        <div class="flex flex-col items-center gap-1 bg-slate-900 p-1 rounded-lg border border-slate-600 z-20">
                            
                            {{-- Fletxa Amunt (+) --}}
                            <form action="{{ route('cistella.actualitzar', $llibre->id_llibre) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="quantitat" value="{{ $llibre->pivot->quantitat + 1 }}">
                                <button type="submit" class="p-1 text-slate-400 hover:text-white hover:bg-slate-700 rounded transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                </button>
                            </form>

                            <span class="font-bold text-sm min-w-[20px] text-center select-none">{{ $llibre->pivot->quantitat }}</span>

                            {{-- Fletxa Avall (-) --}}
                            <form action="{{ route('cistella.actualitzar', $llibre->id_llibre) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="quantitat" value="{{ $llibre->pivot->quantitat - 1 }}">
                                <button type="submit" class="p-1 text-slate-400 hover:text-white hover:bg-slate-700 rounded transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                            </form>
                        </div>

                    </div>
                    @endforeach
                </div>

                {{-- Resum Pagament --}}
                <div class="lg:col-span-1">
                    <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 sticky top-10">
                        <h3 class="text-xl font-bold mb-6 border-b border-slate-700 pb-4">Resum de la comanda</h3>
                        
                        <div class="flex justify-between mb-2 text-slate-400">
                            <span>Subtotal</span>
                            <span>{{ number_format($cistella->total, 2) }} €</span>
                        </div>
                        <div class="flex justify-between mb-6 text-slate-400">
                            <span>Impostos (inclosos)</span>
                            <span>0.00 €</span>
                        </div>

                        <div class="flex justify-between items-center text-2xl font-bold text-white mb-8 border-t border-slate-700 pt-4">
                            <span>Total</span>
                            <span>{{ number_format($cistella->total, 2) }} €</span>
                        </div>

                        {{-- BOTÓ DE PAGAMENT CONNECTAT A STRIPE --}}
                        <form action="{{ route('pagament.checkout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 rounded-xl transition shadow-lg shadow-blue-900/50 flex justify-center items-center gap-2 group">
                                <span>Pagar ara</span>
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
</html>