<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('BookCores - La teva llibreria') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="antialiased bg-slate-50 text-slate-800">

    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">

                <div class="flex-shrink-0 flex items-center gap-2">
                    <a href="/" class="font-serif text-2xl font-bold text-slate-900 flex items-center gap-2">
                        <svg class="h-8 w-8 drop-shadow-sm" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        <span>
                            Book<span class="text-blue-900">Cores</span>
                        </span>
                    </a>
                </div>

                <div class="flex items-center space-x-6">

                    <form action="{{ route('home') }}" method="GET" class="hidden sm:flex items-center">
                        <label for="lang" class="sr-only">{{ __('Idioma') }}</label>
                        <select name="lang" id="lang" onchange="this.form.submit()" class="bg-transparent text-sm font-medium text-slate-600 focus:ring-blue-500 border-none cursor-pointer hover:text-blue-900 outline-none">
                            <option value="ca" {{ app()->getLocale() == 'ca' ? 'selected' : '' }}>CA</option>
                            <option value="es" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>ES</option>
                            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
                            <option value="ja" {{ app()->getLocale() == 'ja' ? 'selected' : '' }}>JA</option>
                        </select>
                    </form>

                    <div class="hidden sm:block h-6 w-px bg-slate-300"></div>
                    <nav class="flex space-x-4 items-center">
                        @if (Route::has('login'))
                        @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-blue-900 transition">
                            {{ __('Dashboard') }} </a>
                        @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-blue-900 transition">
                            {{ __('Inicia sessió') }} </a>

                        @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-sm font-semibold bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-800 transition shadow-sm shadow-blue-200">
                            {{ __('Registra\'t') }} </a>
                        @endif
                        @endauth
                        @endif
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="relative bg-white overflow-hidden">
            <div class="max-w-7xl mx-auto">
                <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32 pt-20 px-4 sm:px-6 lg:px-8">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-slate-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">{{ __('Troba la teva pròxima') }}</span>
                            <span class="block text-blue-900 xl:inline">{{ __('història preferida') }}</span>
                        </h1>
                        <p class="mt-3 text-base text-slate-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            {{ __('Explora el nostre catàleg, llegeix ressenyes de la comunitat i troba les millors històries seleccionades per a tu a BookCores.') }}
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="#novetats" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-900 md:py-4 md:text-lg transition">
                                    {{ __('Veure catàleg') }} </a>
                            </div>
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 md:py-4 md:text-lg transition">
                                    {{ __('Saber-ne més') }} </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-blue-50 flex items-center justify-center">
                <svg class="w-1/2 h-1/2 text-blue-200 opacity-50" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 1H5c-1.1 0-2 .9-2 2v18c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V3c0-1.1-.9-2-2-2zm-7 18.5c-2.5 0-4.5-2-4.5-4.5s2-4.5 4.5-4.5 4.5 2 4.5 4.5-2 4.5-4.5 4.5zm5-8H7V4h10v7.5z" />
                </svg>
            </div>
        </div>

        <div id="novetats" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 bg-slate-50">
            <div class="flex justify-between items-end mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">{{ __('Els més ben valorats ⭐') }}</h2>
                    <p class="text-slate-500 mt-1">{{ __('Llibres amb les millors ressenyes de la comunitat') }}</p>
                </div>
                <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-800">{{ __('Veure tot') }} &rarr;</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                @forelse ($llibres as $llibre)
                <div class="group relative bg-white border border-slate-200 rounded-xl p-4 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">

                    <div class="aspect-w-2 aspect-h-3 w-full overflow-hidden rounded-lg bg-slate-200 mb-4 relative shadow-inner">
                        @if($llibre->img_portada)
                        <img src="{{ asset('img/' . $llibre->img_portada) }}" alt="{{ $llibre->titol }}" class="object-cover w-full h-64 group-hover:scale-105 transition duration-500 ease-in-out" onerror="this.src='https://placehold.co/400x600?text=Sense+Portada'">
                        @else
                        <div class="flex items-center justify-center h-64 bg-slate-100 text-slate-400">
                            <span class="text-4xl">📖</span>
                        </div>
                        @endif

                        <span class="absolute top-2 right-2 bg-white/90 backdrop-blur text-slate-900 text-xs font-bold px-2 py-1 rounded-full shadow-sm flex items-center gap-1">
                            <svg class="w-3 h-3 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            {{ number_format($llibre->nota_promig, 1) }}
                        </span>

                        @if($llibre->genere)
                        <span class="absolute bottom-2 left-2 bg-slate-900/70 text-white text-[10px] uppercase tracking-wider font-semibold px-2 py-1 rounded">
                            {{ $llibre->genere }} </span>
                        @endif
                    </div>

                    <div class="flex flex-col flex-grow">
                        <h3 class="text-base font-bold text-slate-900 line-clamp-2 leading-tight group-hover:text-blue-700 transition-colors">
                            <a href="#">
                                <span aria-hidden="true" class="absolute inset-0"></span>
                                {{ $llibre->titol }} </a>
                        </h3>

                        <p class="text-sm text-slate-500 mt-1 font-medium">
                            {{ $llibre->autor ? $llibre->autor->nom : __('Autor Desconegut') }} </p>

                        <div class="mt-auto pt-4 flex items-center justify-between">
                            <p class="text-lg font-extrabold text-slate-900">
                                {{ number_format($llibre->preu, 2, ',', '.') }} €
                            </p>
                            <button class="relative z-10 p-2 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-colors duration-200" title="{{ __('Afegir al carretó') }}"> <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full flex flex-col items-center justify-center py-16 text-center bg-white border border-dashed border-slate-300 rounded-lg">
                    <span class="text-4xl mb-2">📚</span>
                    <h3 class="text-lg font-medium text-slate-900">{{ __('Encara no hi ha llibres') }}</h3>
                    <p class="text-slate-500 max-w-sm mx-auto mt-1">{{ __('Sembla que la base de dades està buida. Executa els seeders per veure contingut aquí.') }}</p>
                </div>
                @endforelse

            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-slate-200 mt-12">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">

            <div class="flex items-center gap-2">
                <span class="text-2xl">📚</span>
                <span class="font-serif font-bold text-xl text-slate-900">BookCores</span>
            </div>

            <div class="flex gap-6 text-sm text-slate-500">
                <a href="#" class="hover:text-blue-900 transition">{{ __('Sobre nosaltres') }}</a>
                <a href="#" class="hover:text-blue-900 transition">{{ __('Contacte') }}</a>
                <a href="#" class="hover:text-blue-900 transition">{{ __('Política de Privacitat') }}</a>
            </div>

            <p class="text-sm text-slate-400">
                &copy; {{ date('Y') }} BookCores. {{ __('Tots els drets reservats.') }}
            </p>
        </div>
    </footer>

</body>

</html>