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
        <button wire:click="approve({{ $appointment->id }})" class="px-3 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600">
            Approve
        </button>
        <button wire:click="decline({{ $appointment->id }})" class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">
            Decline
        </button>
    @elseif($appointment->status === 'approved')
        <button wire:click="complete({{ $appointment->id }})" class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">
            Completed
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
</div>
