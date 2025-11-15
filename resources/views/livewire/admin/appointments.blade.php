<div class="p-6 bg-white shadow rounded-lg">

    <!-- Appointment Settings (Set Appointment Tab) -->
    <div class="flex border-b mb-4 space-x-4">
        <button wire:click="$set('activeTab', 'settings')"
            class="flex items-center px-4 py-2 text-sm font-medium {{ $activeTab === 'settings' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v16m8-8H4" />
            </svg>
            Set Appointment Limit
        </button>

        <button wire:click="$set('activeTab', 'pending')"
            class="flex items-center px-4 py-2 text-sm font-medium {{ $activeTab === 'pending' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Pending
        </button>

        <button wire:click="$set('activeTab', 'approved')"
            class="flex items-center px-4 py-2 text-sm font-medium {{ $activeTab === 'approved' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 13l4 4L19 7" />
            </svg>
            Approved
        </button>

        <button wire:click="$set('activeTab', 'history')"
            class="flex items-center px-4 py-2 text-sm font-medium {{ $activeTab === 'history' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            History
        </button>
    </div>

    <!-- TAB CONTENTS -->

    {{-- Tab 1: Set Appointment Limit --}}
    @if($activeTab === 'settings')
        <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6 space-y-4">
            <h2 class="text-lg font-bold text-gray-700 border-b pb-2">Appointment Settings</h2>

            <div class="mb-6 text-center">
                <h1 class="text-5xl font-extrabold text-blue-600">{{ $currentLimit }}</h1>
                <p class="text-gray-600 text-lg">Daily Appointment Limit</p>
            </div>

            <!-- Limit Input -->
            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-600">Daily Appointment Limit</label>
                <input type="number" wire:model="limit" min="1"
                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('limit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Timeslot Select -->
            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-600">Select Timeslot</label>
                <select wire:model="timeslot"
                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="full">Full Day</option>
                    <option value="morning">Morning</option>
                    <option value="afternoon">Afternoon</option>
                </select>
                @error('timeslot') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Date -->
            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-600">Select Date</label>
                <input type="date" wire:model="limit_date" min="{{ now()->toDateString() }}"
                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('limit_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Save Button -->
            <div class="pt-2">
                <button wire:click="saveLimit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition">
                    Save Settings
                </button>
            </div>

            <!-- Success Message -->
            @if (session()->has('success'))
                <div class="mt-2 text-green-600 text-sm font-semibold">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    @endif

    {{-- Tab 2–4: Appointments --}}
    @if(in_array($activeTab, ['pending', 'approved', 'history']))
        @php
            if ($activeTab === 'pending') {
                $filteredAppointments = $appointments->where('status', 'pending');
            } elseif ($activeTab === 'approved') {
                $filteredAppointments = $appointments->where('status', 'approved');
            } else {
                $filteredAppointments = $appointments->whereIn('status', ['declined', 'completed']);
            }
        @endphp

        @if($filteredAppointments->count())
            <div class="overflow-x-auto mt-4">
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
                        @foreach($filteredAppointments as $appointment)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $appointment->user->firstname ?? 'N/A' }} {{ $appointment->user->lastname ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $appointment->staff->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $appointment->purpose_of_appointment }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if($appointment->status === 'pending')
                                        <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-700 rounded-full">Pending</span>
                                    @elseif($appointment->status === 'approved')
                                        <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">Approved</span>
                                    @elseif($appointment->status === 'completed')
                                        <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">Completed</span>
                                    @elseif($appointment->status === 'declined')
                                        <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded-full">Declined</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 flex gap-2">
                                    @if($appointment->status === 'pending')
                                        <button wire:click="approve({{ $appointment->id }})"
                                            class="px-3 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600">Approve</button>
                                        <button wire:click="decline({{ $appointment->id }})"
                                            class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Decline</button>
                                    @elseif($appointment->status === 'approved')
                                        <button wire:click="complete({{ $appointment->id }})"
                                            class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">Complete</button>
                                        <button wire:click="openReschedule({{ $appointment->id }})"
                                            class="px-3 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">Reschedule</button>
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
            <p class="text-gray-500 italic mt-4 text-center">No {{ ucfirst($activeTab) }} appointments found.</p>
        @endif
    @endif

@if($showRescheduleModal)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <h2 class="text-lg font-bold mb-4">Reschedule Appointment</h2>

          {{-- Date Picker --}}
<div class="mb-4">
    <label class="block text-gray-700 mb-2">Date</label>
    <input
        type="date"
        id="rescheduleDate"
        wire:model="new_date"
        min="{{ now()->toDateString() }}"
        class="w-full border rounded px-3 py-2"
    >
    @error('new_date')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>





@if($appointmentBeingRescheduled)
    @php
        $department = $appointmentBeingRescheduled->department;
        $staffId = $appointmentBeingRescheduled->staff_id;
    @endphp
@endif

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Time</label>
                <select wire:model="new_time" class="w-full border rounded px-3 py-2">
                    <option value="">Select Time</option>

                    @php
                        $now = \Carbon\Carbon::now();
                        $morningSlots = ['08:00','08:30','09:00','09:30','10:00','10:30','11:00'];
                        $afternoonSlots = ['13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30'];
                        $times = array_merge($morningSlots, $afternoonSlots);

                        $limitRecord = null;

                        if (isset($appointmentBeingRescheduled) && $appointmentBeingRescheduled) {
                            $department = \App\Models\Department::find($appointmentBeingRescheduled->department_id);
                            $adminUserId = optional($department)->user_id;

                            if ($adminUserId && $new_date) {
                                $limitRecord = \App\Models\AppointmentLimit::where('user_id', $adminUserId)
                                    ->whereDate('limit_date', $new_date)
                                    ->first();
                            }
                        }

                        if ($limitRecord && $limitRecord->timeslot === 'morning') {
                            $times = $morningSlots;
                        } elseif ($limitRecord && $limitRecord->timeslot === 'afternoon') {
                            $times = $afternoonSlots;
                        }
                    @endphp

                 @foreach ($times as $time)
    @php
        $checkDate = $new_date ?? $now->toDateString();
        $timeCarbon = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $checkDate . ' ' . $time);
        $isPastTime = ($new_date === $now->toDateString()) && $timeCarbon->lessThan($now);

        $isBooked = false;
        $limitReached = false;

        $isBooked = false;
