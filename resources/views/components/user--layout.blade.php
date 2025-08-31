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

        /* Updated Blue Color Theme */
        .bg-primary {
            background-color: #1E3A8A; /* Dark blue */
        }

        .bg-secondary {
            background-color: #3B82F6; /* Lighter blue */
        }

        .text-primary {
            color: #1E3A8A;
        }

        .text-secondary {
            color: #3B82F6;
        }

        .nav-link {
            color: #93C5FD; /* Muted blue */
        }

        .nav-link:hover {
            color: #1E3A8A; /* Dark blue on hover */
        }

        .nav-logo {
            color: #3B82F6;
        }
    </style>


{{-- @wireUiScripts --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- @wireUiScripts --}}
</head>

<body class="font-sans antialiased h-full bg-no-repeat bg-cover ">
    {{-- <x-notifications position="top-left" /> --}}
    <nav class="bg-primary border-gray-200">
        <div class="flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="{{ asset('images/schedule.png') }}" alt="Violation Photo" class="w-16 h-16 border-2 rounded-full">
                <label for="" class="font-black text-white text-2xl nav-logo">SMART APPOINTMENT</label>
            </a>
            <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-200 rounded-lg md:hidden hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-gray-200" aria-controls="navbar-default" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                </svg>
            </button>
            <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0">
                    <li>
                        <a href="{{ route('userdashboard') }}" class="block py-2 px-3 text-white uppercase font-bold nav-link">Home</a>
                    </li>
                    <li>
                        <a href="{{route('user.appointment')}}" class="block py-2 px-3 text-white uppercase font-bold nav-link">Appointment</a>
                    </li>

                     <li>
                        <a href="{{route('user.status')}}" class="block py-2 px-3 text-white uppercase font-bold nav-link">Status/History</a>
                    </li>

                    <li>
                        <a href="{{route('user.profile')}}" class="mr-20 block py-2 px-3 text-white uppercase font-bold nav-link">Profile</a>
                    </li>


                <li>
                     <form method="POST" action="{{ route('logouts') }}" class="absolute top-4 right-4">
    @csrf
    <button type="submit"
        class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <!-- Logout icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V5m0 14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2h4a2 2 0 012 2" />
        </svg>
        Logout
    </button>
</form>
                </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="border-gray-200  dark:border-gray-700 ">
        <main>
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>

</html>
