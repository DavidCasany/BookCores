<x-app-layout>
    <div class="flex h-screen bg-gray-100 dark:bg-gray-900">
        
        <aside class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 hidden md:block flex-shrink-0">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Admin Panel</h2>
            </div>
            
            <nav class="mt-4 px-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-3 rounded-md transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                   📊 Resum
                </a>

                <div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>

                <a href="#" class="flex items-center px-4 py-3 rounded-md transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                   📚 Llibres
                </a>

                <a href="{{ route('admin.autors.index') }}" 
                   class="flex items-center px-4 py-3 rounded-md transition-colors {{ request()->routeIs('admin.autors.*') ? 'bg-blue-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                   ✍️ Autors
                </a>

                <a href="#" class="flex items-center px-4 py-3 rounded-md transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                   🏢 Editorials
                </a>
            </nav>
        </aside>

        <main class="flex-1 overflow-y-auto p-8">
            <div class="max-w-7xl mx-auto">
                
                @if(request()->routeIs('admin.dashboard'))
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6">Benvingut al Centre de Comandament 🚀</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-blue-50 dark:bg-blue-900/30 p-6 rounded-lg border border-blue-200 dark:border-blue-800">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-medium text-blue-600 dark:text-blue-300">Total Llibres</p>
                                        <h4 class="text-3xl font-bold text-blue-800 dark:text-white">{{ \App\Models\Llibre::count() }}</h4>
                                    </div>
                                    <span class="text-3xl">📚</span>
                                </div>
                            </div>

                            <div class="bg-green-50 dark:bg-green-900/30 p-6 rounded-lg border border-green-200 dark:border-green-800">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-medium text-green-600 dark:text-green-300">Autors</p>
                                        <h4 class="text-3xl font-bold text-green-800 dark:text-white">{{ \App\Models\Autor::count() }}</h4>
                                    </div>
                                    <span class="text-3xl">✍️</span>
                                </div>
                            </div>

                            <div class="bg-purple-50 dark:bg-purple-900/30 p-6 rounded-lg border border-purple-200 dark:border-purple-800">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-medium text-purple-600 dark:text-purple-300">Editorials</p>
                                        <h4 class="text-3xl font-bold text-purple-800 dark:text-white">{{ \App\Models\Editorial::count() }}</h4>
                                    </div>
                                    <span class="text-3xl">🏢</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    {{ $slot }}
                @endif

            </div>
        </main>
    </div>
</x-app-layout>