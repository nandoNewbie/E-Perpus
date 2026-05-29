<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Login Key -->
        <!-- Input NIS / NIP untuk Anggota -->
        <div>
            <x-input-label for="login_key" :value="__('NIS / NIP')" />
            <x-text-input id="login_key" class="block mt-1 w-full" type="text" name="login_key" :value="old('login_key')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('login_key')" class="mt-2" />
        </div>

        <!-- Password -->

        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
    
        <div class="relative mt-1">
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition pr-12" />
            
            <button type="button" onclick="togglePassword('password', 'eye-icon-login')" 
                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-indigo-600 focus:outline-none">
                <svg id="eye-icon-login" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path class="eye-open" stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path class="eye-open" stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    <path class="eye-slash hidden" stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.29 10.29 0 012.186-3.556M3.124 3.124l17.752 17.752M9.88 9.88a3 3 0 104.24 4.24M10.652 6.217A10.059 10.059 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411" />
                </svg>
            </button>
        </div>
        
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
