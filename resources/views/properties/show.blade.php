@extends('layouts.admin')

@section('title', $property->title)

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('properties.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Properties
        </a>
        <div class="flex gap-2">
            <a href="{{ route('properties.edit', $property) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <form action="{{ route('properties.destroy', $property) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-trash mr-2"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Property Image -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                @if($property->featured_image)
                    <img src="{{ asset('storage/' . $property->featured_image) }}" 
                         alt="{{ $property->title }}" 
                         class="w-full h-96 object-cover">
                @else
                    <div class="w-full h-96 flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200">
                        <i class="fas fa-building text-9xl text-blue-300"></i>
                    </div>
                @endif
            </div>

            <!-- Property Details -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $property->title }}</h1>
                        <div class="flex items-center text-gray-600 mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>{{ $property->address }}, {{ $property->city }}, {{ $property->state }}</span>
                        </div>
                        <p class="text-sm text-gray-500">Property Code: {{ $property->property_code }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold bg-{{ $property->status_color }}-100 text-{{ $property->status_color }}-800">
                        {{ ucfirst($property->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6 pb-6 border-b">
                    @if($property->bedrooms)
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-bed text-blue-600"></i>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $property->bedrooms }}</p>
                        <p class="text-sm text-gray-500">Bedrooms</p>
                    </div>
                    @endif

                    @if($property->bathrooms)
                    <div class="text-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-bath text-green-600"></i>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $property->bathrooms }}</p>
                        <p class="text-sm text-gray-500">Bathrooms</p>
                    </div>
                    @endif

                    @if($property->area_sqft)
                    <div class="text-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-ruler-combined text-purple-600"></i>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($property->area_sqft) }}</p>
                        <p class="text-sm text-gray-500">Sqft</p>
                    </div>
                    @endif

                    <div class="text-center">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-car text-orange-600"></i>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $property->parking_spaces }}</p>
                        <p class="text-sm text-gray-500">Parking</p>
                    </div>
                </div>

                @if($property->description)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Description</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $property->description }}</p>
                </div>
                @endif

                @if($property->amenities && count($property->amenities) > 0)
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Amenities</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($property->amenities as $amenity)
                        <div class="flex items-center text-sm text-gray-700">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>{{ $amenity }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Price Card -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-center mb-4">
                    <p class="text-sm text-gray-600 mb-2">{{ ucfirst($property->listing_type) }} Price</p>
                    <h2 class="text-4xl font-bold text-blue-600">{{ $property->formatted_price }}</h2>
                    @if($property->price_per_sqft)
                        <p class="text-sm text-gray-500 mt-2">₹{{ number_format($property->price_per_sqft) }}/sqft</p>
                    @endif
                    @if($property->price_negotiable)
                        <span class="inline-block mt-2 px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                            Negotiable
                        </span>
                    @endif
                </div>
                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium">
                    <i class="fas fa-phone mr-2"></i> Contact Now
                </button>
            </div>

            <!-- Property Info -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Property Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Type</span>
                        <span class="text-sm font-medium text-gray-900">{{ $property->getPropertyTypeLabel() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Listing Type</span>
                        <span class="text-sm font-medium text-gray-900">{{ ucfirst($property->listing_type) }}</span>
                    </div>
                    @if($property->furnishing)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Furnishing</span>
                        <span class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $property->furnishing)) }}</span>
                    </div>
                    @endif
                    @if($property->facing)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Facing</span>
                        <span class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $property->facing)) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Posted By</span>
                        <span class="text-sm font-medium text-gray-900">{{ $property->createdBy->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Posted On</span>
                        <span class="text-sm font-medium text-gray-900">{{ $property->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Views</span>
                        <span class="text-sm font-medium text-gray-900">{{ $property->views_count }}</span>
                    </div>
                </div>
            </div>

            <!-- Location Map Placeholder -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Location</h3>
                <div class="aspect-video bg-gray-200 rounded-lg flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-map-marked-alt text-4xl text-gray-400 mb-2"></i>
                        <p class="text-sm text-gray-500">Map integration coming soon</p>
                    </div>
                </div>
                <div class="mt-3 text-sm text-gray-600">
                    <p>{{ $property->address }}</p>
                    <p>{{ $property->city }}, {{ $property->state }} {{ $property->pincode }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
