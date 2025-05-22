<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tenant Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">{{ $tenant->name }}</h3>
                    <p><strong>Email:</strong> {{ $tenant->email }}</p>
                    <p><strong>Domain:</strong> {{ $tenant->domains->first()->domain ?? 'N/A' }}</p>
                    <p><strong>Plan:</strong> {{ $tenant->is_premium ? 'Premium' : 'Basic' }}</p>
                    <p><strong>Status:</strong> {{ $tenant->is_active ? 'Active' : 'Inactive' }}</p>
                    <p><strong>Verification:</strong> {{ ucfirst($tenant->verification_status) }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>