<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>La meva Biblioteca - BookCores</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>
</head>

<body class="bg-slate-900 text-white font-sans min-h-screen">

    {{-- Header simple --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex justify-between items-center border-b border-slate-800">
        <a href="{{ route('home') }}" class="flex items-center text-slate-400 hover:text-white transition gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Tornar a l'inici</span>
        </a>
        <h1 class="text-2xl font-bold tracking-tight">📚 La meva Biblioteca</h1>
        <div class="w-20"></div> {{-- Espai buit per equilibrar --}}
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        @if($llibres->isEmpty())
        <div class="text-center py-20">
            <div class="text-6xl mb-6 opacity-50">🕸️</div>
            <h2 class="text-2xl font-bold mb-2">La teva biblioteca està buida</h2>
            <p class="text-slate-400 mb-8">Sembla que encara no has comprat cap llibre.</p>
            <a href="{{ route('home') }}" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-8 rounded-full transition">Explorar llibres</a>
        </div>
        @else
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
            @foreach($llibres as $llibre)
            <div class="group relative">
                {{-- Portada Llibre --}}
                <div class="aspect-[2/3] w-full overflow-hidden rounded-xl bg-slate-800 relative shadow-lg group-hover:shadow-blue-500/20 group-hover:-translate-y-2 transition-all duration-300">
                    @if($llibre->img_portada)
                    <img src="{{ asset('img/' . $llibre->img_portada) }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-4xl">📘</div>
                    @endif

                    {{-- Overlay al passar el ratolí --}}
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-3">
                        <a href="{{ route('llibres.show', $llibre->id_llibre) }}" class="px-4 py-2 bg-white text-slate-900 rounded-full font-bold text-sm hover:bg-slate-200 transition">
                            Veure Detalls
                        </a>
                        {{-- Aquí aniria el botó de LLEGIR PDF en el futur --}}
                        <a href="{{ route('llibre.llegir', $llibre->id_llibre) }}" target="_blank"
                            class="px-4 py-2 bg-blue-600 text-white rounded-full font-bold text-sm hover:bg-blue-500 transition shadow-lg hover:shadow-blue-500/50 flex items-center gap-2">
                            <span>Llegir Ara</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Títol i Autor --}}
                <div class="mt-3">
                    <h3 class="font-bold text-white truncate">{{ $llibre->titol }}</h3>
                    <p class="text-sm text-slate-400 truncate">{{ $llibre->autor ? $llibre->autor->nom : 'Autor desconegut' }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</body>

</html>