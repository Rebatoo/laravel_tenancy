<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LaundryHub') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-100 via-white to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
            <!-- Background Pattern -->
            <div class="absolute inset-0 z-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px] opacity-25"></div>
            
            <div class="z-10">
                <a href="/" class="flex flex-col items-center">
                    <x-application-logo class="w-20 h-20 fill-current text-blue-600 dark:text-blue-500" />
                    <span class="mt-2 text-2xl font-bold text-blue-600 dark:text-blue-500">LaundryHub</span>
                </a>
            </div>

            <div class="w-full sm:max-w-2xl mt-6 px-8 py-8 bg-white/80 dark:bg-gray-800/90 backdrop-blur-sm shadow-xl rounded-xl z-10">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
