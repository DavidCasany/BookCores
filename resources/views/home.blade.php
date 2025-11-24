<!DOCTYPE html>
<html lang="ca">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>BookCores - La teva llibreria</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="antialiased bg-slate-50 text-slate-800">

        <!-- BARRA DE NAVEGACIÓ (Header) -->
        <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    
                    <!-- LOGO -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/" class="font-serif text-2xl font-bold text-slate-900">
                            Book<span class="text-blue-900">Cores</span>
                        </a>
                    </div>

                    <!-- MENÚ DRETA (Idioma + Login) -->
                    <div class="flex items-center space-x-6">
                        
                        <!-- Selector d'Idioma senzill -->
                        <form action="#" method="GET" class="flex items-center">
                            <label for="lang" class="sr-only">Idioma</label>
                            <select name="lang" id="lang" class="bg-transparent text-sm font-medium text-slate-600 focus:ring-blue-500 border-none cursor-pointer hover:text-blue-900">
                                <option value="ca" selected>CA</option>
                                <option value="es">ES</option>
                                <option value="en">EN</option>
                            </select>
                        </form>

                        <div class="h-6 w-px bg-slate-300"></div> <!-- Separador vertical -->

                        <!-- Botons d'Autenticació Estàtics -->
                        <nav class="flex space-x-4">
                            <a href="/login" class="text-sm font-medium text-slate-600 hover:text-blue-900 transition">
                                Inicia sessió
                            </a>

                            <a href="/register" class="text-sm font-semibold bg-blue600 text-blue-900 px-4 py-2 rounded-full hover:bg-blue-900 hover:text-white transition shadow-sm">
                                Registra't
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </header>

        <!-- CONTINGUT PRINCIPAL -->
        <main>
            <!-- Hero Section (Portada) -->
            <div class="relative bg-white overflow-hidden">
                <div class="max-w-7xl mx-auto">
                    <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32 pt-20 px-4 sm:px-6 lg:px-8">
                        <div class="sm:text-center lg:text-left">
                            <h1 class="text-4xl tracking-tight font-extrabold text-slate-900 sm:text-5xl md:text-6xl">
                                <span class="block xl:inline">Troba la teva pròxima</span>
                                <span class="block text-blue-900 xl:inline">història preferida</span>
                            </h1>
                            <p class="mt-3 text-base text-slate-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                                Troba la teva següent aventura amb BookCores
                            </p>
                            <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                                <div class="rounded-md shadow">
                                    <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-900 md:py-4 md:text-lg transition">
                                        Veure catàleg
                                    </a>
                                </div>
                                <div class="mt-3 sm:mt-0 sm:ml-3">
                                    <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 md:py-4 md:text-lg transition">
                                        Saber-ne més
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Imatge decorativa dreta -->
                <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-blue-50 flex items-center justify-center">
                    <svg class="w-1/2 h-1/2 text-blue-200" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 1H5c-1.1 0-2 .9-2 2v18c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V3c0-1.1-.9-2-2-2zm-7 18.5c-2.5 0-4.5-2-4.5-4.5s2-4.5 4.5-4.5 4.5 2 4.5 4.5-2 4.5-4.5 4.5zm5-8H7V4h10v7.5z"/>
                    </svg>
                </div>
            </div>

            <!-- Secció de Destacats Senzilla -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <h2 class="text-2xl font-bold text-slate-900 mb-6">Novetats destacades</h2>
                
                <!-- GRID DE LLIBRES (Sense bucle PHP) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <!-- Targeta 1 -->
                    <div class="group relative bg-white border border-slate-200 rounded-lg p-4 hover:shadow-md transition">
                        <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-slate-200 group-hover:opacity-75 h-64 mb-4 flex items-center justify-center text-slate-400">
                            <span class="text-3xl">📚</span>
                        </div>
                        <h3 class="text-sm font-medium text-slate-900">
                            <a href="#">
                                <span aria-hidden="true" class="absolute inset-0"></span>
                                L'Ombra del Vent
                            </a>
                        </h3>
                        <p class="text-sm text-slate-500">Carlos Ruiz Zafón</p>
                        <p class="text-sm font-bold text-blue-900 mt-1">12,95 €</p>
                    </div>

                    <!-- Targeta 2 -->
                    <div class="group relative bg-white border border-slate-200 rounded-lg p-4 hover:shadow-md transition">
                        <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-slate-200 group-hover:opacity-75 h-64 mb-4 flex items-center justify-center text-slate-400">
                            <span class="text-3xl">📕</span>
                        </div>
                        <h3 class="text-sm font-medium text-slate-900">
                            <a href="#">
                                <span aria-hidden="true" class="absolute inset-0"></span>
                                Camí de Sirga
                            </a>
                        </h3>
                        <p class="text-sm text-slate-500">Jesús Moncada</p>
                        <p class="text-sm font-bold text-blue-900 mt-1">18,50 €</p>
                    </div>

                    <!-- Targeta 3 -->
                    <div class="group relative bg-white border border-slate-200 rounded-lg p-4 hover:shadow-md transition">
                        <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-slate-200 group-hover:opacity-75 h-64 mb-4 flex items-center justify-center text-slate-400">
                            <span class="text-3xl">📗</span>
                        </div>
                        <h3 class="text-sm font-medium text-slate-900">
                            <a href="#">
                                <span aria-hidden="true" class="absolute inset-0"></span>
                                Mirall Trencat
                            </a>
                        </h3>
                        <p class="text-sm text-slate-500">Mercè Rodoreda</p>
                        <p class="text-sm font-bold text-blue-900 mt-1">15,90 €</p>
                    </div>

                    <!-- Targeta 4 -->
                    <div class="group relative bg-white border border-slate-200 rounded-lg p-4 hover:shadow-md transition">
                        <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-slate-200 group-hover:opacity-75 h-64 mb-4 flex items-center justify-center text-slate-400">
                            <span class="text-3xl">📘</span>
                        </div>
                        <h3 class="text-sm font-medium text-slate-900">
                            <a href="#">
                                <span aria-hidden="true" class="absolute inset-0"></span>
                                Jo confesso
                            </a>
                        </h3>
                        <p class="text-sm text-slate-500">Jaume Cabré</p>
                        <p class="text-sm font-bold text-blue-900 mt-1">22,00 €</p>
                    </div>

                </div>
            </div>
        </main>

        <!-- FOOTER SENZILL -->
        <footer class="bg-white border-t border-slate-200 mt-12">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-base text-slate-400">
                    &copy; 2025 BookCores. Tots els drets reservats.
                </p>
            </div>
        </footer>

    </body>
</html>