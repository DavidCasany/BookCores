<section>
    <header>
        <h2 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-2">
            <span>🔒</span> {{ __('Actualitzar contrasenya') }}
        </h2>
        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
            {{ __("Assegura't que el teu compte utilitza una contrasenya llarga i segura.") }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="current_password" class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1 ml-1">{{ __('Contrasenya actual') }}</label>
            <input id="current_password" name="current_password" type="password" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 transition shadow-sm py-3 px-4" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1 ml-1">{{ __('Nova contrasenya') }}</label>
            <input id="password" name="password" type="password" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 transition shadow-sm py-3 px-4" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1 ml-1">{{ __('Confirmar nova contrasenya') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 transition shadow-sm py-3 px-4" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="px-6 py-2.5 bg-slate-900 dark:bg-blue-600 hover:bg-slate-700 dark:hover:bg-blue-500 text-white font-bold rounded-xl shadow-lg transition transform hover:-translate-y-0.5">
                {{ __('Guardar') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 dark:text-green-400 font-bold flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ __('Guardat.') }}
                </p>
            @endif
        </div>
    </form>
</section>