@extends('layouts.tenant.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Customize Your Application</h1>
    
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form id="customization-form" class="space-y-6" method="POST" action="{{ route('tenant.customizations.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div>
            <label for="theme_color" class="block text-sm font-medium text-gray-700">Theme Color</label>
            <input type="color" id="theme_color" name="theme_color" 
                   value="{{ $customizations['theme_color'] ?? '#3b82f6' }}"
                   class="mt-1 block w-20 h-10">
        </div>
        
        <div>
            <label for="primary_color" class="block text-sm font-medium text-gray-700">Primary Color</label>
            <input type="color" id="primary_color" name="primary_color" 
                   value="{{ $customizations['primary_color'] ?? '#3b82f6' }}"
                   class="mt-1 block w-20 h-10">
        </div>
        
        <div>
            <label for="secondary_color" class="block text-sm font-medium text-gray-700">Secondary Color</label>
            <input type="color" id="secondary_color" name="secondary_color" 
                   value="{{ $customizations['secondary_color'] ?? '#10b981' }}"
                   class="mt-1 block w-20 h-10">
        </div>
        
        <div>
            <label for="logo_url" class="block text-sm font-medium text-gray-700">Logo URL</label>
            <input type="text" id="logo_url" name="logo_url" 
                   value="{{ $customizations['logo_url'] ?? '' }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Upload Logo</label>
            <input type="file" id="logo" name="logo" class="mt-1 block w-full">
            @if($customizations['logo_url'] ?? false)
                <div class="mt-2">
                    <img src="{{ $customizations['logo_url'] }}" alt="Current Logo" class="h-16">
                </div>
            @endif
        </div>
        
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">
            Save Customizations
        </button>
    </form>
</div>

<script>
    document.getElementById('customization-form').addEventListener('submit', function(e) {
        // Let the normal form submission happen
        // We'll handle success/error via Laravel's redirects and session flashes
    });
</script>
@endsection 