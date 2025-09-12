<div class="p-6 bg-white shadow rounded-lg">
    <div class="flex justify-end mb-4">
        <button wire:click="openModal" class="bg-blue-600 text-white px-4 py-2 rounded">+ Add Requirement</button>
    </div>

    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-3 py-2">ID</th>
                <th class="px-3 py-2">Name</th>
                <th class="px-3 py-2">Service</th>
                <th class="px-3 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requirements as $req)
                <tr>
                    <td class="text-center px-3 py-2">{{ $req->id }}</td>
                    <td class="text-center px-3 py-2">{{ $req->name }}</td>
                    <td class="text-center px-3 py-2">{{ $req->service }}</td>
                    <td class="text-center px-3 py-2 space-x-2">
                        <button wire:click="openModal({{ $req->id }})" class="text-indigo-600 hover:text-indigo-900 font-semibold">Edit</button>
                        <button wire:click="delete({{ $req->id }})" class="text-red-600 hover:text-red-900 font-semibold">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-3">No requirements found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Modal --}}
    @if($showModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg p-6 w-96">
                <h2 class="text-lg font-bold mb-4">
                    {{ $isEdit ? 'Edit Requirement' : 'Add Requirement' }}
                </h2>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Requirement Name</label>
                    <input type="text" wire:model="name" class="w-full border rounded px-3 py-2 mt-1">
                    @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Service</label>
                    <input type="text" wire:model="service" class="w-full border rounded px-3 py-2 mt-1">
                    @error('service') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <button wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                    <button wire:click="save" class="px-4 py-2 bg-blue-600 text-white rounded">
                        {{ $isEdit ? 'Update' : 'Save' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
