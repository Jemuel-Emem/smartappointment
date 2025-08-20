<div class="max-w-7xl mx-auto mt-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 text-white">
            <h2 class="text-2xl font-bold">My Profile</h2>
            <p class="text-sm opacity-90">Update your account information below</p>
        </div>

        <!-- Form -->
        <form wire:submit.prevent="updateProfile" class="p-6 space-y-4">
            @if (session()->has('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div>
                <label class="block font-medium text-gray-600">Name</label>
                <input type="text" wire:model="name" class="mt-1 w-full border rounded-lg p-2 focus:outline-none focus:ring focus:border-blue-400">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-medium text-gray-600">Email</label>
                <input type="email" wire:model="email" class="mt-1 w-full border rounded-lg p-2 focus:outline-none focus:ring focus:border-blue-400">
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-medium text-gray-600">Barangay</label>
                <input type="text" wire:model="barangay" class="mt-1 w-full border rounded-lg p-2 focus:outline-none focus:ring focus:border-blue-400">
                @error('barangay') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-medium text-gray-600">Language</label>
                <input type="text" wire:model="language" class="mt-1 w-full border rounded-lg p-2 focus:outline-none focus:ring focus:border-blue-400">
                @error('language') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
