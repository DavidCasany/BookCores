<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $llibre->titol }} - BookCores</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="antialiased bg-slate-50 text-slate-800">

    {{-- HEADER (Versió fixa 'blanca' per veure's bé sobre el fons gris) --}}
    <header class="fixed w-full z-50 bg-white/95 backdrop-blur-md shadow-sm border-b border-gray-200 py-3">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                {{-- LOGO --}}
                <div class="flex-shrink-0 flex items-center gap-2">
                    <a href="/" class="font-serif text-2xl font-bold flex items-center gap-2 text-slate-900 transition-colors hover:opacity-80">
                        <svg class="h-8 w-8 drop-shadow-sm text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        <span>Book<span class="text-blue-600">Cores</span></span>
                    </a>
                </div>

                {{-- NAV --}}
                <div class="flex items-center space-x-6">
                    <nav class="flex space-x-4 items-center">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-slate-600 hover:text-blue-900 transition-colors">{{ __('Panell') }}</a>
                                {{-- Botó Logout ràpid opcional --}}
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm font-bold text-red-500 hover:text-red-700 transition-colors ml-2">{{ __('Sortir') }}</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-blue-900 transition-colors">{{ __('Inicia sessió') }}</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="text-sm font-bold px-5 py-2.5 rounded-full bg-blue-600 text-white hover:bg-blue-700 transition shadow-md">{{ __('Registra\'t') }}</a>
                                @endif
                            @endauth
                        @endif
                    </nav>
                </div>
            </div>
        </div>
    </header>

    {{-- CONTINGUT PRINCIPAL --}}
    <main class="pt-28 pb-16 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- BOTÓ TORNAR --}}
            <a href="/#novetats" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-blue-600 mb-8 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ __('Tornar al catàleg') }}
            </a>

            <div class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border border-slate-100">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-0">
                    
                    {{-- COLUMNA ESQUERRA: PORTADA --}}
                    <div class="md:col-span-5 bg-slate-100/50 p-8 md:p-12 flex items-center justify-center relative">
                        <div class="relative group perspective-1000">
                            {{-- Ombra decorativa --}}
                            <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                            
                            {{-- Imatge --}}
                            <img src="{{ asset('storage/' . $llibre->fitxer_portada) }}" 
                                 alt="{{ $llibre->titol }}" 
                                 class="relative w-64 md:w-80 h-auto rounded-lg shadow-[0_35px_60px_-15px_rgba(0,0,0,0.3)] transform transition duration-500 hover:scale-[1.02] hover:rotate-1 z-10 object-cover"
                                 onerror="this.src='https://placehold.co/400x600?text=Sense+Portada'">
                            
                            {{-- Etiqueta de nota flotant --}}
                            <div class="absolute -top-4 -right-4 z-20 bg-white px-4 py-2 rounded-full shadow-lg flex items-center gap-1 border border-slate-100">
                                <span class="text-yellow-500 text-xl">★</span>
                                <span class="font-bold text-slate-900 text-lg">{{ $llibre->nota_promig ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- COLUMNA DRETA: INFORMACIÓ --}}
                    <div class="md:col-span-7 p-8 md:p-12 flex flex-col justify-center">
                        <div class="mb-6">
                            @if($llibre->genere)
                                <span class="text-blue-600 font-bold tracking-wider uppercase text-xs bg-blue-50 px-3 py-1 rounded-full">{{ $llibre->genere }}</span>
                            @endif
                            <h1 class="mt-4 text-4xl md:text-5xl font-black text-slate-900 leading-tight mb-2">{{ $llibre->titol }}</h1>
                            <p class="text-xl text-slate-500 font-medium">per <span class="text-slate-800">{{ $llibre->autor ? $llibre->autor->nom : 'Autor Desconegut' }}</span></p>
                        </div>

                        <div class="prose prose-slate text-slate-600 mb-8 leading-relaxed">
                            {{ $llibre->descripcio }}
                        </div>

                        <div class="flex items-center gap-4 mb-8">
                            <div class="px-4 py-2 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="block text-xs text-slate-400 uppercase font-bold">{{ __('Editorial') }}</span>
                                <span class="font-semibold text-slate-700">{{ $llibre->editorial ? $llibre->editorial->nom : '-' }}</span>
                            </div>
                            <div class="px-4 py-2 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="block text-xs text-slate-400 uppercase font-bold">{{ __('Pàgines') }}</span>
                                <span class="font-semibold text-slate-700">324</span> {{-- Dada simulada o real --}}
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-8 mt-auto flex flex-col sm:flex-row items-center justify-between gap-6">
                            <div>
                                <span class="block text-sm text-slate-400 font-medium mb-1">{{ __('Preu total') }}</span>
                                <span class="text-4xl font-black text-slate-900">{{ number_format($llibre->preu, 2, ',', '.') }}€</span>
                            </div>

                            @auth
                                <form action="{{ route('cistella.afegir', $llibre->id_llibre) }}" method="POST" class="w-full sm:w-auto">
                                    @csrf
                                    <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                        {{ __('Afegir a la Cistella') }}
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-slate-900 text-white font-bold rounded-xl shadow-lg hover:bg-slate-800 hover:-translate-y-1 transition-all duration-300 text-center">
                                    {{ __('Inicia sessió per comprar') }}
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECCIÓ RESSENYES --}}
            <div class="mt-16 grid grid-cols-1 lg:grid-cols-3 gap-12">
                {{-- Columna esquerra: Resum i Formulari --}}
                <div class="lg:col-span-1">
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">{{ __('Opinions dels lectors') }}</h3>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 mb-6">
                        <div class="text-center">
                            <span class="block text-5xl font-black text-slate-900">{{ number_format($llibre->nota_promig, 1) }}</span>
                            <div class="flex justify-center gap-1 text-yellow-400 my-2">
                                @for($i=0; $i<5; $i++)
                                    <span>{{ $i < round($llibre->nota_promig) ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                            <span class="text-sm text-slate-400">{{ $llibre->ressenyas->count() }} {{ __('valoracions') }}</span>
                        </div>
                    </div>

                    @auth
                        <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100">
                            <h4 class="font-bold text-blue-900 mb-2">{{ __('Escriu la teva ressenya') }}</h4>
                            <form action="#" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="block text-xs font-bold text-blue-700 uppercase mb-1">{{ __('Puntuació') }}</label>
                                    <select name="puntuacio" class="w-full rounded-lg border-blue-200 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <option value="5">★★★★★ - Excel·lent</option>
                                        <option value="4">★★★★☆ - Molt bo</option>
                                        <option value="3">★★★☆☆ - Correcte</option>
                                        <option value="2">★★☆☆☆ - Regular</option>
                                        <option value="1">★☆☆☆☆ - Dolent</option>
                                    </select>
                                </div>
                                <textarea name="text" class="w-full border-blue-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm mb-3" rows="3" placeholder="Què t'ha semblat el llibre?"></textarea>
                                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg font-bold text-sm hover:bg-blue-700 transition">{{ __('Publicar') }}</button>
                            </form>
                        </div>
                    @else
                        <div class="bg-slate-100 p-6 rounded-2xl text-center">
                            <p class="text-slate-500 text-sm mb-3">{{ __('Vols compartir la teva opinió?') }}</p>
                            <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline text-sm">{{ __('Accedeix per comentar') }}</a>
                        </div>
                    @endauth
                </div>

                {{-- Columna dreta: Llista de comentaris --}}
                <div class="lg:col-span-2 space-y-6">
                    @forelse($llibre->ressenyas as $ressenya)
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($ressenya->user->name, 0, 1) }}
                                </div>
                            </div>
                            <div class="flex-grow">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h5 class="font-bold text-slate-900">{{ $ressenya->user->name }}</h5>
                                        <span class="text-xs text-slate-400">{{ $ressenya->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="flex text-yellow-400 text-sm">
                                        @for($i=0; $i<5; $i++)
                                            <span>{{ $i < $ressenya->puntuacio ? '★' : '☆' }}</span>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-slate-600 leading-relaxed">{{ $ressenya->text }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-slate-200">
                            <span class="text-4xl opacity-50">💭</span>
                            <p class="text-slate-500 mt-2">{{ __('Encara no hi ha ressenyes. Sigues el primer en opinar!') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </main>

    {{-- FOOTER IDENTIC A LA HOME --}}
    <footer class="bg-white border-t border-slate-200 mt-12">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-slate-50 rounded-lg"><svg class="h-6 w-6 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg></div>
                <span class="font-serif font-bold text-xl text-slate-900">BookCores</span>
            </div>
            <p class="text-sm text-slate-400">&copy; {{ date('Y') }} BookCores. {{ __('Tots els drets reservats.') }}</p>
        </div>
    </footer>

</body>
</html>