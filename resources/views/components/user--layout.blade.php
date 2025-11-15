<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <title>SmartAppointment</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.js"></script>

    <style>
        [x-cloak] {
            display: none;
        }
        .background-farm {
            background-image: url('{{ asset('images/farm.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            width: 100%;
            filter: brightness(0.7);
        }
        .bg-primary { background-color: #1E3A8A; }
        .bg-secondary { background-color: #3B82F6; }
        .text-primary { color: #1E3A8A; }
        .text-secondary { color: #3B82F6; }
        .nav-link { color: #93C5FD; }
        .nav-link:hover { color: #1E3A8A; }
        .nav-logo { color: #3B82F6; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased h-full bg-no-repeat bg-cover">

    @php
        use Illuminate\Support\Facades\Auth;

        $user = Auth::user();
        $lang = $user->language ?? 'English';

     $translations = [
    'English' => [
        'home' => 'Home',
        'appointment' => 'Appointment',
        'status_history' => 'Status/History',
        'announcement' => 'Announcement',
        'profile' => 'Profile',
        'logout' => 'Logout',
        'system_name' => 'SMART APPOINTMENT',
    ],
    'Tagalog' => [
        'home' => 'Bahay',
        'appointment' => 'Appointment',
        'status_history' => 'Katayuan/Kasaysayan',
        'announcement' => 'Anunsyo',
        'profile' => 'Profile',
        'logout' => 'Mag-logout',
        'system_name' => 'SMART APPOINTMENT',
    ],
    'Bisaya' => [
        'home' => 'Balay',
        'appointment' => 'Appointment',
        'status_history' => 'Status/Kasaysayan',
        'announcement' => 'Pahibalo',
        'profile' => 'Profile',
        'logout' => 'Pag-logout',
        'system_name' => 'SMART APPOINTMENT',
    ],
];


        $t = $translations[$lang] ?? $translations['English'];
    @endphp

@php
    use App\Models\Appointment;


    $user = Auth::user();

    $appointmentCount = Appointment::where('user_id', $user->id)
                                   ->where('status', 'pending')
                                   ->count();
@endphp


<nav class="bg-primary border-gray-200">
    <div class="w-screen flex flex-wrap items-center justify-around mx-auto p-4">
        <!-- Logo -->
        <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="{{ asset('images/30e70ca8-ac22-4e6e-a214-bd0fda3bef73-removebg-preview.png') }}" alt="Logo" class="w-14 h-14 border-2 rounded-full">
            <label class="font-black text-white text-xl md:text-2xl nav-logo">{{ $t['system_name'] }}</label>
        </a>

        <!-- Mobile Hamburger -->
        <button data-collapse-toggle="navbar-default" type="button"
            class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-200 rounded-lg md:hidden hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-gray-200"
            aria-controls="navbar-default" aria-expanded="false">
            <span class="sr-only">Menu</span>
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M1 1h15M1 7h15M1 13h15"/>
            </svg>
        </button>

        <!-- Menu Items -->
        <div class="hidden w-full md:flex md:w-auto" id="navbar-default">
            <ul class="flex flex-col md:flex-row md:items-center md:space-x-8 font-medium mt-4 md:mt-0">
                <li>
                    <a href="{{ route('userdashboard') }}"
                       class="block py-2 px-3 text-white uppercase font-bold nav-link hover:text-blue-500">
                        {{ $t['home'] }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.appointment') }}"
                       class="block py-2 px-3 text-white uppercase font-bold nav-link hover:text-blue-500">
                        {{ $t['appointment'] }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.status') }}"
                       class="relative block py-2 px-3 text-white uppercase font-bold nav-link hover:text-blue-500">
                        {{ $t['status_history'] }}
                        @if($appointmentCount > 0)
                            <span class="absolute top-0 right-0 -mt-1 -mr-3 bg-red-600 text-white px-2 py-0.5 rounded-full text-xs">
                                {{ $appointmentCount }}
                            </span>
                        @endif
                    </a>
                </li>

                 <li>
    <a href="{{ route('user.announcement') }}" class="relative block py-2 px-3 text-white uppercase font-bold nav-link hover:text-blue-500">
        <span class="text-white uppercase">{{ $t['announcement'] }}</span>
    </a>
</li>

                <li>
                    <a href="{{ route('user.profile') }}"
                       class="block py-2 px-3 text-white uppercase font-bold nav-link hover:text-blue-500">
                        {{ $t['profile'] }}
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logouts') }}">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V5m0 14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2h4a2 2 0 012 2" />
                            </svg>
                            {{ $t['logout'] }}
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

    <div class="border-gray-200 dark:border-gray-700">
        <main>
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>
