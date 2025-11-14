@extends('layouts.admin')

@section('title', $project->name)

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('projects.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Projects
        </a>
        <div class="flex gap-2">
            <a href="{{ route('projects.edit', $project) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Are you sure? All linked properties will be unlinked.')">
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
            <!-- Project Image -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                @if($project->featured_image)
                    <img src="{{ asset('storage/' . $project->featured_image) }}" 
                         alt="{{ $project->name }}" 
                         class="w-full h-96 object-cover">
                @else
                    <div class="w-full h-96 flex items-center justify-center bg-gradient-to-br from-purple-100 to-purple-200">
                        <i class="fas fa-city text-9xl text-purple-300"></i>
                    </div>
                @endif
            </div>

            <!-- Project Details -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $project->name }}</h1>
                        @if($project->builder_name)
                            <p class="text-lg text-gray-600 mb-3">by {{ $project->builder_name }}</p>
                        @endif
                        <div class="flex items-center text-gray-600 mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>{{ $project->address }}, {{ $project->city }}, {{ $project->state }} @if($project->pincode) - {{ $project->pincode }}@endif</span>
                        </div>
                        <p class="text-sm text-gray-500">Project Code: {{ $project->project_code }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold bg-{{ $project->status_color }}-100 text-{{ $project->status_color }}-800">
                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                    </span>
                </div>

                <!-- Project Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6 pb-6 border-b">
                    @if($project->total_units)
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-building text-blue-600"></i>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $project->total_units }}</p>
                        <p class="text-sm text-gray-500">Total Units</p>
                    </div>
                    @endif

                    @if($project->total_towers)
                    <div class="text-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-city text-green-600"></i>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $project->total_towers }}</p>
                        <p class="text-sm text-gray-500">Towers</p>
                    </div>
                    @endif

                    @if($project->total_area_acres)
                    <div class="text-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-expand-arrows-alt text-purple-600"></i>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $project->total_area_acres }}</p>
                        <p class="text-sm text-gray-500">Acres</p>
                    </div>
                    @endif

                    <div class="text-center">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-home text-orange-600"></i>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $project->properties->count() }}</p>
                        <p class="text-sm text-gray-500">Listed Properties</p>
                    </div>
                </div>

                @if($project->description)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">About Project</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $project->description }}</p>
                </div>
                @endif

                @if($project->amenities && count($project->amenities) > 0)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Amenities & Facilities</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($project->amenities as $amenity)
                        <div class="flex items-center text-sm text-gray-700">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span>{{ $amenity }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Linked Properties -->
            @if($project->properties->count() > 0)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Available Properties in this Project</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($project->properties as $property)
                    <a href="{{ route('properties.show', $property) }}" class="block p-4 border rounded-lg hover:shadow-md transition">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-semibold text-gray-900">{{ $property->title }}</h4>
                            <span class="px-2 py-1 text-xs rounded-full bg-{{ $property->status_color }}-100 text-{{ $property->status_color }}-800">
                                {{ ucfirst($property->status) }}
                            </span>
                        </div>
                        <p class="text-lg font-bold text-blue-600 mb-2">{{ $property->formatted_price }}</p>
                        <div class="flex items-center gap-3 text-sm text-gray-600">
                            @if($property->bedrooms)
                                <span><i class="fas fa-bed mr-1"></i>{{ $property->bedrooms }} BHK</span>
                            @endif
                            @if($property->area_sqft)
                                <span><i class="fas fa-ruler-combined mr-1"></i>{{ number_format($property->area_sqft) }} sqft</span>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Price Card -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-center mb-4">
                    <p class="text-sm text-gray-600 mb-2">Price Range</p>
                    <h2 class="text-3xl font-bold text-blue-600">{{ $project->formatted_price_range }}</h2>
                </div>
                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium">
                    <i class="fas fa-phone mr-2"></i> Contact for Details
                </button>
            </div>

            <!-- Project Info -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Project Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Type</span>
                        <span class="text-sm font-medium text-gray-900">{{ ucfirst($project->type) }}</span>
                    </div>
                    @if($project->launch_date)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Launch Date</span>
                        <span class="text-sm font-medium text-gray-900">{{ $project->launch_date->format('M Y') }}</span>
                    </div>
                    @endif
                    @if($project->possession_date)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Possession</span>
                        <span class="text-sm font-medium text-gray-900">{{ $project->possession_date->format('M Y') }}</span>
                    </div>
                    @endif
                    @if($project->rera_number)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">RERA No.</span>
                        <span class="text-sm font-medium text-gray-900">{{ $project->rera_number }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Added By</span>
                        <span class="text-sm font-medium text-gray-900">{{ $project->createdBy->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Posted On</span>
                        <span class="text-sm font-medium text-gray-900">{{ $project->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Views</span>
                        <span class="text-sm font-medium text-gray-900">{{ $project->views_count }}</span>
                    </div>
                </div>
            </div>

            <!-- Location Map Placeholder -->
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
        <p>{{ $project->address }}</p>
        <p>{{ $project->city }}, {{ $project->state }}@if($project->pincode) - {{ $project->pincode }}@endif</p>
    </div>
</div>

        </div>
    </div>
</div>
@endsection
