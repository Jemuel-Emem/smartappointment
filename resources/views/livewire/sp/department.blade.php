<div wire:keydown.escape="closeModal" tabindex="0" class="p-6">

    <!-- Header + Add button -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold">Departments</h2>
        <button
            wire:click="openModal"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            + Add Department
        </button>
    </div>

    <!-- Success message -->
    @if (session()->has('message'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-3 text-green-800">
            {{ session('message') }}
        </div>
    @endif

    <!-- Department table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                     <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($departments as $dept)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $dept->department_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $dept->service_type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $dept->user->email ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $dept->created_at->format('M d, Y') }}</td>
                          <td class="px-6 py-4 whitespace-nowrap  text-sm font-medium">
                <button wire:click="editDepartment({{ $dept->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                <button wire:click="deleteDepartment({{ $dept->id }})" class="text-red-600 hover:text-red-900">Delete</button>
            </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No departments yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    @if ($showModal)
        <!-- BACKDROP -->
        <div class="fixed inset-0 z-40 flex items-center justify-center">
            <div class="fixed inset-0 bg-black/50" wire:click="closeModal" aria-hidden="true"></div>

            <!-- DIALOG -->
            <div
                role="dialog"
                aria-modal="true"
                class="relative z-50 w-full max-w-lg mx-4"
            >
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b">
                        <h3 class="text-lg font-medium text-gray-900">Add Department</h3>
                        <button type="button" aria-label="Close" wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                            ✕
                        </button>
                    </div>

                    <form wire:submit.prevent="saveDepartment" class="px-6 py-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Department Name</label>
                                <input type="text" wire:model.defer="department_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                @error('department_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <input type="text" wire:model.defer="service_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                @error('service_type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" wire:model.defer="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Password</label>
                                <input type="password" wire:model.defer="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end space-x-3">
                            <button type="button" wire:click="closeModal" class="px-4 py-2 rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200">Cancel</button>
                            <button type="submit" class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

</div>
