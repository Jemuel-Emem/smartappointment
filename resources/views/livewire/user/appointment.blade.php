<div class="max-w-7xl mt-2 mx-auto bg-gradient-to-br from-blue-50 to-white p-8 rounded-2xl shadow-lg border border-blue-100">

    {{-- Language Switcher --}}
    <div class="mb-4 flex justify-end gap-2">
        <button type="button" wire:click="$set('language', 'en')"
            class="px-4 py-2 rounded-lg border
            {{ $language === 'en' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300' }}">
            English
        </button>
        <button type="button" wire:click="$set('language', 'tl')"
            class="px-4 py-2 rounded-lg border
            {{ $language === 'tl' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300' }}">
            Tagalog
        </button>
        <button type="button" wire:click="$set('language', 'bs')"
            class="px-4 py-2 rounded-lg border
            {{ $language === 'bs' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300' }}">
            Bisaya
        </button>
    </div>

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

    {{-- Suggested Staff List --}}
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

                            <button wire:click="selectStaff({{ $staff->id }})"
                                    class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-sm">
                                {{ $selectedStaffId == $staff->id ? 'Selected' : 'Choose' }}
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
        $times = [
            '08:30', '09:00', '09:30', '10:00', '10:30', '11:00',
            '13:00', '13:30', '14:00', '14:30', '15:00', '15:30',
            '16:00', '16:30', '17:00'
        ];
    @endphp

    @foreach ($times as $time)
        <option value="{{ $time }}">{{ date('g:i A', strtotime($time)) }}</option>
    @endforeach
</select>

        @error('appointment_time') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Submit Button --}}
    <button wire:click="submit"
            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold py-3 px-6 rounded-lg">
        {{ $translations[$language]['submit'] }}
    </button>

    {{-- Datepicker Script --}}
    <script>
      document.addEventListener('DOMContentLoaded', function () {
    flatpickr("#appointment_date_picker", {
        inline: true,   // always show calendar
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
</div>
