<div class="max-w-7xl mx-auto mt-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 text-white">
            <h2 class="text-2xl font-bold">{{ $lang['my_profile'] }}</h2>
            <p class="text-sm opacity-90">{{ $lang['update_info'] }}</p>
        </div>

        <!-- Form -->
        <form wire:submit.prevent="updateProfile" class="p-6 space-y-4">
            @if (session()->has('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div>
                <label class="block font-medium text-gray-600">{{ $lang['firstname'] }}</label>
                <input type="text" wire:model="firstname" class="mt-1 w-full border rounded-lg p-2 focus:outline-none focus:ring focus:border-blue-400">
                @error('firstname') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-medium text-gray-600">{{ $lang['lastname'] }}</label>
                <input type="text" wire:model="lastname" class="mt-1 w-full border rounded-lg p-2 focus:outline-none focus:ring focus:border-blue-400">
                @error('lastname') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-medium text-gray-600">{{ $lang['middlename'] }}</label>
                <input type="text" wire:model="middlename" class="mt-1 w-full border rounded-lg p-2 focus:outline-none focus:ring focus:border-blue-400">
                @error('middlename') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-medium text-gray-600">{{ $lang['barangay'] }}</label>
                <input type="text" wire:model="barangay" class="mt-1 w-full border rounded-lg p-2 focus:outline-none focus:ring focus:border-blue-400">
                @error('barangay') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-medium text-gray-600">{{ $lang['language'] }}</label>
                <select wire:model="language" class="mt-1 w-full border rounded-lg p-2 focus:outline-none focus:ring focus:border-blue-400">
                    <option value="English">English</option>
                    <option value="Tagalog">Tagalog</option>
                    <option value="Bisaya">Bisaya</option>
                </select>
                @error('language') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-medium text-gray-600">{{ $lang['email'] }}</label>
                <input type="email" wire:model="email" class="mt-1 w-full border rounded-lg p-2 focus:outline-none focus:ring focus:border-blue-400">
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block font-medium text-gray-600">New Password</label>
                <div class="relative">
                    <input id="passwordField" type="password" wire:model.defer="password"
                        class="mt-1 w-full border rounded-lg p-2 pr-10 focus:outline-none focus:ring focus:border-blue-400"
                        placeholder="Enter new password">
                    <button type="button" onclick="togglePassword('passwordField', this)"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-500">
                        <!-- Eye icon (visible by default) -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block font-medium text-gray-600">Confirm Password</label>
                <div class="relative">
                    <input id="confirmPasswordField" type="password" wire:model.defer="password_confirmation"
                        class="mt-1 w-full border rounded-lg p-2 pr-10 focus:outline-none focus:ring focus:border-blue-400"
                        placeholder="Confirm new password">
                    <button type="button" onclick="togglePassword('confirmPasswordField', this)"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    {{ $lang['save_changes'] }}
                </button>
            </div>
        </form>
    </div>
</div>


<script>
function togglePassword(fieldId, btn) {
    const field = document.getElementById(fieldId);
    const svg = btn.querySelector('.eye-icon');

    if (field.type === 'password') {
        field.type = 'text';
        svg.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 012.14-3.362M9.88 9.88A3 3 0 0112 9c1.657 0 3 1.343 3 3 0 .697-.243 1.336-.648 1.845M15 15l3.5 3.5M9.88 9.88L5.5 5.5" />
        `;
        btn.classList.add("text-blue-500");
    } else {
        field.type = 'password';
        svg.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
        btn.classList.remove("text-blue-500");
    }
}
</script>
