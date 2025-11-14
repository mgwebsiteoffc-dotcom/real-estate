@extends('layouts.admin')

@section('title', 'Properties')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Properties</h2>
            <p class="text-gray-600 mt-1">Manage your property listings</p>
        </div>
        <a href="{{ route('properties.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Add Property
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <form method="GET" action="{{ route('properties.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Search properties...">
            </div>
            <div>
                <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Types</option>
                    <option value="apartment" {{ request('type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                    <option value="villa" {{ request('type') == 'villa' ? 'selected' : '' }}>Villa</option>
                    <option value="plot" {{ request('type') == 'plot' ? 'selected' : '' }}>Plot/Land</option>
                    <option value="commercial" {{ request('type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                    <option value="office" {{ request('type') == 'office' ? 'selected' : '' }}>Office</option>
                </select>
            </div>
            <div>
                <select name="listing_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Sale/Rent</option>
                    <option value="sale" {{ request('listing_type') == 'sale' ? 'selected' : '' }}>For Sale</option>
                    <option value="rent" {{ request('listing_type') == 'rent' ? 'selected' : '' }}>For Rent</option>
                    <option value="lease" {{ request('listing_type') == 'lease' ? 'selected' : '' }}>For Lease</option>
                </select>
            </div>
            <div>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                    <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>Rented</option>
                    <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('properties.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Properties Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($properties as $property)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition">
            <!-- Property Image -->
            <div class="relative h-48 bg-gray-200">
                @if($property->featured_image)
                    <img src="{{ asset('storage/' . $property->featured_image) }}" 
                         alt="{{ $property->title }}" 
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200">
                        <i class="fas fa-building text-6xl text-blue-300"></i>
                    </div>
                @endif
                
                <!-- Status Badge -->
                <div class="absolute top-3 left-3">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-{{ $property->status_color }}-100 text-{{ $property->status_color }}-800">
                        {{ ucfirst($property->status) }}
                    </span>
                </div>

                <!-- Listing Type Badge -->
                <div class="absolute top-3 right-3">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                        For {{ ucfirst($property->listing_type) }}
                    </span>
                </div>

                @if($property->is_featured)
                <div class="absolute bottom-3 left-3">
                    <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-400 text-yellow-900">
                        <i class="fas fa-star mr-1"></i> Featured
                    </span>
                </div>
                @endif
            </div>

            <!-- Property Details -->
            <div class="p-4">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="font-semibold text-lg text-gray-900 line-clamp-1">{{ $property->title }}</h3>
                        <p class="text-sm text-gray-500">{{ $property->getPropertyTypeLabel() }}</p>
                    </div>
                </div>

                <div class="flex items-center text-sm text-gray-600 mb-3">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    <span class="line-clamp-1">{{ $property->city }}, {{ $property->state }}</span>
                </div>

                <!-- Property Features -->
                <div class="flex items-center gap-4 text-sm text-gray-600 mb-3 pb-3 border-b">
                    @if($property->bedrooms)
                    <div class="flex items-center">
                        <i class="fas fa-bed mr-1"></i>
                        <span>{{ $property->bedrooms }} BHK</span>
                    </div>
                    @endif
                    @if($property->bathrooms)
                    <div class="flex items-center">
                        <i class="fas fa-bath mr-1"></i>
                        <span>{{ $property->bathrooms }}</span>
                    </div>
                    @endif
                    @if($property->area_sqft)
                    <div class="flex items-center">
                        <i class="fas fa-ruler-combined mr-1"></i>
                        <span>{{ number_format($property->area_sqft) }} sqft</span>
                    </div>
                    @endif
                </div>

                <!-- Price & Actions -->
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-2xl font-bold text-blue-600">{{ $property->formatted_price }}</p>
                        @if($property->price_per_sqft)
                            <p class="text-xs text-gray-500">₹{{ number_format($property->price_per_sqft) }}/sqft</p>
                        @endif
                    </div>
                    <a href="{{ route('properties.show', $property) }}" 
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12">
            <i class="fas fa-building text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No properties found. Add your first property!</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $properties->links() }}
    </div>
</div>
@endsection
