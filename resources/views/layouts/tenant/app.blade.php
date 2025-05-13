<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ tenant('name') }} - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Tenant Styles -->
    <style>
        :root {
            --primary-color: {{ $customizations['primary_color'] ?? '#3b82f6' }};
            --secondary-color: {{ $customizations['secondary_color'] ?? '#10b981' }};
            --theme-color: {{ $customizations['theme_color'] ?? '#3b82f6' }};
        }
        
        .bg-primary { background-color: var(--primary-color); }
        .text-primary { color: var(--primary-color); }
        .border-primary { border-color: var(--primary-color); }
        
        .bg-secondary { background-color: var(--secondary-color); }
        .text-secondary { color: var(--secondary-color); }
        .border-secondary { border-color: var(--secondary-color); }
        
        .bg-theme { background-color: var(--theme-color); }
        .text-theme { color: var(--theme-color); }
        .border-theme { border-color: var(--theme-color); }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.tenant.navigation')

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>
</body>
</html> 