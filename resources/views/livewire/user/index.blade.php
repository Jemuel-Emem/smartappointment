<div class="flex flex-col items-center justify-center min-h-screen bg-gradient-to-br from-blue-50 to-blue-100 p-6">

    <img src="{{ asset('images/schedule.png') }}" alt="SMART Appointment Logo" class="w-32 h-32 mb-6 drop-shadow-lg">

    <h1 class="text-4xl font-extrabold text-gray-800 mb-4 text-center">
        {!! $translations['title'] !!}
    </h1>

    <p class="text-lg text-gray-600 mb-8 text-center max-w-xl">
        {{ $translations['subtitle'] }}
    </p>

    <div class="flex gap-4">
        <a href="{{ route('user.appointment') }}" class="w-64 text-center px-6 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
            {{ $translations['button'] }}
        </a>
    </div>

    <div class="mt-10">
        <img src="{{ asset('images/de.png') }}" alt="Appointment Illustration" class="w-full max-w-2xl drop-shadow-lg">
    </div>
</div>
