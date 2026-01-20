<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white">
                    Gestió d'Autors ✍️
                </h1>
                <a href="#" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-lg transition duration-150 ease-in-out">
                    + Afegir Autor
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 p-4">
                <form action="{{ route('admin.autors.index') }}" method="GET" class="flex gap-2">
                    <input type="text" 
                           name="search" 
                           placeholder="Cercar per nom..." 
                           value="{{ request('search') }}"
                           class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">
                        🔍 Cercar
                    </button>
                    
                    @if(request('search'))
                        <a href="{{ route('admin.autors.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition flex items-center">
                            X
                        </a>
                    @endif
                </form>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Llibres Publicats</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Accions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                            @forelse ($autors as $autor)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        #{{ $autor->id }}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $autor->nom }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $autor->llibres->count() }} llibres
                                        </span>
                                        <div class="text-xs text-gray-400 mt-1">
                                            {{-- Mostrem els primers 3 títols com a exemple --}}
                                            {{ $autor->llibres->take(3)->pluck('titol')->join(', ') }}
                                            {{ $autor->llibres->count() > 3 ? '...' : '' }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end gap-2">
                                        
                                        <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900">
                                            ✏️ Editar
                                        </a>

                                        <form action="{{ route('admin.autors.destroy', $autor) }}" method="POST" 
                                              onsubmit="return confirm('⚠️ ALERTA: Estàs a punt d\'eliminar l\'autor &quot;{{ $autor->nom }}&quot;.\n\nAixò eliminarà automàticament els {{ $autor->llibres->count() }} llibres associats.\n\nEstàs segur que vols continuar?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 ml-4 cursor-pointer">
                                                🗑️ Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No s'han trobat autors.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="p-4">
                    {{ $autors->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>