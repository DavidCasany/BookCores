<section>
    <header>
        <h2 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-2">
            <span>👤</span> {{ __('Informació del perfil') }}
        </h2>
        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
            {{ __("Actualitza la informació del teu compte i l'adreça de correu electrònic.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- nom -->
        <div>
            <label for="name" class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1 ml-1">{{ __('Nom') }}</label>
            <input id="name" name="name" type="text" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 transition shadow-sm py-3 px-4" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- gmail -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase text-slate-500 dark:text-slate-400 mb-1 ml-1">{{ __('Correu electrònic') }}</label>
            <input id="email" name="email" type="email" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 transition shadow-sm py-3 px-4" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl">
                    <p class="text-sm text-yellow-800 dark:text-yellow-200">
                        {{ __("La teva adreça de correu no està verificada.") }}
                        <button form="send-verification" class="underline text-sm text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __("Fes clic aquí per reenviar el correu de verificació.") }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-bold text-sm text-green-600 dark:text-green-400">
                            {{ __("S'ha enviat un nou enllaç de verificació a la teva adreça.") }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- botó -->
        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="px-6 py-2.5 bg-slate-900 dark:bg-blue-600 hover:bg-slate-700 dark:hover:bg-blue-500 text-white font-bold rounded-xl shadow-lg transition transform hover:-translate-y-0.5">
                {{ __('Guardar canvis') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 dark:text-green-400 font-bold flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ __('Guardat.') }}
                </p>
            @endif
        </div>
    </form>
</section>