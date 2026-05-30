<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Identity Number --}}
        <div class="mt-4">
            <x-input-label for="identity_number" :value="__('NIS / NIP')" />
            <x-text-input id="identity_number" class="block mt-1 w-full" type="text" name="identity_number" :value="old('identity_number')" required autofocus />
            <x-input-error :messages="$errors->get('identity_number')" class="mt-2" />
        </div>
        {{-- Role --}}
        <div class="mt-4">
            <x-input-label for="role" :value="__('Mendaftar Sebagai')" />
            <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru / Staf</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        {{-- Class --}}
        <div class="mt-4" id="class-group">
            <x-input-label for="class" :value="__('Kelas')" />
            <x-text-input id="class" class="block mt-1 w-full" type="text" name="class" :value="old('class')" placeholder="Contoh: XII" />
            <x-input-error :messages="$errors->get('class')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <div class="relative mt-1">
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm pr-12" />
                <button type="button" onclick="togglePassword('password', 'eye-icon-reg')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-indigo-600 focus:outline-none">
                    <svg id="eye-icon-reg" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path class="eye-open" stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path class="eye-open" stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        <path class="eye-slash hidden" stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.29 10.29 0 012.186-3.556M3.124 3.124l17.752 17.752M9.88 9.88a3 3 0 104.24 4.24M10.652 6.217A10.059 10.059 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
            <div class="relative mt-1">
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm pr-12" />
                <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-confirm')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-indigo-600 focus:outline-none">
                    <svg id="eye-icon-confirm" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path class="eye-open" stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path class="eye-open" stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        <path class="eye-slash hidden" stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.29 10.29 0 012.186-3.556M3.124 3.124l17.752 17.752M9.88 9.88a3 3 0 104.24 4.24M10.652 6.217A10.059 10.059 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const roleSelect = document.getElementById('role');
        const classGroup = document.getElementById('class-group');
        const classInput = document.getElementById('class');

        function toggleClassInput() {
            if (roleSelect.value === 'siswa') {
                classGroup.style.display = 'block';
                classInput.required = true;
            } else {
                classGroup.style.display = 'none';
                classInput.required = false;
                classInput.value = ''; // Kosongkan isi jika memilih guru
            }
        }

        // Jalankan saat pertama kali halaman dimuat
        toggleClassInput();

        // Jalankan setiap kali pilihan role diubah
        roleSelect.addEventListener('change', toggleClassInput);
    });
</script>
</x-guest-layout>
