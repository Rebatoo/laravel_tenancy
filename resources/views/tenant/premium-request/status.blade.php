<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Request Status - {{ tenant('name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen p-6">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Premium Request Status</h1>
                <a href="{{ route('tenant.home') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition-colors">
                    Back to Dashboard
                </a>
            </div>

            @if($request)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-2">Request Details</h2>
                        <p class="text-gray-600 mb-4">{{ $request->message }}</p>
                        <p class="text-sm text-gray-500">Submitted on: {{ $request->created_at->format('F j, Y H:i') }}</p>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status</h3>
                        <div class="flex items-center mb-4">
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($request->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                   'bg-red-100 text-red-800') }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </div>

                        @if($request->admin_response)
                            <div class="mt-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Admin Response:</h4>
                                <p class="text-gray-600">{{ $request->admin_response }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <p class="text-gray-600 mb-4">You haven't submitted any premium requests yet.</p>
                    <a href="{{ route('tenant.premium-request.create') }}" class="inline-block bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 transition-colors">
                        Request Premium Access
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>
</html> 