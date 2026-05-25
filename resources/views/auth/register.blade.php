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
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

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
