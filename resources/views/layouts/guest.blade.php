<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SmartAppoint </title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-blue-100 via-white to-pink-100 min-h-screen">

    <div class="min-h-screen flex flex-col justify-center items-center px-4">
        <!-- Logo + Title -->
        <div class="flex items-center gap-3 mb-6">
            <img class="w-16 h-16" src="{{ asset('images/30e70ca8-ac22-4e6e-a214-bd0fda3bef73-removebg-preview.png') }}" alt="App Logo">
            <h1 class="text-3xl font-bold text-blue-700">SmartAppointment</h1>
        </div>

        <!-- Card Container -->
        <div class="w-full max-w-md bg-white shadow-xl rounded-xl px-8 py-6">
            {{ $slot }}
        </div>
    </div>

</body>
</html>
