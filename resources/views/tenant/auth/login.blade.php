<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ tenant('name') }} - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary-color: {{ optional(\App\Models\TenantCustomization::first())->primary_color ?? '#3B82F6' }};
            --secondary-color: {{ optional(\App\Models\TenantCustomization::first())->secondary_color ?? '#1E40AF' }};
        }
        .btn-primary {
            background-color: var(--primary-color);
        }
        .btn-primary:hover {
            background-color: var(--secondary-color);
        }
        .text-primary {
            color: var(--primary-color);
        }
        .border-primary {
            border-color: var(--primary-color);
        }
        .focus-ring-primary:focus {
            --tw-ring-color: var(--primary-color);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow-md">
            <div>
                @if($customization = \App\Models\TenantCustomization::first())
                    <div class="flex justify-center mb-4">
                        <img src="{{ asset('storage/' . $customization->logo_path) }}" 
                             alt="{{ tenant('name') }}" 
                             class="h-20 w-auto object-contain"
                             onerror="this.onerror=null; this.src='{{ asset('images/default-logo.png') }}';">
                    </div>
                @else
                    <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                        {{ tenant('name') }}
                    </h2>
                @endif
                <p class="mt-2 text-center text-sm text-gray-600">
                    Tenant Portal Login
                </p>
            </div>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form class="mt-8 space-y-6" action="{{ route('tenant-login') }}" method="POST">
                @csrf
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email" class="sr-only">Email address</label>
                        <input id="email" name="email" type="email" required 
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm" 
                            placeholder="Email address"
                            value="{{ old('email') }}">
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password" required 
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm" 
                            placeholder="Password">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" 
                            class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white btn-primary hover:btn-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        Sign in to Tenant Portal
                    </button>
                </div>
            </form>

            <div class="text-center text-sm text-gray-600 mt-4">
                <p>This is the tenant portal login for {{ tenant('name') }}.</p>
                <p>For central application access, please visit <a href="http://localhost:8000/login" class="text-primary hover:text-secondary">the main login page</a>.</p>
            </div>
        </div>
    </div>
</body>
</html> 