<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Registre - La teva llibreria') }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,900&display=swap" rel="stylesheet" />
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Figtree', 'sans-serif'],
                        serif: ['Georgia', 'serif'],
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>


<body class="antialiased bg-slate-900 text-slate-100 h-screen w-full overflow-hidden flex items-center justify-center relative"
      x-data="{ isEntering: true }"
      x-init="setTimeout(() => isEntering = false, 50)">

  
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('img/bosc_verd.jpg') }}" class="w-full h-full object-cover brightness-[0.3]" alt="Fons">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900/80 via-slate-900/50 to-slate-900/80"></div>
    </div>

    
    <div class="relative z-10 w-full max-w-md px-6 py-12 transition-all duration-700 ease-out transform"
         :class="isEntering ? 'translate-x-full opacity-0 scale-95' : 'translate-x-0 opacity-100 scale-100'">

        <!-- // logo i títol -->
        <div class="mb-8 text-center">
            <a href="/" class="inline-flex items-center gap-3 text-4xl font-serif font-bold text-white hover:opacity-80 transition-opacity">
                <svg class="h-10 w-10 text-blue-500 drop-shadow-md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                <span class="drop-shadow-sm">Book<span class="text-blue-500">Cores</span></span>
            </a>
            <p class="mt-3 text-slate-400 font-medium text-lg">{{ __('Crea el teu compte') }}</p>
        </div>

        <!-- // formulari -->
        <div class="w-full bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl shadow-2xl p-8">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- // nom -->
                <div>
                    <label for="name" class="block text-sm font-bold text-slate-300 mb-1 ml-1">{{ __('Nom') }}</label>
                    <input id="name" class="block w-full px-4 py-3 bg-slate-800/50 border border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-slate-500 transition-all outline-none" 
                           type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="El teu nom" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-400 text-sm" />
                </div>

                <!-- // gmail -->
                <div class="mt-5">
                    <label for="email" class="block text-sm font-bold text-slate-300 mb-1 ml-1">{{ __('Email') }}</label>
                    <input id="email" class="block w-full px-4 py-3 bg-slate-800/50 border border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-slate-500 transition-all outline-none" 
                           type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="exemple@correu.cat" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400 text-sm" />
                </div>

                <!-- // contrassenya -->
                <div class="mt-5">
                    <label for="password" class="block text-sm font-bold text-slate-300 mb-1 ml-1">{{ __('Contrasenya') }}</label>
                    <input id="password" class="block w-full px-4 py-3 bg-slate-800/50 border border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-slate-500 transition-all outline-none" 
                           type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400 text-sm" />
                </div>

                <!-- // confirmar contrassenya -->
                <div class="mt-5">
                    <label for="password_confirmation" class="block text-sm font-bold text-slate-300 mb-1 ml-1">{{ __('Confirmar Contrasenya') }}</label>
                    <input id="password_confirmation" class="block w-full px-4 py-3 bg-slate-800/50 border border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-white placeholder-slate-500 transition-all outline-none" 
                           type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-400 text-sm" />
                </div>

                <div class="flex items-center justify-between mt-8">
                    <a class="text-sm font-medium text-slate-400 hover:text-blue-400 transition-colors" href="{{ route('login') }}">
                        {{ __('Ja tens compte?') }}
                    </a>

                    <button class="px-8 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-full font-bold shadow-lg shadow-blue-600/30 transform hover:-translate-y-0.5 transition-all duration-200">
                        {{ __('Registra\'t') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>