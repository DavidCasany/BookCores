<section class="space-y-6">
    <header>
        <h2 class="text-xl font-black text-red-600 dark:text-red-400 flex items-center gap-2">
            <span>⚠️</span> {{ __('Esborrar compte') }}
        </h2>
        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
            {{ __("Un cop esborrat el compte, totes les dades i recursos s'eliminaran permanentment. Si us plau, descarrega qualsevol dada que vulguis conservar abans de procedir.") }}
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-lg transition transform hover:-translate-y-0.5"
    >{{ __('Esborrar compte') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 bg-white dark:bg-slate-800 rounded-2xl">
            @csrf
            @method('delete')

            <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-2">
                {{ __('Estàs segur?') }}
            </h2>

            <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">
                {{ __("Un cop esborrat el compte, totes les dades s'eliminaran permanentment. Introdueix la teva contrasenya per confirmar que vols esborrar el compte de forma definitiva.") }}
            </p>

            <div class="mb-6">
                <label for="password" class="sr-only">{{ __('Contrasenya') }}</label>
                <input id="password" name="password" type="password" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:border-red-500 focus:ring-red-500 py-3 px-4" placeholder="{{ __('Contrasenya') }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-6 py-2.5 bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-white font-bold rounded-xl hover:bg-slate-300 transition">
                    {{ __('Cancel·lar') }}
                </button>

                <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-lg transition transform hover:-translate-y-0.5">
                    {{ __('Esborrar compte') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>