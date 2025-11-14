@extends('layouts.admin')

@section('title', 'Create Package')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('packages.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Packages
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6" x-data="{ features: [''] }">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Create New Package</h2>

        <form action="{{ route('packages.store') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <!-- Package Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Package Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g., Basic, Professional, Enterprise">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="Short description">{{ old('description') }}</textarea>
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price (₹/month) *</label>
                    <input type="number" name="price" value="{{ old('price') }}" step="0.01" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="999.00">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Users *</label>
                        <input type="number" name="max_users" value="{{ old('max_users', 5) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Use -1 for unlimited</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Leads *</label>
                        <input type="number" name="max_leads" value="{{ old('max_leads', 1000) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Properties *</label>
                        <input type="number" name="max_properties" value="{{ old('max_properties', 100) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Projects *</label>
                        <input type="number" name="max_projects" value="{{ old('max_projects', 10) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Dynamic Features -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Features</label>
                    <div class="space-y-2">
                        <template x-for="(feature, index) in features" :key="index">
                            <div class="flex gap-2">
                                <input 
                                    type="text" 
                                    :name="'features[' + index + ']'" 
                                    x-model="features[index]"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                    placeholder="e.g., Email Support"
                                >
                                <button 
                                    type="button" 
                                    @click="features.length > 1 ? features.splice(index, 1) : null"
                                    class="px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg"
                                    :class="{ 'opacity-50 cursor-not-allowed': features.length === 1 }"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                    <button 
                        type="button" 
                        @click="features.push('')"
                        class="mt-2 px-4 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-sm font-medium"
                    >
                        <i class="fas fa-plus mr-1"></i> Add Feature
                    </button>
                </div>

                <!-- Is Active -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" checked
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('packages.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Create Package
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
