@extends('layouts.admin')

@section('title', 'Add Property')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('properties.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Properties
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6" x-data="{ amenities: [] }">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Add New Property</h2>

        <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Basic Information -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 pb-2 border-b">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Property Title *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="e.g., 3 BHK Luxury Apartment in Bandra">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Property Type *</label>
                        <select name="type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Type</option>
                            <option value="apartment" {{ old('type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                            <option value="villa" {{ old('type') == 'villa' ? 'selected' : '' }}>Villa</option>
                            <option value="plot" {{ old('type') == 'plot' ? 'selected' : '' }}>Plot/Land</option>
                            <option value="commercial" {{ old('type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                            <option value="office" {{ old('type') == 'office' ? 'selected' : '' }}>Office Space</option>
                            <option value="warehouse" {{ old('type') == 'warehouse' ? 'selected' : '' }}>Warehouse</option>
                            <option value="shop" {{ old('type') == 'shop' ? 'selected' : '' }}>Shop/Showroom</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Listing Type *</label>
                        <select name="listing_type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="sale" {{ old('listing_type') == 'sale' ? 'selected' : '' }}>For Sale</option>
                            <option value="rent" {{ old('listing_type') == 'rent' ? 'selected' : '' }}>For Rent</option>
                            <option value="lease" {{ old('listing_type') == 'lease' ? 'selected' : '' }}>For Lease</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price (₹) *</label>
                        <input type="number" name="price" value="{{ old('price') }}" step="0.01" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="5000000">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Area (sqft)</label>
                        <input type="number" name="area_sqft" value="{{ old('area_sqft') }}" step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="1200">
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
                            placeholder="Building/Society name, Street">{{ old('address') }}</textarea>
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

            <!-- Property Details -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 pb-2 border-b">Property Details</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bedrooms</label>
                        <input type="number" name="bedrooms" value="{{ old('bedrooms') }}" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bathrooms</label>
                        <input type="number" name="bathrooms" value="{{ old('bathrooms') }}" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Balconies</label>
                        <input type="number" name="balconies" value="{{ old('balconies') }}" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Parking</label>
                        <input type="number" name="parking_spaces" value="{{ old('parking_spaces', 0) }}" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Amenities -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 pb-2 border-b">Amenities</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @php
                        $commonAmenities = ['Swimming Pool', 'Gym', 'Garden', 'Parking', 'Security', 'Elevator', 'Power Backup', 'Water Supply', 'Club House', 'Playground', 'CCTV', 'Intercom'];
                    @endphp
                    @foreach($commonAmenities as $amenity)
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Detailed description of the property...">{{ old('description') }}</textarea>
            </div>

            <!-- Featured Image -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                <input type="file" name="featured_image" accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Upload main property image (Max: 2MB)</p>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('properties.index') }}" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Create Property
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
