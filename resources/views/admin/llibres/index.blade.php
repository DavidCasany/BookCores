<x-app-layout>
    <div class="max-w-7xl mx-auto py-12 px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-white">Gestió de Llibres</h1>
            <a href="{{ route('admin.llibres.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-bold transition">
                + Nou Llibre
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-6 shadow-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg border border-slate-700">
            <table class="w-full text-left text-slate-300">
                <thead class="bg-slate-900 text-slate-100 uppercase text-xs">
                    <tr>
                        <th class="p-4">Portada</th>
                        <th class="p-4">Títol / Autor</th>
                        <th class="p-4">Editorial</th>
                        <th class="p-4">Preu</th>
                        <th class="p-4 text-right">Accions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach($llibres as $llibre)
                    <tr class="hover:bg-slate-700 transition">
                        <td class="p-4">
                            @if($llibre->img_portada)
                                <img src="{{ asset('storage/' . $llibre->img_portada) }}" class="h-16 w-12 object-cover rounded shadow-sm">
                            @else
                                <div class="h-16 w-12 bg-slate-600 rounded flex items-center justify-center text-xs">No img</div>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="font-bold text-white text-lg">{{ $llibre->titol }}</div>
                            <div class="text-sm text-slate-400">{{ $llibre->autor->nom ?? 'Desconegut' }}</div>
                            <div class="text-xs text-slate-500">{{ $llibre->genere }}</div>
                        </td>
                        <td class="p-4">
                            <span class="bg-slate-900 text-blue-300 py-1 px-2 rounded text-xs border border-slate-600">
                                {{ $llibre->editorial->nom ?? 'Autopublicat' }}
                            </span>
                        </td>
                        <td class="p-4 font-bold text-white">{{ $llibre->preu }}€</td>
                        <td class="p-4 text-right space-x-2">
                            <a href="{{ route('admin.llibres.edit', $llibre->id_llibre) }}" class="text-yellow-400 hover:text-yellow-300 font-bold">Editar</a>
                            
                            <form action="{{ route('admin.llibres.destroy', $llibre->id_llibre) }}" method="POST" class="inline-block" onsubmit="return confirm('Segur que vols eliminar aquest llibre?');">
                                @csrf @method('DELETE')
                                <button class="text-red-400 hover:text-red-300 font-bold">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4 border-t border-slate-700">
                {{ $llibres->links() }}
            </div>
        </div>
    </div>
</x-app-layout>