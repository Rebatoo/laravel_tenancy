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
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="py-3 px-6">Name</th>
                                    <th scope="col" class="py-3 px-6">Email</th>
                                    <th scope="col" class="py-3 px-6">Domain</th>
                                    <th scope="col" class="py-3 px-6">Plan</th>
                                    <th scope="col" class="py-3 px-6">Status</th>
                                    <th scope="col" class="py-3 px-6">Verification</th>
                                    <th scope="col" class="py-3 px-6">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tenants as $tenant)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="py-4 px-6">{{ $tenant->name }}</td>
                                    <td class="py-4 px-6">{{ $tenant->email }}</td> 
                                    <td class="py-4 px-6">
                                        @foreach ($tenant->domains as $domain)
                                        {{ $domain->domain }}{{ $loop->last ? '' : ', ' }}
                                        @endforeach
                                    </td>
                                    <td class="py-4 px-6">
                                        <form action="{{ route('tenants.updatePlan', $tenant) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="is_premium" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                <option value="0" {{ !$tenant->is_premium ? 'selected' : '' }}>Basic</option>
                                                <option value="1" {{ $tenant->is_premium ? 'selected' : '' }}>Premium</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="py-4 px-6">
                                        <form action="{{ route('tenants.updateStatus', $tenant) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="is_active" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                <option value="1" {{ $tenant->is_active ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ !$tenant->is_active ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($tenant->verification_status === 'pending')
                                            <div class="flex space-x-2">
                                                <form action="{{ route('tenants.verify', $tenant) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="verification_status" value="verified">
                                                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                                        Verify
                                                    </button>
                                                </form>
                                                <form action="{{ route('tenants.verify', $tenant) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="verification_status" value="rejected">
                                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                                        Reject
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="px-2 py-1 rounded {{ $tenant->verification_status === 'verified' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($tenant->verification_status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        <a href="{{ route('tenants.show', $tenant) }}" class="text-blue-500 hover:underline">View</a>
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
