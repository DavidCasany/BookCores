<x-app-layout>
    <div class="max-w-7xl mx-auto py-12 px-4">
        
        <div class="mb-6">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-slate-400 hover:text-white transition duration-150 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                
            </a>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-white">Gestió d'Autors ✍️</h1>
            <a href="{{ route('admin.autors.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-bold transition shadow-lg">
                + Nou Autor
            </a>
        </div>

        <div class="bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 p-4 border border-slate-700">
            <form action="{{ route('admin.autors.index') }}" method="GET" class="flex gap-2">
                <input type="text" 
                       name="search" 
                       placeholder="Cercar per nom..." 
                       value="{{ request('search') }}"
                       class="w-full rounded-md border-slate-600 bg-slate-900 text-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm placeholder-slate-500">
                
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition font-medium">
                    🔍 Cercar
                </button>
                
                @if(request('search'))
                    <a href="{{ route('admin.autors.index') }}" class="bg-slate-600 hover:bg-slate-500 text-white px-4 py-2 rounded-md transition flex items-center">
                        X
                    </a>
                @endif
            </form>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }"
                 x-init="setTimeout(() => show = false, 3000)"
                 x-show="show"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90"
                 class="bg-green-900/50 border border-green-500 text-green-300 px-4 py-3 rounded relative mb-4 shadow-lg" 
                 role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-slate-800 overflow-visible shadow-sm sm:rounded-lg border border-slate-700">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-slate-300">
                    <thead class="bg-slate-900 text-slate-100 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="p-4">Nom</th>
                            <th class="p-4">Llibres Publicats</th>
                            <th class="p-4 text-right">Accions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @forelse ($autors as $autor)
                            <tr class="hover:bg-slate-700 transition duration-150">
                                <td class="p-4 font-bold text-white">
                                    {{ $autor->nom }}
                                    @if($autor->nom === 'Anònim')
                                        <span class="ml-2 text-xs font-normal text-slate-400 italic">(Sistema)</span>
                                    @endif
                                </td>

                                <td class="p-4">
                                    <span class="bg-slate-900 text-blue-400 py-1 px-3 rounded-full text-xs font-bold border border-slate-600">
                                        {{ $autor->llibres_count }} llibres
                                    </span>
                                    @if($autor->llibres_count > 0)
                                        <div class="text-xs text-slate-500 mt-1 truncate max-w-xs">
                                            {{ $autor->llibres->take(3)->pluck('titol')->join(', ') }}
                                            {{ $autor->llibres_count > 3 ? '...' : '' }}
                                        </div>
                                    @endif
                                </td>

                                <td class="p-4 flex justify-end gap-3 relative">
                                    
                                    <a href="{{ route('admin.autors.edit', $autor) }}" class="text-yellow-400 hover:text-yellow-300 font-medium transition">
                                        Editar
                                    </a>

                                    @if($autor->nom !== 'Anònim') 
                                        <div x-data="{ open: false }" class="relative">
                                            <button @click="open = !open" class="text-red-400 hover:text-red-300 font-medium transition">
                                                Eliminar...
                                            </button>
                                            
                                            <div x-show="open" 
                                                 @click.away="open = false" 
                                                 style="display: none;" 
                                                 x-transition:enter="transition ease-out duration-100"
                                                 x-transition:enter-start="opacity-0 scale-95"
                                                 x-transition:enter-end="opacity-100 scale-100"
                                                 class="absolute right-0 w-72 bg-slate-900 border border-slate-600 rounded-lg shadow-2xl z-50 p-4 
                                                        {{ $loop->iteration > $loop->count - 2 ? 'bottom-full mb-2' : 'mt-2' }}">
                                                
                                                <p class="text-white text-sm mb-2 font-bold">⚠️ Aquest autor té {{ $autor->llibres_count }} llibres.</p>
                                                <p class="text-slate-400 text-xs mb-3">Què en vols fer?</p>
                                                
                                                <form action="{{ route('admin.autors.destroy', $autor) }}" method="POST" class="space-y-2">
                                                    @csrf 
                                                    @method('DELETE')
                                                    
                                                    <button name="accio_llibres" value="esborrar" 
                                                            class="w-full text-left text-xs text-red-400 hover:bg-slate-800 p-2 rounded border border-red-900/50 transition flex items-center gap-2" 
                                                            onclick="return confirm('Segur? Això esborrarà l\'autor i TOTS els seus llibres PER SEMPRE.')">
                                                        🗑️ Esborrar tot (Autor + Llibres)
                                                    </button>
                                                    
                                                    <button name="accio_llibres" value="anonim" 
                                                            class="w-full text-left text-xs text-blue-400 hover:bg-slate-800 p-2 rounded border border-blue-900/50 transition flex items-center gap-2">
                                                        🕵️ Moure llibres a "Anònim"
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-8 text-center text-slate-500">
                                    No s'han trobat autors amb aquest criteri.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t border-slate-700 bg-slate-800 rounded-b-lg">
                {{ $autors->links() }}
            </div>
        </div>
    </div>
</x-app-layout>