<div class="p-6 bg-white shadow rounded-lg">
    @if($appointments->count())
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden shadow-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Assign Staff</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Purpose</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($appointments as $appointment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $appointment->user->name ?? 'N/A' }}</td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $appointment->staff->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $appointment->purpose_of_appointment }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 capitalize">{{ $appointment->status }}</td>
                           <td class="px-6 py-4 text-sm text-gray-700 flex gap-2">
  @if($appointment->status === 'pending')
    <button wire:click="approve({{ $appointment->id }})"
        class="px-3 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600">
        Approve
    </button>
    <button wire:click="decline({{ $appointment->id }})"
        class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">
        Decline
    </button>
@elseif($appointment->status === 'approved')
    <button wire:click="complete({{ $appointment->id }})"
        class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">
        Completed
    </button>
    <button wire:click="openReschedule({{ $appointment->id }})"
        class="px-3 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
        Reschedule
    </button>
@else
    <span class="text-gray-400 italic">No actions</span>
@endif

</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-500 italic">No appointments found.</p>
    @endif


    @if($showRescheduleModal)
<div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <h2 class="text-lg font-bold mb-4">Reschedule Appointment</h2>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">New Date</label>
        <select wire:model="new_date" class="w-full border rounded px-3 py-2 mt-1">
        <option value="">-- Select a time --</option>
         <option value="08:30">08:00 AM</option>
        <option value="08:30">08:30 AM</option>
        <option value="09:00">09:00 AM</option>
        <option value="09:30">09:30 AM</option>
        <option value="10:00">10:00 AM</option>
        <option value="10:30">10:30 AM</option>
        <option value="11:00">11:00 AM</option>
        <option value="13:00">01:00 PM</option>
        <option value="13:30">01:30 PM</option>
        <option value="14:00">02:00 PM</option>
        <option value="14:30">02:30 PM</option>
        <option value="15:00">03:00 PM</option>
        <option value="15:30">03:30 PM</option>
        <option value="16:00">04:00 PM</option>
        <option value="16:30">04:30 PM</option>
        <option value="17:00">05:00 PM</option>
    </select>
            @error('new_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">New Time</label>
            <input type="time" wire:model="new_time" class="w-full border rounded px-3 py-2">
            @error('new_time') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-2">
            <button wire:click="$set('showRescheduleModal', false)"
                class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                Cancel
            </button>
            <button wire:click="saveReschedule"
                class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                Save Changes
            </button>
        </div>
    </div>
</div>
@endif

</div>
