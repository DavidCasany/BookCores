<x-app-layout>
    <div class="max-w-2xl mx-auto py-12 px-4">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('admin.editorials.index') }}" class="text-slate-400 hover:text-white">&larr; Tornar</a>
            <h1 class="text-2xl font-bold text-white">Nova Editorial</h1>
        </div>

        <form action="{{ route('admin.editorials.store') }}" method="POST" class="bg-slate-800 p-6 rounded-lg shadow-lg space-y-6 border border-slate-700">
            @csrf
            
            <div>
                <label class="text-white block mb-2 font-medium">Nom de l'Editorial</label>
                <input type="text" name="nom" class="w-full bg-slate-900 border-slate-600 text-white rounded focus:ring-blue-500 focus:border-blue-500" placeholder="Ex: Planeta, Edicions 62..." required>
            </div>

            <div>
                <label class="text-white block mb-2 font-medium">Descripció (Opcional)</label>
                <textarea name="descripcio" rows="4" class="w-full bg-slate-900 border-slate-600 text-white rounded focus:ring-blue-500 focus:border-blue-500" placeholder="Especialitzada en novel·la negra..."></textarea>
            </div>

            <div class="flex justify-end">
                <button class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 font-bold transition shadow-lg shadow-green-900/20">
                    Guardar Editorial
                </button>
            </div>
        </form>
    </div>
</x-app-layout>