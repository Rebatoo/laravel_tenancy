<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Laundry Log - {{ tenant('name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h1 class="text-2xl font-bold text-center mb-6">Add New Laundry Log</h1>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('tenant.laundry_logs.store') }}">
                @csrf

                <!-- Customer -->
                <div class="mb-4">
                    <label for="customer_id" class="block text-gray-700 text-sm font-bold mb-2">Customer</label>
                    <select id="customer_id" name="customer_id" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('customer_id') border-red-500 @enderror">
                        <option value="">Select a customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Laundry Type -->
                <div class="mb-4">
                    <label for="laundry_type" class="block text-gray-700 text-sm font-bold mb-2">Laundry Type</label>
                    <select id="laundry_type" name="laundry_type" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('laundry_type') border-red-500 @enderror">
                        <option value="">Select a type</option>
                        <option value="wash_only" {{ old('laundry_type') === 'wash_only' ? 'selected' : '' }}>Wash Only</option>
                        <option value="wash_and_fold" {{ old('laundry_type') === 'wash_and_fold' ? 'selected' : '' }}>Wash & Fold</option>
                        <option value="dry_clean" {{ old('laundry_type') === 'dry_clean' ? 'selected' : '' }}>Dry Clean</option>
                        <option value="iron_only" {{ old('laundry_type') === 'iron_only' ? 'selected' : '' }}>Iron Only</option>
                    </select>
                    @error('laundry_type')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Weight -->
                <div class="mb-4">
                    <label for="weight" class="block text-gray-700 text-sm font-bold mb-2">Weight (kg)</label>
                    <input id="weight" type="number" step="0.01" name="weight" value="{{ old('weight') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('weight') border-red-500 @enderror">
                    @error('weight')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Total Price -->
                <div class="mb-4">
                    <label for="total_price" class="block text-gray-700 text-sm font-bold mb-2">Total Price (₱)</label>
                    <input id="total_price" type="number" step="0.01" name="total_price" value="{{ old('total_price') }}" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('total_price') border-red-500 @enderror">
                    @error('total_price')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                    <select id="status" name="status" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror">
                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="washing" {{ old('status') === 'washing' ? 'selected' : '' }}>Washing</option>
                        <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="picked_up" {{ old('status') === 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                        Add Laundry Log
                    </button>
                </div>
            </form>

            <div class="mt-4 text-center">
                <a href="{{ route('tenant.laundry_logs.index') }}" class="text-blue-500 hover:underline">
                    ← Back to Laundry Logs
                </a>
            </div>
        </div>
    </div>
</body>
</html> 