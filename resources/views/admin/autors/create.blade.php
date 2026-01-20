<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <h2 class="text-2xl font-bold mb-6">Afegir Nou Autor</h2>

                    <form action="{{ route('admin.autors.store') }}" method="POST">
                        @csrf <div class="mb-6">
                            <label for="nom" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nom de l'Autor *
                            </label>
                            <input type="text" 
                                   name="nom" 
                                   id="nom" 
                                   value="{{ old('nom') }}"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                   placeholder="Ex: Mercè Rodoreda"
                                   required>
                            
                            @error('nom')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="biografia" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Breu Descripció (Opcional)
                            </label>
                            <textarea name="biografia" 
                                      id="biografia" 
                                      rows="3"
                                      class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                      placeholder="Petita biografia o notes sobre l'autor...">{{ old('biografia') }}</textarea>
                        </div>

                        <div class="flex justify-end gap-4 mt-8">
                            <a href="{{ route('admin.autors.index') }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition">
                                Cancel·lar
                            </a>

                            <button type="submit" 
                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-lg transition">
                                Guardar Autor
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>