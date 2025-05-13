<nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    @if(isset($customizations['logo_url']))
                        <img class="h-8 w-auto" src="{{ $customizations['logo_url'] }}" alt="Logo">
                    @else
                        <span class="text-xl font-bold text-primary">{{ tenant('name') }}</span>
                    @endif
                </div>
            </div>

            <div class="hidden sm:ml-6 sm:flex sm:items-center">
                <!-- Navigation Links -->
                <div class="flex space-x-4">
                    <a href="{{ route('tenant.home') }}" class="text-gray-900 hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-medium">Home</a>
                    <a href="{{ route('tenant.customize') }}" class="text-gray-900 hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-medium">Customize</a>
                </div>
            </div>
        </div>
    </div>
</nav> 