<div class="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg overflow-hidden">
        <div class="p-4 sm:p-6">

            @php
                $translations = [
                    'English' => [
                        'no_appointments' => 'You have no appointments yet.',
                        'date' => 'Date',
                        'time' => 'Time',
                        'purpose' => 'Purpose',
                        'status' => 'Status',
                        'action' => 'Action',
                        'cancel' => 'Cancel',
                        'reschedule' => 'Reschedule',
                        'rate_now' => 'Rate Now',
                        'no_actions' => 'No actions',
                        'approved' => 'Approved',
                        'pending' => 'Pending',
                        'declined' => 'Declined',
                        'cancelled' => 'Cancelled',
                        'completed' => 'Completed',
                        'modal_title' => 'Reschedule Appointment',
                        'date_label' => 'Date',
                        'time_label' => 'Time',
                        'modal_cancel' => 'Cancel',
                        'modal_save' => 'Save',
                    ],
                    'Tagalog' => [
                        'no_appointments' => 'Wala ka pang mga appointment.',
                        'date' => 'Petsa',
                        'time' => 'Oras',
                        'purpose' => 'Layunin',
                        'status' => 'Katayuan',
                        'action' => 'Aksyon',
                        'cancel' => 'Kanselahin',
                        'reschedule' => 'I-reschedule',
                        'rate_now' => 'Mag-rate Ngayon',
                        'no_actions' => 'Walang aksyon',
                        'approved' => 'Aprubado',
                        'pending' => 'Nakahold',
                        'declined' => 'Tinanggihan',
                        'cancelled' => 'Kanselado',
                        'completed' => 'Nakumpleto',
                        'modal_title' => 'I-reschedule ang Appointment',
                        'date_label' => 'Petsa',
                        'time_label' => 'Oras',
                        'modal_cancel' => 'Kanselahin',
                        'modal_save' => 'I-save',
                    ],
                    'Bisaya' => [
                        'no_appointments' => 'Wala pa kay mga appointment.',
                        'date' => 'Petsa',
                        'time' => 'Oras',
                        'purpose' => 'Katuyoan',
                        'status' => 'Kahimtang',
                        'action' => 'Aksyon',
                        'cancel' => 'Kansela',
                        'reschedule' => 'Reschedule',
                        'rate_now' => 'I-rate Karon',
                        'no_actions' => 'Walay aksyon',
                        'approved' => 'Gi-aprubahan',
                        'pending' => 'Pending',
                        'declined' => 'Gibalibaran',
                        'cancelled' => 'Gikansela',
                        'completed' => 'Nahuman',
                        'modal_title' => 'Reschedule Appointment',
                        'date_label' => 'Petsa',
                        'time_label' => 'Oras',
                        'modal_cancel' => 'Kansela',
                        'modal_save' => 'I-save',
                    ],
                ];

                $lang = $translations[$language] ?? $translations['English'];
            @endphp

            @if (session()->has('message'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded text-sm sm:text-base">
                    {{ session('message') }}
                </div>
            @endif

            @if($appointments->isEmpty())
                <p class="text-gray-500 text-center text-sm sm:text-base">{{ $lang['no_appointments'] }}</p>
            @else
                {{-- Table view for desktop --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-200 text-sm sm:text-base">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-200 px-4 py-2">{{ $lang['date'] }}</th>
                                <th class="border border-gray-200 px-4 py-2">{{ $lang['time'] }}</th>
                                <th class="border border-gray-200 px-4 py-2">{{ $lang['purpose'] }}</th>
                                <th class="border border-gray-200 px-4 py-2">{{ $lang['status'] }}</th>
                                <th class="border border-gray-200 px-4 py-2">{{ $lang['action'] }}</th>
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
                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">{{ $lang['approved'] }}</span>
                                        @elseif($appointment->status === 'pending')
                                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">{{ $lang['pending'] }}</span>
                                        @elseif($appointment->status === 'declined')
                                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">{{ $lang['declined'] }}</span>
                                        @elseif($appointment->status === 'cancelled')
                                            <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm">{{ $lang['cancelled'] }}</span>
                                        @elseif($appointment->status === 'completed')
                                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">{{ $lang['completed'] }}</span>
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2 space-x-2 flex justify-center">
                                        @if($appointment->status === 'pending' || $appointment->status === 'approved')
                                            <button wire:click="cancel({{ $appointment->id }})"
                                                class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                                {{ $lang['cancel'] }}
                                            </button>
                                            <button wire:click="openReschedule({{ $appointment->id }})"
                                                class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                                {{ $lang['reschedule'] }}
                                            </button>
                                        @elseif($appointment->status === 'completed' && !$appointment->rated)
                                            <button wire:click="openRatingModal({{ $appointment->id }})"
                                                class="px-3 py-1 bg-emerald-500 text-white rounded hover:bg-emerald-600">
                                                {{ $lang['rate_now'] }}
                                            </button>
                                        @elseif($appointment->status === 'completed' && $appointment->rated)
                                            <span class="text-gray-400 italic">{{ $lang['no_actions'] }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Card view for mobile --}}
                <div class="block md:hidden space-y-4">
                    @foreach($appointments as $appointment)
                        <div class="border rounded-lg p-4 shadow-sm">
                            <div class="flex justify-between">
                                <span class="font-semibold">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</span>
                                <span class="text-gray-500 text-sm">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</span>
                            </div>
                            <p class="mt-2 text-gray-700 text-sm"><strong>{{ $lang['purpose'] }}:</strong> {{ $appointment->purpose_of_appointment }}</p>
                            <p class="mt-1">
                                <strong>{{ $lang['status'] }}:</strong>
                                @if($appointment->status === 'approved')
                                    <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs">{{ $lang['approved'] }}</span>
                                @elseif($appointment->status === 'pending')
                                    <span class="bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full text-xs">{{ $lang['pending'] }}</span>
                                @elseif($appointment->status === 'declined')
                                    <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full text-xs">{{ $lang['declined'] }}</span>
                                @elseif($appointment->status === 'cancelled')
                                    <span class="bg-gray-200 text-gray-700 px-2 py-0.5 rounded-full text-xs">{{ $lang['cancelled'] }}</span>
                                @elseif($appointment->status === 'completed')
                                    <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs">{{ $lang['completed'] }}</span>
                                @endif
                            </p>
                            <div class="mt-3 flex flex-col space-y-2">
                                @if($appointment->status === 'pending' || $appointment->status === 'approved')
                                    <button wire:click="cancel({{ $appointment->id }})"
                                        class="w-full px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
                                        {{ $lang['cancel'] }}
                                    </button>
                                    <button wire:click="openReschedule({{ $appointment->id }})"
                                        class="w-full px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                                        {{ $lang['reschedule'] }}
                                    </button>
                                @elseif($appointment->status === 'completed' && !$appointment->rated)
                                    <button wire:click="openRatingModal({{ $appointment->id }})"
                                        class="w-full px-3 py-1 bg-emerald-500 text-white rounded hover:bg-emerald-600 text-sm">
                                        {{ $lang['rate_now'] }}
                                    </button>
                                @elseif($appointment->status === 'completed' && $appointment->rated)
                                    <span class="text-gray-400 italic text-sm">{{ $lang['no_actions'] }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Reschedule Modal --}}
    @if($showRescheduleModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 px-4">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-sm sm:max-w-md p-6">
                <h2 class="text-lg font-bold mb-4">{{ $lang['modal_title'] }}</h2>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">{{ $lang['date_label'] }}</label>
                    <input type="date" wire:model="rescheduleDate" class="w-full border rounded px-3 py-2 mt-1 text-sm sm:text-base">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">{{ $lang['time_label'] }}</label>
                    <select wire:model="rescheduleTime" class="w-full border rounded px-3 py-2 mt-1 text-sm sm:text-base">
                        <option value="">{{ $lang['time_label'] }}</option>
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
                </div>

                <div class="flex flex-col sm:flex-row justify-end sm:space-x-2 space-y-2 sm:space-y-0">
                    <button wire:click="$set('showRescheduleModal', false)" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 text-sm sm:text-base">{{ $lang['modal_cancel'] }}</button>
                    <button wire:click="reschedule" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm sm:text-base">{{ $lang['modal_save'] }}</button>
                </div>
            </div>
        </div>
    @endif
</div>
