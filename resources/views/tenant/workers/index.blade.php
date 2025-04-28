<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workers - {{ tenant('name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen p-6">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Workers List</h1>
                    <a href="{{ route('tenant.workers.create') }}" 
                       class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors">
                        Add New Worker
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
                                <th class="py-3 px-6 text-left">Name</th>
                                <th class="py-3 px-6 text-left">Position</th>
                                <th class="py-3 px-6 text-left">Email</th>
                                <th class="py-3 px-6 text-left">Phone</th>
                                <th class="py-3 px-6 text-left">Created At</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm">
                            @foreach($workers as $worker)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6">{{ $worker->name }}</td>
                                <td class="py-3 px-6">{{ $worker->position }}</td>
                                <td class="py-3 px-6">{{ $worker->email ?? 'N/A' }}</td>
                                <td class="py-3 px-6">{{ $worker->phone ?? 'N/A' }}</td>
                                <td class="py-3 px-6">{{ $worker->created_at->format('F j, Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($workers->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        No workers found. Click "Add New Worker" to create one.
                    </div>
                @endif
            </div>

            <div class="mt-4">
                <a href="{{ route('tenant.home') }}" 
                   class="text-blue-500 hover:underline">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</body>
</html> 