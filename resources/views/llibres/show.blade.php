<x-guest-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        {{-- Si tens la imatge a storage, usa asset('storage/...') --}}
                        <img src="{{ asset('storage/' . $llibre->fitxer_portada) }}" 
                             alt="{{ $llibre->titol }}" 
                             class="w-full h-auto rounded-lg shadow-md">
                    </div>

                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $llibre->titol }}</h1>
                        <p class="text-xl text-gray-600 mt-2">{{ $llibre->autor->nom }}</p>
                        <p class="text-sm text-gray-500 mb-4">Editorial: {{ $llibre->editorial->nom }}</p>
                        
                        <div class="text-2xl font-bold text-blue-600 mb-6">
                            {{ $llibre->preu }} €
                        </div>

                        <p class="text-gray-700 mb-6">
                            {{ $llibre->descripcio }}
                        </p>

                        @auth
                            {{-- L'usuari està registrat --}}
                            <form action="{{ route('cistella.afegir', $llibre->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-bold w-full">
                                    Afegir a la Cistella / Comprar
                                </button>
                            </form>
                        @else
                            {{-- L'usuari NO està registrat --}}
                            <div class="bg-gray-100 p-4 rounded-lg text-center">
                                <p class="mb-2">Vols comprar aquest llibre?</p>
                                <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">
                                    Inicia sessió per comprar
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>

                <div class="mt-12 border-t pt-8">
                    <h2 class="text-2xl font-bold mb-4">Opinions dels lectors</h2>

                    @forelse($llibre->ressenyas as $ressenya)
                        <div class="mb-4 p-4 border rounded bg-gray-50">
                            <div class="flex justify-between">
                                <strong class="text-sm">{{ $ressenya->user->name }}</strong>
                                <span class="text-yellow-500">★ {{ $ressenya->puntuacio }}/5</span>
                            </div>
                            <p class="mt-2 text-gray-700">{{ $ressenya->text }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500">Encara no hi ha ressenyes. Sigues el primer!</p>
                    @endforelse

                    <div class="mt-8">
                        @auth
                            <h3 class="text-lg font-bold mb-2">Escriu la teva opinió</h3>
                            <form action="#" method="POST"> @csrf
                                <textarea name="text" class="w-full border-gray-300 rounded-md" rows="3" placeholder="Què t'ha semblat?"></textarea>
                                <button type="submit" class="mt-2 bg-gray-800 text-white px-4 py-2 rounded">
                                    Publicar Ressenya
                                </button>
                            </form>
                        @else
                            <p class="mt-4 text-sm text-gray-500">
                                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Connecta't</a> 
                                per deixar una ressenya.
                            </p>
                        @endauth
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-guest-layout>