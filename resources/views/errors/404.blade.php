<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-6xl font-bold text-blue-600 mb-4">404</h1>
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Page Not Found</h2>
            <p class="text-gray-600 mb-8">The page you're looking for doesn't exist or has been moved.</p>
            <a href="{{ route('homepage') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                Go to Homepage
            </a>
        </div>
    </div>
</body>
</html> 