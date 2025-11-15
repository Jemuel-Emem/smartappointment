<div class="max-w-7xl mt-2 mx-auto bg-gradient-to-br from-blue-50 to-white p-8 rounded-2xl shadow-lg border border-blue-100">


    <h2 class="text-3xl font-bold mb-6 text-center text-blue-700">
    {{ $translations[$language]['book_title'] }}

    </h2>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="mb-6 p-4 text-green-800 bg-green-100 border border-green-200 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 text-red-800 bg-red-100 border border-red-200 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Department --}}
    <div class="mb-5">
        <label class="block text-gray-700 font-semibold mb-2">{{ $translations[$language]['select_department'] }}</label>
        <select wire:model="department_id" class="w-full border border-gray-300 rounded-lg px-4 py-2">
            <option value="">{{ $translations[$language]['choose_department'] }}</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
            @endforeach
        </select>
        @error('department_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Purpose --}}
    <div class="mb-5">
        <label class="block text-gray-700 font-semibold mb-2">{{ $translations[$language]['purpose_label'] }}</label>
   <input type="text" wire:model.live="purpose_of_appointment"
       placeholder="{{ $translations[$language]['purpose_placeholder'] }}"
       class="w-full border border-gray-300 rounded-lg px-4 py-2">
        @error('purpose_of_appointment') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror

        {{-- Loading indicator --}}
        <div wire:loading wire:target="purpose_of_appointment" class="mt-2 text-blue-600">
            Searching for staff...
        </div>
    </div>


    @if($showStaffList)
        <div class="mb-6 bg-blue-50 p-4 rounded-lg">

            <h3 class="font-semibold text-blue-800 mb-3">{{ $translations[$language]['recommended'] }}</h3>

            @error('selectedStaffId')
                <p class="text-red-500 text-sm mb-3 font-medium">⚠️ {{ $message }}</p>
            @enderror

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($suggestedStaff as $staff)
                    <div class="p-4 border rounded-lg shadow-md bg-white hover:shadow-lg transition-all
                        {{ $selectedStaffId == $staff->id ? 'border-2 border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $staff->name }}</h3>
                        <p class="text-gray-600"><span class="font-medium">Speciality:</span> {{ $staff->speciality }}</p>
                        <p class="text-gray-600"><span class="font-medium">Service:</span> {{ $staff->service_type }}</p>
                       <p class="text-gray-600">
    <span class="font-medium">Availability:</span>
    {{ $staff->availability ? 'Available' : 'Not Available' }}


</p>
    <div class="mt-4">
                        <button
                            wire:click="showComments({{ $staff->id }})"
                            class="px-4 py-1.5  text-blue-500 text-sm font-semibold ">
                            View Comments
                        </button>
                    </div>
                        <div class="mt-3 flex items-center justify-between">
                          <div class="flex items-center">
@php
    $avgRating = $staff->ratings_avg_rating ?? 0;
    $fullStars = floor($avgRating);
    $halfStar = ($avgRating - $fullStars) >= 0.5;
    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
@endphp




<div>
    Average Rating: {{ $staff->ratings_avg_rating ? number_format($staff->ratings_avg_rating, 1) : 'No ratings yet' }}
</div>

</div>

                        <button
    wire:click="selectStaff({{ $staff->id }})"
    class="px-3 py-1 rounded text-sm font-semibold
        {{ $staff->availability ? 'bg-blue-500 hover:bg-blue-600 text-white cursor-pointer' : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}"
    {{ $staff->availability ? '' : 'disabled' }}>
    {{ $staff->availability ? ($selectedStaffId == $staff->id ? 'Selected' : 'Choose') : 'Unavailable' }}
</button>

                        </div>
                    </div>
                @empty
                    <div class="col-span-full p-4 text-center bg-yellow-50 rounded-lg">
                        <p class="text-yellow-700">No specialists found matching your purpose. Try different keywords.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    @if(!is_null($remainingSlots))
    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
        <p class="text-gray-700 font-semibold">
            {{ $translations[$language]['available_slots'] ?? 'Available Slots:' }}
            <span class="text-blue-600">{{ $remainingSlots }}</span>
        </p>
    </div>
@endif

    <div class="mb-6" wire:ignore>
        <label class="block text-gray-700 font-semibold mb-2">{{ $translations[$language]['appointment_date'] }}</label>
     <div id="appointment_date_picker" class="w-full bg-white rounded-lg "></div>

        @error('appointment_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>


    <div class="mb-6">
        <label class="block text-gray-700 font-semibold mb-2">{{ $translations[$language]['appointment_time'] }}</label>
    <select wire:model="appointment_time" class="w-full border border-gray-300 rounded-lg px-4 py-2">
    <option value="">{{ $translations[$language]['select_time'] }}</option>



@php
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\AppointmentLimit;
use App\Models\Department;


$morningSlots = ['08:00','08:30','09:00','09:30','10:00','10:30','11:00'];
$afternoonSlots = ['13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30'];

$department = Department::find($department_id);
$adminUserId = optional($department)->user_id;


$limit = 15;
$timeslot = 'full';
$limitRecord = null;

if ($adminUserId && $appointment_date) {
    $limitRecord = AppointmentLimit::where('user_id', $adminUserId)
        ->whereDate('limit_date', $appointment_date)
        ->first();
}


if ($limitRecord) {
    $limit = $limitRecord->limit;
    $timeslot = $limitRecord->timeslot;
}


if ($timeslot === 'morning') {
    $times = $morningSlots;
} elseif ($timeslot === 'afternoon') {
    $times = $afternoonSlots;
} else {
    $times = array_merge($morningSlots, $afternoonSlots);
}

$now = Carbon::now();
@endphp


@foreach ($times as $time)
    @php



        $timeCarbon = Carbon::createFromFormat('Y-m-d H:i', $now->toDateString() . ' ' . $time);


        $isPastTime = ($appointment_date === $now->toDateString()) && $timeCarbon->lessThan($now);


        $isBooked = false;
        if ($appointment_date && $selectedStaffId) {
            $isBooked = Appointment::where('appointment_date', $appointment_date)
                ->where('appointment_time', $time)
                ->where('staff_id', $selectedStaffId)
                ->where('status', 'approved')
                ->exists();
        }

        $isDisabled = $isPastTime || $isBooked;
    @endphp

    <option value="{{ $time }}" {{ $isDisabled ? 'disabled class=text-gray-400' : '' }}>
        {{ \Carbon\Carbon::createFromFormat('H:i', $time)->format('g:i A') }}
        @if($isBooked)
            (Not Available)
        @endif
    </option>
@endforeach


</select>

        @error('appointment_time') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>


    <button wire:click="submit"
            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold py-3 px-6 rounded-lg">
        {{ $translations[$language]['submit'] }}
    </button>


    <script>
      document.addEventListener('DOMContentLoaded', function () {
    flatpickr("#appointment_date_picker", {
        inline: true,
        dateFormat: "Y-m-d",
        minDate: "today",
        onChange: function (selectedDates, dateStr) {
            Livewire.find(
                document.querySelector('[wire\\:id]').getAttribute('wire:id')
            ).set('appointment_date', dateStr);
        }
    });
});

    </script>


@if($showCommentModal)
<div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
        <h3 class="text-xl font-bold text-blue-700 mb-4">Comments for {{ $selectedStaffName }}</h3>

        @if(count($comments) > 0)
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @foreach($comments as $comment)
                    <div class="border-b border-gray-200 pb-2">
                        <p class="text-gray-800">{{ $comment->comment }}</p>
                        <p class="text-xs text-gray-500 mt-1">⭐ {{ $comment->rating }}/5 — {{ $comment->user->firstname ?? 'Anonymous' }}, {{ $comment->created_at->format('M d, Y') }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center">No comments available for this staff.</p>
        @endif

        <div class="mt-5 flex justify-end">
            <button wire:click="$set('showCommentModal', false)"
                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-md">
                Close
            </button>
        </div>
    </div>
</div>
@endif

</div>
