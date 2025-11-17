@extends('layouts.admin')
@section('title', 'Create Microsite')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">Create Microsite</h1>
            <p class="text-gray-600">Build a custom landing page for your property</p>
        </div>
        <a href="{{ route('microsites.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <form action="{{ route('microsites.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Basic Information -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-semibold text-lg mb-4">Basic Information</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Select Property *</label>
                    <select name="property_id" class="w-full border rounded px-3 py-2" required>
                        <option value="">Choose a property</option>
                        @foreach($properties as $property)
                        <option value="{{ $property->id }}">{{ $property->title }} - {{ $property->city }}</option>
                        @endforeach
                    </select>
                    @error('property_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Microsite Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" 
                           class="w-full border rounded px-3 py-2" 
                           placeholder="e.g., Luxury Apartment in Downtown" required>
                    @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <textarea name="description" rows="4" 
                              class="w-full border rounded px-3 py-2"
                              placeholder="Write a compelling description for your property...">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- SEO Settings -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-semibold text-lg mb-4">
                <i class="fas fa-search text-blue-600"></i> SEO Settings
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Meta Title (60 chars recommended)</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title') }}" 
                           maxlength="60" class="w-full border rounded px-3 py-2"
                           placeholder="SEO optimized title">
                    <p class="text-xs text-gray-500 mt-1">Leave blank to use microsite title</p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Meta Description (160 chars recommended)</label>
                    <textarea name="meta_description" rows="3" maxlength="160"
                              class="w-full border rounded px-3 py-2"
                              placeholder="Brief description for search engines">{{ old('meta_description') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Keywords (comma separated)</label>
                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}"
                           class="w-full border rounded px-3 py-2"
                           placeholder="apartment, luxury, downtown, 2BHK">
                </div>
            </div>
        </div>

        <!-- Template Selection -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-semibold text-lg mb-4">Choose Template</h3>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($templates as $key => $name)
                <label class="cursor-pointer">
                    <input type="radio" name="template" value="{{ $key }}" 
                           {{ $key === 'default' ? 'checked' : '' }} class="hidden peer">
                    <div class="border-2 border-gray-300 peer-checked:border-blue-600 peer-checked:bg-blue-50 rounded-lg p-4 text-center hover:border-blue-400 transition">
                        <div class="h-24 bg-gradient-to-br from-gray-200 to-gray-300 rounded mb-2 flex items-center justify-center">
                            <i class="fas fa-eye text-3xl text-gray-600"></i>
                        </div>
                        <p class="font-medium text-sm">{{ $name }}</p>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Custom Domain (Optional) -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-semibold text-lg mb-4">Custom Domain (Optional)</h3>
            
            <div>
                <label class="block text-sm font-medium mb-1">Custom Domain</label>
                <input type="text" name="custom_domain" value="{{ old('custom_domain') }}"
                       class="w-full border rounded px-3 py-2"
                       placeholder="e.g., property.yourdomain.com">
                <p class="text-xs text-gray-500 mt-1">Configure DNS CNAME to point to your CRM domain</p>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded hover:bg-blue-700 font-semibold">
                <i class="fas fa-check"></i> Create Microsite
            </button>
            <a href="{{ route('microsites.index') }}" class="bg-gray-500 text-white px-8 py-3 rounded hover:bg-gray-600">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
