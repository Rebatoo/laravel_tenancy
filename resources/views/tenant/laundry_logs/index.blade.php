<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry Logs - {{ tenant('name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen p-6">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Laundry Logs</h1>
                    <a href="{{ route('tenant.laundry_logs.create') }}" 
                       class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">
                        Add New Laundry Log
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">Customer</th>
                                <th class="py-3 px-6 text-left">Worker</th>
                                <th class="py-3 px-6 text-left">Type</th>
                                <th class="py-3 px-6 text-left">Weight</th>
                                <th class="py-3 px-6 text-left">Price</th>
                                <th class="py-3 px-6 text-left">Status</th>
                                <th class="py-3 px-6 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm">
                            @foreach($laundryLogs as $log)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6">{{ $log->customer->name }}</td>
                                <td class="py-3 px-6">{{ $log->worker->name }}</td>
                                <td class="py-3 px-6">{{ $log->laundry_type }}</td>
                                <td class="py-3 px-6">{{ $log->weight ?? 'N/A' }}</td>
                                <td class="py-3 px-6">₱{{ number_format($log->total_price, 2) }}</td>
                                <td class="py-3 px-6">
                                    <span class="px-2 py-1 rounded-full text-xs 
                                        {{ $log->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : '' }}
                                        {{ $log->status === 'washing' ? 'bg-blue-200 text-blue-800' : '' }}
                                        {{ $log->status === 'completed' ? 'bg-green-200 text-green-800' : '' }}
                                        {{ $log->status === 'picked_up' ? 'bg-gray-200 text-gray-800' : '' }}">
                                        {{ ucfirst($log->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-6">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('tenant.laundry_logs.show', $log) }}" 
                                           class="text-blue-500 hover:text-blue-700">
                                            View
                                        </a>
                                        <a href="{{ route('tenant.laundry_logs.edit', $log) }}" 
                                           class="text-yellow-500 hover:text-yellow-700">
                                            Edit
                                        </a>
                                        <form action="{{ route('tenant.laundry_logs.destroy', $log) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this laundry log?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($laundryLogs->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        No laundry logs found. Click "Add New Laundry Log" to create one.
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html> 