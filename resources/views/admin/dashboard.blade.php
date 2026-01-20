<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Panell d\'Administració') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-4">Benvingut, Administrador 👋</h3>
                    <p>Des d'aquí podràs gestionar tot el contingut de BookCores.</p>
                    
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 bg-gray-700 rounded-lg border border-gray-600">
                            <h4 class="font-bold text-lg text-blue-400">Llibres</h4>
                            <p class="text-sm mt-2">Gestió del catàleg</p>
                        </div>
                        <div class="p-4 bg-gray-700 rounded-lg border border-gray-600">
                            <h4 class="font-bold text-lg text-green-400">Autors</h4>
                            <p class="text-sm mt-2">Gestió d'escriptors</p>
                        </div>
                        <div class="p-4 bg-gray-700 rounded-lg border border-gray-600">
                            <h4 class="font-bold text-lg text-purple-400">Editorials</h4>
                            <p class="text-sm mt-2">Gestió de procedència</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>