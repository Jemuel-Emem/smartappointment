<div class="flex flex-col items-center justify-center min-h-screen bg-gradient-to-br from-blue-50 to-blue-100 p-6">
    <!-- Logo / Main Image -->
    <img src="{{asset('images/schedule.png')}}" alt="SMART Appointment Logo" class="w-32 h-32 mb-6 drop-shadow-lg">

    <!-- Heading -->
    <h1 class="text-4xl font-extrabold text-gray-800 mb-4 text-center">
        Welcome to <span class="text-blue-600">SMART Appointment System</span>
    </h1>

    <!-- Description -->
    <p class="text-lg text-gray-600 mb-8 text-center max-w-xl">
        Streamline your scheduling process with ease. Book, manage, and track appointments in one smart platform — anytime, anywhere.
    </p>

    <!-- Action Buttons -->
    <div class="flex gap-4">
        <a href="{{ route('login') }}" class="w-64 text-center px-6 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
            APPOINT NOW!
        </a>

    </div>

    <!-- Decorative Image / Illustration -->
    <div class="mt-10">
        <img src="{{asset('images/de.png')}}" alt="Appointment Illustration" class="w-full max-w-2xl drop-shadow-lg">
    </div>
</div>
