<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customize Your Portal - {{ tenant('name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen p-6">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Customize Your Portal</h1>
                <a href="{{ route('tenant.home') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition-colors">
                    Back to Dashboard
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('tenant.customization.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Logo Upload -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Logo</label>
                        @if($customization && $customization->logo_path)
                            <div class="mb-4">
                                <img src="{{ asset('storage/' . $customization->logo_path) }}" 
                                     alt="Current Logo" 
                                     class="h-20 w-auto object-contain"
                                     onerror="this.onerror=null; this.src='{{ asset('images/default-logo.png') }}';">
                            </div>
                        @endif
                        <input type="file" name="logo" accept="image/*" class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100">
                        <p class="mt-1 text-sm text-gray-500">Upload a new logo (max 2MB)</p>
                    </div>

                    <!-- Primary Color -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Primary Color</label>
                        <div class="flex items-center">
                            <input type="color" name="primary_color" value="{{ $customization->primary_color ?? '#3B82F6' }}" 
                                class="h-10 w-20 rounded cursor-pointer">
                            <input type="text" name="primary_color_text" value="{{ $customization->primary_color ?? '#3B82F6' }}" 
                                class="ml-2 px-3 py-2 border rounded" readonly>
                        </div>
                    </div>

                    <!-- Secondary Color -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Secondary Color</label>
                        <div class="flex items-center">
                            <input type="color" name="secondary_color" value="{{ $customization->secondary_color ?? '#1E40AF' }}" 
                                class="h-10 w-20 rounded cursor-pointer">
                            <input type="text" name="secondary_color_text" value="{{ $customization->secondary_color ?? '#1E40AF' }}" 
                                class="ml-2 px-3 py-2 border rounded" readonly>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Update text inputs when color inputs change
        document.querySelectorAll('input[type="color"]').forEach(input => {
            input.addEventListener('input', function() {
                this.nextElementSibling.value = this.value;
            });
        });
    </script>
</body>
</html> 