<div class="max-w-7xl mx-auto mt-8">
    <div class="bg-white rounded-lg overflow-hidden">

        <div class="p-6">
            @if (session()->has('message'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                    {{ session('message') }}
                </div>
            @endif

            @if($appointments->isEmpty())
                <p class="text-gray-500">You have no appointments yet.</p>
            @else
                <table class="w-full border-collapse border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-200 px-4 py-2">Date</th>
                            <th class="border border-gray-200 px-4 py-2">Time</th>
                            <th class="border border-gray-200 px-4 py-2">Purpose</th>
                            <th class="border border-gray-200 px-4 py-2">Status</th>

                            <th class="border border-gray-200 px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                         <tr>
    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
    <td class="border px-4 py-2">{{ $appointment->purpose_of_appointment }}</td>
    <td class="border px-4 py-2 text-center">
        @if($appointment->status === 'approved')
            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">Approved</span>
        @elseif($appointment->status === 'pending')
            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">Pending</span>
        @elseif($appointment->status === 'declined')
            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">Declined</span>
        @elseif($appointment->status === 'cancelled')
            <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm">Cancelled</span>
        @elseif($appointment->status === 'completed')
            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">Completed</span>
        @endif
    </td>
   <td class="border px-4 py-2 space-x-2 flex justify-center">
    @if($appointment->status === 'pending' || $appointment->status === 'approved')
        <button wire:click="cancel({{ $appointment->id }})"
            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
            Cancel
        </button>
        <button wire:click="openReschedule({{ $appointment->id }})"
            class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
            Reschedule
        </button>
@elseif($appointment->status === 'completed' && !$appointment->rated)
    <button wire:click="openRatingModal({{ $appointment->id }})"
        class="px-3 py-1 bg-emerald-500 text-white rounded hover:bg-emerald-600">
        Rate Now
    </button>
@elseif($appointment->status === 'completed' && $appointment->rated)
    <span class="text-gray-400 italic">No actions</span>

    @endif
</td>

</tr>

                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
{{-- Rating Modal --}}
@if($showRatingModal)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow-lg w-96 p-6">
            <h2 class="text-lg font-bold mb-4">Rate Staff</h2>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Your Rating (1-5)</label>
                <select wire:model="rating" class="w-full border rounded px-3 py-2 mt-1">
                    <option value="">Select</option>
                    <option value="1">1 - Very Poor</option>
                    <option value="2">2 - Poor</option>
                    <option value="3">3 - Fair</option>
                    <option value="4">4 - Good</option>
                    <option value="5">5 - Excellent</option>
                </select>
            </div>

             <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Comment (optional)</label>
                <textarea wire:model="comment" rows="3"
                    class="w-full border rounded px-3 py-2 mt-1"
                    placeholder="Write your feedback here..."></textarea>
            </div>

            <div class="flex justify-end space-x-2">
                <button wire:click="$set('showRatingModal', false)"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button wire:click="submitRating"
                    class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700">Submit</button>
            </div>
        </div>
    </div>
@endif

    {{-- Reschedule Modal --}}
    @if($showRescheduleModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg w-96 p-6">
                <h2 class="text-lg font-bold mb-4">Reschedule Appointment</h2>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">New Date</label>
                    <input type="date" wire:model="rescheduleDate" class="w-full border rounded px-3 py-2 mt-1">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">New Time</label>
                    <input type="time" wire:model="rescheduleTime" class="w-full border rounded px-3 py-2 mt-1">
                </div>

                <div class="flex justify-end space-x-2">
                    <button wire:click="$set('showRescheduleModal', false)" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button wire:click="reschedule" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
                </div>
            </div>
        </div>
    @endif
</div>
