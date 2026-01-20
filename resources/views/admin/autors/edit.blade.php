<x-app-layout>
    <div class="max-w-7xl mx-auto py-12 px-4 space-y-8">
        
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.autors.index') }}" class="text-slate-400 hover:text-white transition duration-150">&larr; Tornar a la llista</a>
            <h1 class="text-3xl font-bold text-white">Gestió d'Autor</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="space-y-6">
                
                <div class="bg-slate-800 p-6 rounded-lg border border-slate-700 shadow-lg">
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        ✏️ Dades de l'Autor
                    </h2>
                    <form action="{{ route('admin.autors.update', $autor) }}" method="POST" class="space-y-4">
                        @csrf @method('PUT')
                        
                        <div>
                            <label class="text-slate-300 text-sm font-bold">Nom</label>
                            <input type="text" name="nom" value="{{ old('nom', $autor->nom) }}" 
                                   class="w-full mt-1 bg-slate-900 text-white rounded border-slate-600 focus:border-blue-500 focus:ring-blue-500 shadow-sm" required>
                            @error('nom') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-slate-300 text-sm font-bold">Biografia / Descripció</label>
                            <textarea name="biografia" rows="5" 
                                      class="w-full mt-1 bg-slate-900 text-white rounded border-slate-600 focus:border-blue-500 focus:ring-blue-500 shadow-sm">{{ old('biografia', $autor->biografia) }}</textarea>
                        </div>

                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-bold transition shadow-lg">
                            💾 Actualitzar Dades
                        </button>
                    </form>
                </div>

                <div class="bg-slate-800 p-6 rounded-lg border border-slate-700 shadow-lg">
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        📥 Assignar Obra
                    </h2>
                    
                    <form action="{{ route('admin.autors.assignar-llibre', $autor) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div class="bg-slate-900/50 p-3 rounded border border-slate-700">
                            <label class="flex items-center space-x-3 text-white cursor-pointer">
                                <input type="radio" name="origen" value="nou" checked 
                                       onclick="document.getElementById('select-existent').disabled = true; document.getElementById('select-existent').classList.add('opacity-50');" 
                                       class="text-blue-600 focus:ring-blue-500 bg-slate-800 border-slate-600">
                                <span class="font-bold">✨ Crear Llibre Nou</span>
                            </label>
                            <p class="text-xs text-slate-400 mt-1 ml-7">Et portarà al formulari de creació amb aquest autor preseleccionat.</p>
                        </div>

                        <div class="bg-slate-900/50 p-3 rounded border border-slate-700">
                            <label class="flex items-center space-x-3 text-white cursor-pointer mb-2">
                                <input type="radio" name="origen" value="existent" 
                                       onclick="document.getElementById('select-existent').disabled = false; document.getElementById('select-existent').classList.remove('opacity-50');" 
                                       class="text-blue-600 focus:ring-blue-500 bg-slate-800 border-slate-600">
                                <span class="font-bold">🔄 Reclamar Autoria</span>
                            </label>
                            
                            <select id="select-existent" name="llibre_id" disabled 
                                    class="w-full bg-slate-900 text-white rounded border-slate-600 text-sm opacity-50 transition focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Cerca per títol --</option>
                                @foreach($llibresExterns as $l)
                                    <option value="{{ $l->id_llibre ?? $l->id }}">
                                        {{ $l->titol }} (Autor actual: {{ $l->autor->nom ?? 'Cap' }})
                                    </option>
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
                        📚 Obres Publicades
                        <span class="bg-slate-700 text-white text-sm py-1 px-3 rounded-full ml-2">{{ $autor->llibres->count() }}</span>
                    </h2>
                </div>

                @if(session('success'))
                    <div class="bg-green-900/50 text-green-300 border border-green-500 p-4 rounded mb-4 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if($autor->llibres->isEmpty())
                    <div class="text-center py-12 bg-slate-900/50 rounded border border-dashed border-slate-700">
                        <p class="text-slate-400">Aquest autor no té cap llibre assignat actualment.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($autor->llibres as $llibre)
                        <div class="bg-slate-900/80 p-4 rounded border border-slate-700 flex flex-col md:flex-row justify-between items-center gap-4 hover:border-slate-500 transition duration-150">
                            
                            <div class="flex items-center gap-4 w-full">
                                <div class="bg-slate-800 h-12 w-12 rounded flex items-center justify-center text-2xl shadow-inner">📖</div>
                                <div>
                                    <h4 class="text-white font-bold text-lg leading-tight">{{ $llibre->titol }}</h4>
                                    <div class="text-xs text-slate-400 flex gap-3 mt-1">
                                        <span>Preu: {{ $llibre->preu }}€</span>
                                        <span>Pàgines: {{ $llibre->pagines }}</span>
                                        <span class="text-slate-500">|</span>
                                        <a href="#" class="text-blue-400 hover:text-blue-300 hover:underline">Editar Fitxa &rarr;</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="w-full md:w-auto bg-slate-800 p-3 rounded border border-slate-700 shadow-sm">
                                
                                <div x-data="{ accio: 'anonim' }" class="flex flex-col gap-2">
                                    
                                    <div class="flex items-center gap-2">
                                        <select x-model="accio" class="bg-slate-900 text-white text-xs rounded border-slate-600 focus:ring-0 focus:border-blue-500 w-40 cursor-pointer">
                                            <option value="anonim">🕵️ Moure a Anònim</option>
                                            <option value="transferir">➡️ Transferir a...</option>
                                            <option value="esborrar">🗑️ ELIMINAR definitivament</option>
                                        </select>

                                        <form x-show="accio === 'anonim'" action="{{ route('admin.autors.llibre.anonim', $llibre->id_llibre ?? $llibre->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-slate-700 hover:bg-slate-600 text-white px-3 py-1.5 rounded text-xs font-bold transition">
                                                Aplicar
                                            </button>
                                        </form>

                                        <form x-show="accio === 'esborrar'" action="{{ route('admin.autors.llibre.destroy', $llibre->id_llibre ?? $llibre->id) }}" method="POST"
                                              onsubmit="return confirm('ATENCIÓ: Estàs segur que vols eliminar &quot;{{ $llibre->titol }}&quot;? Aquesta acció no es pot desfer.');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="bg-red-900/50 hover:bg-red-800 text-red-200 border border-red-800 px-3 py-1.5 rounded text-xs font-bold transition">
                                                Eliminar
                                            </button>
                                        </form>

                                        <div x-show="accio === 'transferir'">
                                            </div>
                                    </div>

                                    <form x-show="accio === 'transferir'" action="{{ route('admin.autors.llibre.transferir', $llibre->id_llibre ?? $llibre->id) }}" method="POST" class="flex gap-2" style="display: none;">
                                        @csrf
                                        <select name="nou_autor_id" class="w-full bg-slate-900 text-white text-xs rounded border-slate-600" required>
                                            <option value="" disabled selected>Tria nou autor...</option>
                                            @foreach($altresAutors as $altre)
                                                <option value="{{ $altre->id }}">{{ $altre->nom }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-3 py-1 rounded text-xs font-bold transition">
                                            Moure
                                        </button>
                                    </form>

                                </div>
                            </div>

                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>