@extends('layouts.admin')

@section('title', 'Add Project')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('projects.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Projects
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Add New Project</h2>

        <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Basic Information -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 pb-2 border-b">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Project Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="e.g., Green Valley Apartments">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Builder Name</label>
                        <input type="text" name="builder_name" value="{{ old('builder_name') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="e.g., Lodha Group">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Project Type *</label>
                        <select name="type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Type</option>
                            <option value="residential" {{ old('type') == 'residential' ? 'selected' : '' }}>Residential</option>
                            <option value="commercial" {{ old('type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                            <option value="mixed" {{ old('type') == 'mixed' ? 'selected' : '' }}>Mixed Use</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Units</label>
                        <input type="number" name="total_units" value="{{ old('total_units') }}" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Towers</label>
                        <input type="number" name="total_towers" value="{{ old('total_towers') }}" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="5">
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 pb-2 border-b">Location</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                        <textarea name="address" rows="2" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Project location">{{ old('address') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                            <input type="text" name="city" value="{{ old('city') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="Mumbai">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">State *</label>
                            <input type="text" name="state" value="{{ old('state') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="Maharashtra">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pincode</label>
                            <input type="text" name="pincode" value="{{ old('pincode') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="400001">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing & Dates -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 pb-2 border-b">Pricing & Timeline</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price Range Min (₹)</label>
                        <input type="number" name="price_min" value="{{ old('price_min') }}" step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="5000000">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price Range Max (₹)</label>
                        <input type="number" name="price_max" value="{{ old('price_max') }}" step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="15000000">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Launch Date</label>
                        <input type="date" name="launch_date" value="{{ old('launch_date') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Possession Date</label>
                        <input type="date" name="possession_date" value="{{ old('possession_date') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Amenities -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 pb-2 border-b">Project Amenities</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @php
                        $projectAmenities = ['Swimming Pool', 'Gym & Fitness Center', 'Clubhouse', 'Children Play Area', 'Landscaped Garden', 'Jogging Track', '24x7 Security', 'CCTV Surveillance', 'Parking', 'Power Backup', 'Rainwater Harvesting', 'Solar Panels'];
                    @endphp
                    @foreach($projectAmenities as $amenity)
                    <label class="flex items-center">
                        <input type="checkbox" name="amenities[]" value="{{ $amenity }}"
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">{{ $amenity }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Project Description</label>
                <textarea name="description" rows="5"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Detailed description of the project...">{{ old('description') }}</textarea>
            </div>

            <!-- Featured Image -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                <input type="file" name="featured_image" accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Upload project cover image (Max: 2MB)</p>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('projects.index') }}" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Create Project
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
