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
                <label class="block font-medium text-gray-600">{{ $lang['email'] }}</label>
                <input type="email" wire:model="email" class="mt-1 w-full border rounded-lg p-2 focus:outline-none focus:ring focus:border-blue-400">
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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

            <div class="pt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    {{ $lang['save_changes'] }}
                </button>
            </div>
        </form>
    </div>
</div>
