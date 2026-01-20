<x-app-layout>
    <div class="max-w-7xl mx-auto py-12 px-4 space-y-8">
        
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.editorials.index') }}" class="text-slate-400 hover:text-white">&larr; Tornar a la llista</a>
            <h1 class="text-3xl font-bold text-white">Gestió Editorial</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="space-y-6">
                <div class="bg-slate-800 p-6 rounded-lg border border-slate-700 shadow-lg">
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        ✏️ Dades Bàsiques
                    </h2>
                    <form action="{{ route('admin.editorials.update', $editorial) }}" method="POST" class="space-y-4">
                        @csrf @method('PUT')
                        <div>
                            <label class="text-slate-300 text-sm font-bold">Nom</label>
                            <input type="text" name="nom" value="{{ $editorial->nom }}" class="w-full mt-1 bg-slate-900 text-white rounded border-slate-600 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="text-slate-300 text-sm font-bold">Descripció</label>
                            <textarea name="descripcio" rows="5" class="w-full mt-1 bg-slate-900 text-white rounded border-slate-600 focus:border-blue-500">{{ $editorial->descripcio }}</textarea>
                        </div>
                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-bold transition">
                            Actualitzar Dades
                        </button>
                    </form>
                </div>

                <div class="bg-slate-800 p-6 rounded-lg border border-slate-700 shadow-lg">
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        📥 Afegir Llibres
                    </h2>
                    
                    @if ($errors->any())
    <div class="bg-red-500 text-white p-4 rounded mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="bg-green-500 text-white p-4 rounded mb-4">
        {{ session('success') }}
    </div>
@endif
                    <form action="{{ route('admin.editorials.afegir-llibre', $editorial) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div class="bg-slate-900/50 p-3 rounded border border-slate-700">
                            <label class="flex items-center space-x-3 text-white cursor-pointer">
                                <input type="radio" name="origen" value="nou" checked onclick="document.getElementById('select-existent').disabled = true; document.getElementById('select-existent').classList.add('opacity-50');" class="text-blue-600 focus:ring-blue-500">
                                <span class="font-bold">✨ Crear Llibre Nou</span>
                            </label>
                            <p class="text-xs text-slate-400 mt-1 ml-7">Et portarà al formulari de creació amb aquesta editorial preseleccionada.</p>
                        </div>

                        <div class="bg-slate-900/50 p-3 rounded border border-slate-700">
                            <label class="flex items-center space-x-3 text-white cursor-pointer mb-2">
                                <input type="radio" name="origen" value="existent" onclick="document.getElementById('select-existent').disabled = false; document.getElementById('select-existent').classList.remove('opacity-50');" class="text-blue-600 focus:ring-blue-500">
                                <span class="font-bold">🔄 Assignar Existent</span>
                            </label>
                            
                            <select id="select-existent" name="llibre_id" disabled class="w-full bg-slate-900 text-white rounded border-slate-600 text-sm opacity-50 transition">
                                <option value="">-- Cerca per títol --</option>
                                @foreach($llibresExterns as $l)
                                    <option value="{{ $l->id_llibre }}">{{ $l->titol }} (Actual: {{ $l->editorial->nom ?? 'Cap' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <button class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded font-bold transition shadow-lg shadow-green-900/20">
                            Executar Acció
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2 bg-slate-800 p-6 rounded-lg border border-slate-700 shadow-lg h-fit">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                        📚 Catàleg Actual
                        <span class="bg-slate-700 text-white text-sm py-1 px-3 rounded-full ml-2">{{ $editorial->llibres->count() }}</span>
                    </h2>
                </div>

                @if($editorial->llibres->isEmpty())
                    <div class="text-center py-12 bg-slate-900/50 rounded border border-dashed border-slate-700">
                        <p class="text-slate-400">Aquesta editorial encara no té cap llibre assignat.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($editorial->llibres as $llibre)
                        <div class="bg-slate-900/80 p-4 rounded border border-slate-700 flex flex-col md:flex-row justify-between items-center gap-4 hover:border-slate-500 transition">
                            
                            <div class="flex items-center gap-4 w-full">
                                <div class="bg-slate-800 h-12 w-12 rounded flex items-center justify-center text-xl">📖</div>
                                <div>
                                    <h4 class="text-white font-bold text-lg">{{ $llibre->titol }}</h4>
                                    <div class="text-xs text-slate-400 flex gap-3">
                                        <span>Autor: {{ $llibre->autor->nom ?? 'Desconegut' }}</span>
                                        <span>Preu: {{ $llibre->preu }}€</span>
                                        <a href="{{ route('admin.llibres.edit', $llibre->id_llibre) }}" class="text-blue-400 hover:underline">Editar Fitxa &rarr;</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="w-full md:w-auto bg-slate-800 p-2 rounded border border-slate-700">
                                <form action="{{ route('admin.llibres.desvincular', $llibre) }}" method="POST" class="flex flex-col gap-2">
                                    @csrf
                                    
                                    <div class="flex items-center gap-2">
                                        <select name="accio" class="bg-slate-900 text-white text-xs rounded border-slate-600 focus:ring-0 w-40" 
                                                onchange="const targetSelect = this.parentElement.nextElementSibling; this.value === 'transferir' ? targetSelect.classList.remove('hidden') : targetSelect.classList.add('hidden')">
                                            <option value="autopublicar">Moure a Autopublicats</option>
                                            <option value="transferir">Transferir a una altra...</option>
                                            <option value="esborrar">🗑️ ELIMINAR definitivament</option>
                                        </select>
                                        
                                        <button class="bg-slate-700 hover:bg-slate-600 text-white px-3 py-1.5 rounded text-xs font-bold transition">
                                            Aplicar
                                        </button>
                                    </div>

                                    <div class="hidden">
                                        <select name="target_editorial_id" class="w-full bg-slate-900 text-white text-xs rounded border-slate-600">
                                            @foreach($altresEditorials as $ae)
                                                <option value="{{ $ae->id }}">{{ $ae->nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            </div>

                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>