@extends('layouts.admin')

@section('title', 'Edit Package')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('packages.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Packages
        </a>
    </div>

    @php
        $existingFeatures = [];
        if (!empty($package->features)) {
            $decoded = json_decode($package->features, true);
            if (is_array($decoded) && count($decoded) > 0) {
                $existingFeatures = $decoded;
            }
        }
        if (empty($existingFeatures)) {
            $existingFeatures = [''];
        }
    @endphp

    <div class="bg-white rounded-lg shadow-sm p-6" x-data="{ features: {{ json_encode($existingFeatures) }} }">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Package</h2>

        <form action="{{ route('packages.update', $package) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Package Name *</label>
                    <input type="text" name="name" value="{{ old('name', $package->name) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $package->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price (₹/month) *</label>
                    <input type="number" name="price" value="{{ old('price', $package->price) }}" step="0.01" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Users *</label>
                        <input type="number" name="max_users" value="{{ old('max_users', $package->max_users) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Use -1 for unlimited</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Leads *</label>
                        <input type="number" name="max_leads" value="{{ old('max_leads', $package->max_leads) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Properties *</label>
                        <input type="number" name="max_properties" value="{{ old('max_properties', $package->max_properties) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Projects *</label>
                        <input type="number" name="max_projects" value="{{ old('max_projects', $package->max_projects) }}" required
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

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" {{ $package->is_active ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('packages.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update Package
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