if ($new_date && $staffId) {
    $isBooked = \App\Models\Appointment::where('appointment_date', $new_date)
        ->where('appointment_time', $time)
        ->where('staff_id', $staffId)
        ->where('status', 'approved')
        ->where('id', '!=', $appointmentBeingRescheduled->id)
        ->exists();
}


        if ($new_date && isset($appointmentBeingRescheduled)) {
            $departmentId = $appointmentBeingRescheduled->department_id;
            $staffId = $appointmentBeingRescheduled->staff_id;


            $isBooked = \App\Models\Appointment::where('appointment_date', $new_date)
                ->where('appointment_time', $time)
                ->where('staff_id', $staffId)
                ->where('status', 'approved')
                ->where('id', '!=', $appointmentBeingRescheduled->id)
                ->exists();


            $adminUserId = optional(\App\Models\Department::find($departmentId))->user_id;
            if ($adminUserId) {
                $limitRecord = \App\Models\AppointmentLimit::where('user_id', $adminUserId)
                    ->whereDate('limit_date', $new_date)
                    ->first();

                if ($limitRecord) {
                    $limit = $limitRecord->limit;
                    $currentCount = \App\Models\Appointment::where('department_id', $departmentId)
                        ->whereDate('appointment_date', $new_date)
                        ->whereIn('status', ['approved', 'pending'])
                        ->count();

                    $limitReached = $currentCount >= $limit;
                }
            }
        }

        $isDisabled = $isPastTime || $isBooked || $limitReached;
    @endphp

    <option value="{{ $time }}" {{ $isDisabled ? 'disabled class=text-gray-400' : '' }}>
        {{ \Carbon\Carbon::createFromFormat('H:i', $time)->format('g:i A') }}
        @if($isBooked)

        @elseif($limitReached)

        @elseif($isPastTime)

        @endif
    </option>
@endforeach


                </select>
                @error('new_time')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-2">
                <button wire:click="$set('showRescheduleModal', false)"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button wire:click="saveReschedule"
                    class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Save Changes</button>
            </div>
        </div>
    </div>
@endif



</div>
