<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Homepage - {{ tenant('name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
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
                    <h2 class="text-lg font-semibold mb-2">Workers Management</h2>
                    <a href="{{ route('tenant.workers.index') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors">
                        Manage Workers
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