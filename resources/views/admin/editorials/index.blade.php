<x-app-layout>
    <div class="max-w-7xl mx-auto py-12 px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-white">Editorials</h1>
            <a href="{{ route('admin.editorials.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-bold transition">
                + Nova Editorial
            </a>
        </div>

        <div class="bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg border border-slate-700">
            <table class="w-full text-left text-slate-300">
                <thead class="bg-slate-900 text-slate-100 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="p-4">Nom</th>
                        <th class="p-4">Descripció</th>
                        <th class="p-4">Llibres en Catàleg</th>
                        <th class="p-4 text-right">Accions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach($editorials as $ed)
                    <tr class="hover:bg-slate-700 transition">
                        <td class="p-4 font-bold text-white">{{ $ed->nom }}</td>
                        <td class="p-4 text-sm text-slate-400 truncate max-w-xs">{{ $ed->descripcio ?? '-' }}</td>
                        <td class="p-4">
                            <span class="bg-slate-900 text-blue-400 py-1 px-3 rounded-full text-xs font-bold">
                                {{ $ed->llibres_count }} llibres
                            </span>
                        </td>
                        <td class="p-4 flex justify-end gap-3 relative">
                            <a href="{{ route('admin.editorials.edit', $ed) }}" class="text-yellow-400 hover:text-yellow-300 font-medium">
                                Editar
                            </a>

                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="text-red-400 hover:text-red-300 font-medium">
                                    Eliminar...
                                </button>
                                
                                <div x-show="open" @click.away="open = false" style="display: none;" 
                                     class="absolute right-0 mt-2 w-72 bg-slate-900 border border-slate-600 rounded-lg shadow-xl z-50 p-4">
                                    <p class="text-white text-sm mb-3 font-bold">⚠️ Aquesta editorial té {{ $ed->llibres_count }} llibres.</p>
                                    <p class="text-slate-400 text-xs mb-3">Què en vols fer?</p>
                                    
                                    <form action="{{ route('admin.editorials.destroy', $ed) }}" method="POST" class="space-y-2">
                                        @csrf @method('DELETE')
                                        
                                        <button name="accio_llibres" value="esborrar" class="w-full text-left text-xs text-red-400 hover:bg-slate-800 p-2 rounded border border-red-900/50 transition" onclick="return confirm('Segur? Això esborrarà els llibres PER SEMPRE.')">
                                            🗑️ Esborrar llibres i editorial
                                        </button>
                                        
                                        <button name="accio_llibres" value="autopublicar" class="w-full text-left text-xs text-blue-400 hover:bg-slate-800 p-2 rounded border border-blue-900/50 transition">
                                            🔄 Moure a Autopublicats i esborrar ed.
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="p-4 border-t border-slate-700">
                {{ $editorials->links() }}
            </div>
        </div>
    </div>
</x-app-layout>