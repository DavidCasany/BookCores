<x-app-layout>
    <div class="max-w-4xl mx-auto py-12 px-4">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('admin.llibres.index') }}" class="text-slate-400 hover:text-white transition">&larr; Tornar a la llista</a>
            <h1 class="text-2xl font-bold text-white">Editar: {{ $llibre->titol }}</h1>
        </div>

        <form action="{{ route('admin.llibres.update', $llibre->id_llibre) }}" method="POST" enctype="multipart/form-data" class="bg-slate-800 p-8 rounded-lg shadow-lg border border-slate-700 space-y-6">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-white block mb-2 font-bold">Títol</label>
                    <input type="text" name="titol" value="{{ $llibre->titol }}" class="w-full bg-slate-900 border-slate-600 text-white rounded focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="text-white block mb-2 font-bold">Gènere</label>
                    <input type="text" name="genere" value="{{ $llibre->genere }}" class="w-full bg-slate-900 border-slate-600 text-white rounded focus:ring-blue-500" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-white block mb-2 font-bold">Autor</label>
                    <select name="autor_id" class="w-full bg-slate-900 border-slate-600 text-white rounded">
                        @foreach($autors as $autor)
                        <option value="{{ $autor->id }}" {{ $llibre->autor_id == $autor->id ? 'selected' : '' }}>
                            {{ $autor->nom }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-white block mb-2 font-bold">Editorial</label>
                    <select name="editorial_id" class="w-full bg-slate-900 border-slate-600 text-white rounded">
                        @foreach($editorials as $ed)
                        <option value="{{ $ed->id }}" {{ $llibre->editorial_id == $ed->id ? 'selected' : '' }}>
                            {{ $ed->nom }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-white block mb-2 font-bold">Preu (€)</label>
                    <input type="number" step="0.01" name="preu" value="{{ $llibre->preu }}" class="w-full bg-slate-900 border-slate-600 text-white rounded" required>
                </div>
                <div>
                    <label class="text-white block mb-2 font-bold">Número de Pàgines</label>
                    <input type="number" name="pagines" value="{{ $llibre->pagines }}" class="w-full bg-slate-900 border-slate-600 text-white rounded" required>
                </div>
            </div>

            <div class="p-6 bg-slate-900/50 rounded border border-slate-700 grid grid-cols-1 md:grid-cols-2 gap-8">

                <div class="space-y-4">
                    <label class="text-blue-400 block font-bold">📸 Portada</label>
                    <div class="flex gap-4 items-start">
                        @if($llibre->img_portada)
                        <img src="{{ asset('img/' . $llibre->img_portada) }}" class="w-20 rounded shadow border border-slate-600" title="Portada Actual">
                        @endif
                        <div class="flex-1">
                            <input type="file" name="img_portada" accept="image/*" class="w-full text-slate-400 text-sm file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                            <p class="text-xs text-slate-500 mt-2">Deixa-ho buit per mantenir l'actual.</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="text-red-400 block font-bold">📕 Fitxer PDF</label>
                    <div class="bg-slate-800 p-3 rounded border border-slate-700 text-sm text-slate-300">
                        @if($llibre->fitxer_pdf)
                        <div class="flex items-center gap-2 mb-2">
                            ✅ <span>Fitxer actual: </span>
                            <span class="font-mono text-xs bg-slate-900 px-2 py-1 rounded truncate max-w-[150px]">{{ $llibre->fitxer_pdf }}</span>
                        </div>
                        @else
                        <div class="text-yellow-500 mb-2">⚠️ No hi ha PDF pujat</div>
                        @endif

                        <input type="file" name="fitxer_pdf" accept="application/pdf" class="w-full text-slate-400 text-sm file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-red-600 file:text-white hover:file:bg-red-700">
                        <p class="text-xs text-slate-500 mt-2">Deixa-ho buit per mantenir l'actual.</p>
                    </div>
                </div>
                <div class="md:col-span-2 mt-6 pt-6 border-t border-slate-700">
                    <label class="text-purple-400 block mb-2 font-bold">🖼️ Imatge Hero (Horitzontal)</label>

                    <div class="bg-slate-900/50 p-6 rounded border border-slate-700">
                        @if($llibre->img_hero)
                        <div class="mb-4">
                            <p class="text-xs text-slate-400 mb-2">Imatge actual:</p>
                            <img src="{{ asset('img/' . $llibre->img_hero) }}" class="w-full h-32 object-cover rounded border border-slate-600">
                        </div>
                        @endif

                        <input type="file" name="img_hero" accept="image/*" class="w-full text-slate-300 text-sm file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-bold file:bg-purple-600 file:text-white hover:file:bg-purple-700 cursor-pointer">
                        <p class="text-xs text-slate-500 mt-2">Deixa-ho buit per mantenir l'actual.</p>
                    </div>
                </div>
            </div>

            <div>
                <label class="text-white block mb-2 font-bold">Sinopsi</label>
                <textarea name="descripcio" rows="5" class="w-full bg-slate-900 border-slate-600 text-white rounded">{{ $llibre->descripcio }}</textarea>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.llibres.index') }}" class="bg-slate-700 text-white px-6 py-2 rounded hover:bg-slate-600 font-bold transition">Cancel·lar</a>
                <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-bold shadow-lg transition">Guardar Canvis</button>
            </div>
        </form>
    </div>
</x-app-layout>