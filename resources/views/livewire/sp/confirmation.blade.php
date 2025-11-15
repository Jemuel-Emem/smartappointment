<div class="max-w-lg mx-auto mt-10 bg-white shadow-lg rounded-xl p-8 text-center border border-green-300">
    <div class="flex justify-center mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4 -4m6 2a9 9 0 11 -18 0a9 9 0 0118 0z" />
        </svg>
    </div>

    <h2 class="text-2xl font-extrabold text-green-700">Announcement Posted!</h2>
    <p class="mt-2 text-gray-700">Your announcement has been successfully created and sent.</p>

    <a href="{{ route('sp.announcement') }}"
        class="mt-6 inline-block px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow">
        Back to Create Announcement
    </a>
</div>
