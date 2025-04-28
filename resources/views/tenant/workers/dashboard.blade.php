<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Dashboard - {{ tenant('name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-6xl mx-auto px-4">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <span class="text-xl font-bold">{{ tenant('name') }}</span>
                    </div>
                    <div class="flex items-center">
                        <form method="POST" action="{{ route('tenant-logout') }}">
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="max-w-6xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-4">Welcome, {{ Auth::guard('worker')->user()->name }}!</h2>
                    <p class="text-gray-600">Position: {{ Auth::guard('worker')->user()->position }}</p>
                </div>
            </div>

            <!-- Worker Information -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Your Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ Auth::guard('worker')->user()->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <p class="mt-1 text-sm text-gray-900">{{ Auth::guard('worker')->user()->phone ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Joined Date</label>
                            <p class="mt-1 text-sm text-gray-900">{{ Auth::guard('worker')->user()->created_at->format('F j, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Management -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Customer Management</h3>
                        <a href="{{ route('tenant.customers.create') }}" 
                           class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">
                            Add New Customer
                        </a>
                    </div>
                    <div class="space-y-4">
                        <p class="text-gray-600">Manage your customers and their information.</p>
                        <a href="{{ route('tenant.customers.index') }}" 
                           class="inline-block bg-gray-100 text-gray-700 px-4 py-2 rounded hover:bg-gray-200 transition-colors">
                            View All Customers →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 