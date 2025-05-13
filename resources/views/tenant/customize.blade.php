@extends('layouts.tenant.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Customize Your Application</h1>
    
    <form id="customization-form" class="space-y-6" enctype="multipart/form-data">
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
    document.getElementById('customization-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());
        
        try {
            const response = await fetch('/customizations', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            alert(result.message || 'Customizations saved successfully');
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to save customizations');
        }
    });
</script>
@endsection 