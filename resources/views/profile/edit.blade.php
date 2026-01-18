<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
        
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-serif text-3xl font-bold text-slate-800 dark:text-white leading-tight">
                {{ __('El meu Perfil') }}
            </h2>
        </div>

        {{-- SECCIÓ 1: INFORMACIÓ PERFIL --}}
        <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow-xl sm:rounded-3xl border border-slate-200 dark:border-slate-700 transition-colors duration-300">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- SECCIÓ 2: CONTRASENYA --}}
        <div class="p-4 sm:p-8 bg-white dark:bg-slate-800 shadow-xl sm:rounded-3xl border border-slate-200 dark:border-slate-700 transition-colors duration-300">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- SECCIÓ 3: ELIMINAR COMPTE (PERILL) --}}
        <div class="p-4 sm:p-8 bg-red-50 dark:bg-red-900/20 shadow-xl sm:rounded-3xl border border-red-200 dark:border-red-800 transition-colors duration-300">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>