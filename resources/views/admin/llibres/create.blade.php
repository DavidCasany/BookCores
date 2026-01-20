<x-app-layout>
    <div class="max-w-4xl mx-auto py-12 px-4">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('admin.llibres.index') }}" class="text-slate-400 hover:text-white transition">&larr; Tornar a la llista</a>
            <h1 class="text-2xl font-bold text-white">Publicar Nou Llibre</h1>
        </div>

        <form action="{{ route('admin.llibres.store') }}" method="POST" enctype="multipart/form-data" class="bg-slate-800 p-8 rounded-lg shadow-lg border border-slate-700 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-white block mb-2 font-bold">Títol del Llibre</label>
                    <input type="text" name="titol" class="w-full bg-slate-900 border-slate-600 text-white rounded focus:ring-blue-500" placeholder="El nom del llibre" required>
                </div>
                <div>
                    <label class="text-white block mb-2 font-bold">Gènere</label>
                    <input type="text" name="genere" class="w-full bg-slate-900 border-slate-600 text-white rounded focus:ring-blue-500" placeholder="Ex: Fantasia, Ciència-ficció..." required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-white block mb-2 font-bold">Autor</label>
                    <select name="autor_id" class="w-full bg-slate-900 border-slate-600 text-white rounded focus:ring-blue-500">
                        @foreach($autors as $autor)
                        <option value="{{ $autor->id }}">{{ $autor->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-white block mb-2 font-bold">Editorial</label>
                    <select name="editorial_id" class="w-full bg-slate-900 border-slate-600 text-white rounded focus:ring-blue-500">
                        @foreach($editorials as $ed)
                        <option value="{{ $ed->id }}" {{ (isset($editorialPreseleccionada) && $editorialPreseleccionada == $ed->id) ? 'selected' : '' }}>
                            {{ $ed->nom }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-white block mb-2 font-bold">Preu (€)</label>
                    <input type="number" step="0.01" name="preu" class="w-full bg-slate-900 border-slate-600 text-white rounded focus:ring-blue-500" placeholder="0.00" required>
                </div>
                <div>
                    <label class="text-white block mb-2 font-bold">Número de Pàgines</label>
                    <input type="number" name="pagines" class="w-full bg-slate-900 border-slate-600 text-white rounded focus:ring-blue-500" placeholder="123" required>
                </div>
            </div>

            <div class="p-6 bg-slate-900/50 rounded border border-slate-700 grid grid-cols-1 md:grid-cols-2 gap-8">

                <div>
                    <label class="text-blue-400 block mb-2 font-bold">📸 Portada (Imatge)</label>
                    <div class="bg-slate-800 p-4 rounded border border-slate-600">
                        <input type="file" name="img_portada" accept="image/*" class="w-full text-slate-300 text-sm file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer" required>
                        <p class="text-xs text-slate-500 mt-2">Formats: JPG, PNG, WEBP (Màx 2MB)</p>
                    </div>
                </div>

                <div>
                    <label class="text-red-400 block mb-2 font-bold">📕 Llibre (PDF)</label>
                    <div class="bg-slate-800 p-4 rounded border border-slate-600">
                        <input type="file" name="fitxer_pdf" accept="application/pdf" class="w-full text-slate-300 text-sm file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-bold file:bg-red-600 file:text-white hover:file:bg-red-700 cursor-pointer" required>
                        <p class="text-xs text-slate-500 mt-2">Format: PDF (Màx 10MB)</p>
                    </div>
                </div>
                <div class="md:col-span-2 mt-6 pt-6 border-t border-slate-700">
                    <label class="text-purple-400 block mb-2 font-bold">🖼️ Imatge Hero (Horitzontal per al Carrusel)</label>
                    <div class="bg-slate-800 p-4 rounded border border-slate-600">
                        <input type="file" name="img_hero" accept="image/*" class="w-full text-slate-300 text-sm file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-bold file:bg-purple-600 file:text-white hover:file:bg-purple-700 cursor-pointer">
                        <p class="text-xs text-slate-500 mt-2">Recomanat: Format panoràmic (ex: 1920x600px). (Opcional)</p>
                    </div>
                </div>
            </div>


            <div>
                <label class="text-white block mb-2 font-bold">Sinopsi / Descripció</label>
                <textarea name="descripcio" rows="4" class="w-full bg-slate-900 border-slate-600 text-white rounded focus:ring-blue-500" placeholder="De què va el llibre?"></textarea>
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t border-slate-700">
                <a href="{{ route('admin.llibres.index') }}" class="bg-slate-700 text-white px-6 py-3 rounded hover:bg-slate-600 font-bold transition">
                    Cancel·lar
                </a>
                <button class="bg-green-600 text-white px-8 py-3 rounded hover:bg-green-700 font-bold shadow-lg shadow-green-900/20 transition transform hover:-translate-y-0.5">
                    🚀 Publicar Llibre
                </button>
            </div>
        </form>
    </div>
</x-app-layout>