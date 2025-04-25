<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tenants') }}
            <x-btn-link class="ml-4 float-right" href="{{ route('tenants.create') }}">Add Tenant</x-btn-link>
        </h2>
    
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
               
                    <div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-6">Name</th>
                <th scope="col" class="py-3 px-6">Email</th>
                <th scope="col" class="py-3 px-6">Domain</th>
                <th scope="col" class="py-3 px-6">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tenants as $tenant)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <td class="py-3 px-6">{{ $tenant->name }}</td>
                <td class="py-3 px-6">{{ $tenant->email }}</td> 
                <td class="py-3 px-6">
                    @foreach ($tenant->domains as $domain)
                    {{ $domain->domain }}{{ $loop->last ? '' : ', ' }}
                    @endforeach
                </td>
                <td class="py-3 px-6">
                    <button class="text-green-600 hover:underline mr-4">Accept</button>
                    <button class="text-red-600 hover:underline">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
