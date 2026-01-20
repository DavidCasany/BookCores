<x-app-layout>
    <div class="max-w-7xl mx-auto py-12 px-4">
        
        {{-- FLETXA TORNAR AL DASHBOARD --}}
        <div class="mb-6">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-slate-400 hover:text-white transition duration-150 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
        </div>

        {{-- ENCAPÇALAMENT --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-white">Gestió de Llibres 📖</h1>
            <a href="{{ route('admin.llibres.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-bold transition shadow-lg">
                + Nou Llibre
            </a>
        </div>

        {{-- BARRA DE CERCA --}}
        <div class="bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 p-4 border border-slate-700">
            <form action="{{ route('admin.llibres.index') }}" method="GET" class="flex gap-2">
                <input type="text" 
                       name="search" 
                       placeholder="Cercar per títol o gènere..." 
                       value="{{ request('search') }}"
                       class="w-full rounded-md border-slate-600 bg-slate-900 text-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm placeholder-slate-500">
                
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition font-medium">
                    🔍 Cercar
                </button>
                
                @if(request('search'))
                    <a href="{{ route('admin.llibres.index') }}" class="bg-slate-600 hover:bg-slate-500 text-white px-4 py-2 rounded-md transition flex items-center">
                        X
                    </a>
                @endif
            </form>
        </div>

        {{-- MISSATGES DE SESSIÓ --}}
        @if(session('success'))
            <div x-data="{ show: true }"
                 x-init="setTimeout(() => show = false, 3000)"
                 x-show="show"
                 class="bg-green-900/50 border border-green-500 text-green-300 px-4 py-3 rounded relative mb-4 shadow-lg" 
                 role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- TAULA DE LLIBRES --}}
        <div class="bg-slate-800 overflow-visible shadow-sm sm:rounded-lg border border-slate-700">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-slate-300">
                    <thead class="bg-slate-900 text-slate-100 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="p-4">Títol</th>
                            <th class="p-4">Autor</th>
                            <th class="p-4">Editorial</th>
                            <th class="p-4">Preu</th>
                            <th class="p-4 text-right">Accions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @forelse($llibres as $llibre)
                        <tr class="hover:bg-slate-700 transition duration-150">
                            <td class="p-4 font-bold text-white">
                                {{ $llibre->titol }}
                                <div class="text-xs text-slate-400 font-normal">{{ $llibre->genere }}</div>
                            </td>
                            <td class="p-4">{{ $llibre->autor->nom ?? 'Anònim' }}</td>
                            <td class="p-4">{{ $llibre->editorial->nom ?? 'Sense Editorial' }}</td>
                            <td class="p-4 font-mono text-green-400">{{ number_format($llibre->preu, 2) }}€</td>
                            
                            <td class="p-4 flex justify-end gap-3">
                                <a href="{{ route('admin.llibres.edit', $llibre) }}" class="text-yellow-400 hover:text-yellow-300 font-medium transition">
                                    Editar
                                </a>
                                
                                <form action="{{ route('admin.llibres.destroy', $llibre) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-400 hover:text-red-300 font-medium transition"
                                            onclick="return confirm('Estàs segur que vols eliminar aquest llibre?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-500">
                                No s'han trobat llibres amb aquest criteri.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t border-slate-700 bg-slate-800 rounded-b-lg">
                {{ $llibres->links() }}
            </div>
        </div>
    </div>
</x-app-layout>