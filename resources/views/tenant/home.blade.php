<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Homepage - {{ tenant('name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex justify-center py-4 bg-white shadow-sm">
        @if(tenant('customizations') && tenant('customizations')['logo'])
            <img 
                src="{{ Storage::disk('tenant_assets')->url(tenant('customizations')['logo']) }}" 
                alt="{{ tenant('name') }} Logo" 
                class="h-16"
            >
        @else
            <span class="text-xl font-bold text-gray-700">{{ tenant('name') }}</span>
        @endif
    </div>

    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-4xl">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Welcome to {{ tenant('name') }}</h1>
                <form method="POST" action="{{ route('tenant-logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition-colors">
                        Logout
                    </button>
                </form>
            </div>
            
            <div class="mb-6">
                <a href="{{ route('tenant.customize') }}" 
                   class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                    </svg>
                    Customize Your App
                </a>
            </div>
            
            <div class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="text-lg font-semibold mb-2">Admin Information</h2>
                    <p class="text-gray-600"><span class="font-medium">Name:</span> {{ $admin->name }}</p>
                    <p class="text-gray-600"><span class="font-medium">Email:</span> {{ $admin->email }}</p>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="text-lg font-semibold mb-2">Tenant Information</h2>
                    <p class="text-gray-600"><span class="font-medium">Domain:</span> {{ request()->getHost() }}</p>
                    <p class="text-gray-600"><span class="font-medium">Plan:</span> 
                        <span class="px-2 py-1 rounded {{ tenant('is_premium') ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ tenant('is_premium') ? 'Premium' : 'Basic' }}
                        </span>
                    </p>
                    <p class="text-gray-600"><span class="font-medium">Status:</span> 
                        <span class="px-2 py-1 rounded {{ tenant('is_active') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ tenant('is_active') ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                    <p class="text-gray-600"><span class="font-medium">Created:</span> {{ $admin->created_at->format('F j, Y') }}</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="text-lg font-semibold mb-2">Database Information</h2>
                    <button onclick="toggleDatabaseInfo()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">
                        Show Database Info
                    </button>
                    <div id="databaseInfo" class="mt-4 hidden">
                        <p class="text-gray-600"><span class="font-medium">Database Name:</span> {{ config('database.connections.tenant.database') }}</p>
                        <p class="text-gray-600"><span class="font-medium">Database Host:</span> {{ config('database.connections.tenant.host') }}</p>
                        <p class="text-gray-600"><span class="font-medium">Database Port:</span> {{ config('database.connections.tenant.port') }}</p>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="text-lg font-semibold mb-2">Users Table</h2>
                    <button onclick="toggleUsersTable()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors">
                        Show Users Table
                    </button>
                    <div id="usersTable" class="mt-4 hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                        <th class="py-3 px-6 text-left">ID</th>
                                        <th class="py-3 px-6 text-left">Name</th>
                                        <th class="py-3 px-6 text-left">Email</th>
                                        <th class="py-3 px-6 text-left">Admin</th>
                                        <th class="py-3 px-6 text-left">Created At</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm">
                                    @foreach($users as $user)
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        <td class="py-3 px-6">{{ $user->id }}</td>
                                        <td class="py-3 px-6">{{ $user->name }}</td>
                                        <td class="py-3 px-6">{{ $user->email }}</td>
                                        <td class="py-3 px-6">{{ $user->is_admin ? 'Yes' : 'No' }}</td>
                                        <td class="py-3 px-6">{{ $user->created_at->format('F j, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="text-lg font-semibold mb-2">Admin Management</h2>
                    <a href="{{ route('tenant.admins.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">
                        Manage Admins
                    </a>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="text-lg font-semibold mb-2">Workers Management</h2>
                    <a href="{{ route('tenant.workers.index') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors">
                        Manage Workers
                    </a>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="text-lg font-semibold mb-2">Authorized Admin Management</h2>
                    <a href="{{ route('authorized-admins.index') }}" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 transition-colors">
                        Manage Authorized Admins
                    </a>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="text-lg font-semibold mb-2">Laundry Analytics</h2>
                    @if(tenant('is_premium'))
                        <a href="{{ route('tenant.analytics') }}" class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600 transition-colors">
                            View Analytics
                        </a>
                    @else
                        <div class="flex items-center space-x-2">
                            <button disabled class="bg-gray-300 text-gray-500 px-4 py-2 rounded cursor-not-allowed">
                                View Analytics
                            </button>
                            <span class="text-sm text-gray-500">(Premium Feature)</span>
                        </div>
                    @endif
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="text-lg font-semibold mb-2">App Customization</h2>
                    <a href="{{ route('tenant.customize') }}" 
                       class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                        </svg>
                        Customize Your App
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDatabaseInfo() {
            const databaseInfo = document.getElementById('databaseInfo');
            databaseInfo.classList.toggle('hidden');
        }

        function toggleUsersTable() {
            const usersTable = document.getElementById('usersTable');
            usersTable.classList.toggle('hidden');
        }
    </script>
</body>
</html> 