<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Premium Request - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen p-6">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Review Premium Request</h1>
                <a href="{{ route('admin.premium-requests.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition-colors">
                    Back to Requests
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Request Details -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Tenant Name</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $premiumRequest->tenant->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Request Date</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $premiumRequest->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Current Status</h3>
                            <p class="mt-1">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $premiumRequest->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($premiumRequest->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                       'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($premiumRequest->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Request Message</h3>
                        <div class="bg-gray-50 rounded p-4">
                            <p class="text-sm text-gray-900">{{ $premiumRequest->message }}</p>
                        </div>
                    </div>

                    @if($premiumRequest->status === 'pending')
                        <form action="{{ route('admin.premium-requests.update', $premiumRequest) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Decision</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="approved">Approve</option>
                                    <option value="rejected">Reject</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="admin_response" class="block text-sm font-medium text-gray-700 mb-2">Response Message</label>
                                <textarea name="admin_response" id="admin_response" rows="4" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Enter your response to the tenant (minimum 10 characters)"
                                    required></textarea>
                                @error('admin_response')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                                    Submit Decision
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Admin Response</h3>
                            <div class="bg-gray-50 rounded p-4">
                                <p class="text-sm text-gray-900">{{ $premiumRequest->admin_response }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html> 