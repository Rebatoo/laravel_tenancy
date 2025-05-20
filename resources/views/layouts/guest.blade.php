<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'C-Laundry') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-[#003087] via-[#0057b8] to-[#003087] dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
            <!-- Background Pattern -->
            <div class="absolute inset-0 z-0 bg-[radial-gradient(#ffffff33_1px,transparent_1px)] [background-size:20px_20px] opacity-30"></div>
            
            <div class="z-10">
                <a href="/" class="flex flex-col items-center">
                    <img src="{{ asset('images/logo-laundry.png') }}" alt="C-Laundry" class="w-40 h-40 object-contain drop-shadow-xl" />
                </a>
            </div>

            <div class="w-full sm:max-w-2xl mt-6 px-8 py-8 bg-white/10 dark:bg-gray-800/20 backdrop-blur-md shadow-2xl rounded-xl z-10 border border-white/20">
                <div class="text-white">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center text-white/80 text-sm z-10">
                <p>&copy; {{ date('Y') }} C-Laundry. All rights reserved.</p>
            </div>
        </div>
    </body>
</html>
