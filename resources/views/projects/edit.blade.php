@extends('layouts.admin')

@section('title', 'Edit Project')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Project
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Project</h2>

        <form action="{{ route('projects.update', $project) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 pb-2 border-b">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Project Name *</label>
                        <input type="text" name="name" value="{{ old('name', $project->name) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Builder Name</label>
                        <input type="text" name="builder_name" value="{{ old('builder_name', $project->builder_name) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Project Type *</label>
                        <select name="type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="residential" {{ old('type', $project->type) == 'residential' ? 'selected' : '' }}>Residential</option>
                            <option value="commercial" {{ old('type', $project->type) == 'commercial' ? 'selected' : '' }}>Commercial</option>
                            <option value="mixed" {{ old('type', $project->type) == 'mixed' ? 'selected' : '' }}>Mixed Use</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="upcoming" {{ old('status', $project->status) == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="under_construction" {{ old('status', $project->status) == 'under_construction' ? 'selected' : '' }}>Under Construction</option>
                            <option value="ready_to_move" {{ old('status', $project->status) == 'ready_to_move' ? 'selected' : '' }}>Ready to Move</option>
                            <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Units</label>
                        <input type="number" name="total_units" value="{{ old('total_units', $project->total_units) }}" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Towers</label>
                        <input type="number" name="total_towers" value="{{ old('total_towers', $project->total_towers) }}" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
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
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('address', $project->address) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                            <input type="text" name="city" value="{{ old('city', $project->city) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">State *</label>
                            <input type="text" name="state" value="{{ old('state', $project->state) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pincode</label>
                            <input type="text" name="pincode" value="{{ old('pincode', $project->pincode) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
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
                        <input type="number" name="price_min" value="{{ old('price_min', $project->price_min) }}" step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price Range Max (₹)</label>
                        <input type="number" name="price_max" value="{{ old('price_max', $project->price_max) }}" step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Launch Date</label>
                        <input type="date" name="launch_date" value="{{ old('launch_date', $project->launch_date?->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Possession Date</label>
                        <input type="date" name="possession_date" value="{{ old('possession_date', $project->possession_date?->format('Y-m-d')) }}"
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
                        $selectedAmenities = $project->amenities ?? [];
                    @endphp
                    @foreach($projectAmenities as $amenity)
                    <label class="flex items-center">
                        <input type="checkbox" name="amenities[]" value="{{ $amenity }}"
                            {{ in_array($amenity, $selectedAmenities) ? 'checked' : '' }}
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
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $project->description) }}</textarea>
            </div>

            <!-- Featured Image -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                @if($project->featured_image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $project->featured_image) }}" alt="Current Image" class="w-48 h-32 object-cover rounded-lg">
                        <p class="text-xs text-gray-500 mt-1">Current image</p>
                    </div>
                @endif
                <input type="file" name="featured_image" accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Upload new image to replace current (Max: 2MB)</p>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('projects.show', $project) }}" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update Project
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
