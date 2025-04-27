<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Homepage</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h1 class="text-2xl font-bold text-center mb-6">Welcome to {{ tenant('name') }}</h1>
            
            <div class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="text-lg font-semibold mb-2">Admin Information</h2>
                    <p class="text-gray-600"><span class="font-medium">Name:</span> {{ $admin->name }}</p>
                    <p class="text-gray-600"><span class="font-medium">Email:</span> {{ $admin->email }}</p>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="text-lg font-semibold mb-2">Tenant Information</h2>
                    <p class="text-gray-600"><span class="font-medium">Domain:</span> {{ request()->getHost() }}</p>
                    <p class="text-gray-600"><span class="font-medium">Created:</span> {{ $admin->created_at->format('F j, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 