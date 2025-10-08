<div class="p-6">
    <!-- Add Staff Button -->
    <div class="flex justify-end">
        <button wire:click="openModal" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded w-64">
            Add Staff
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mt-2 mb-4 rounded-md bg-green-50 border border-green-200 p-3 text-green-800">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mt-2 mb-4 rounded-md bg-red-50 border border-red-200 p-3 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="mt-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Speciality</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Availability</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($staffList as $staff)
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $staff->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $staff->address }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $staff->phone_number }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $staff->service_type }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $staff->speciality }}</td>

                        <!-- Availability -->
                        <td class="px-6 py-4 text-sm">
                            @if($staff->availability)
                                <button wire:click="toggleAvailability({{ $staff->id }})"
                                    class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                                    Available
                                </button>
                            @else
                                <button wire:click="toggleAvailability({{ $staff->id }})"
                                    class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                    Not Available
                                </button>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-sm text-gray-700 space-x-2">
                            <button wire:click="editStaff({{ $staff->id }})"
                                class="text-indigo-600 hover:text-indigo-900 font-semibold">Edit</button>
                            <button wire:click="confirmDelete({{ $staff->id }})"
                                class="text-red-600 hover:text-red-900 font-semibold">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No staff found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Add/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                <h2 class="text-lg font-bold mb-4">
                    {{ $staffIdBeingEdited ? 'Edit Staff' : 'Add Staff' }}
                </h2>

                <div class="mb-3">
                    <label class="block text-sm font-medium">Name</label>
                    <input type="text" wire:model="name" class="w-full border rounded p-2">
                    @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium">Address</label>
                    <input type="text" wire:model="address" class="w-full border rounded p-2">
                    @error('address') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium">Phone Number</label>
                    <input type="text" wire:model="phone_number" class="w-full border rounded p-2">
                    @error('phone_number') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium">Service</label>
                    <input type="text" wire:model="service" class="w-full border rounded p-2">
                    @error('service') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium">Speciality</label>
                    <input type="text" wire:model="speciality" class="w-full border rounded p-2">
                    @error('speciality') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium">Requirement Name</label>
                    <input type="text" wire:model="requirement" class="w-full border rounded p-2">
                    @error('requirement') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end">
                    <button wire:click="$set('showModal', false)" class="px-4 py-2 border rounded mr-2">Cancel</button>
                    <button wire:click="saveStaff" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
                </div>
            </div>
        </div>
    @endif


    @if($confirmingDelete)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-96 text-center">
                <h2 class="text-lg font-bold mb-3 text-red-600">Confirm Delete</h2>
                <p class="text-gray-700 mb-6">Are you sure you want to delete this staff member?</p>
                <div class="flex justify-center gap-4">
                    <button wire:click="$set('confirmingDelete', false)" class="px-4 py-2 border rounded">Cancel</button>
                    <button wire:click="deleteStaffConfirmed" class="px-4 py-2 bg-red-500 text-white rounded">Yes, Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
