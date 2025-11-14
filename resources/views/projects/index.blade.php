@extends('layouts.admin')

@section('title', 'Projects')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Real Estate Projects</h2>
            <p class="text-gray-600 mt-1">Manage builder projects and developments</p>
        </div>
        <a href="{{ route('projects.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Add Project
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <form method="GET" action="{{ route('projects.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Search projects...">
            </div>
            <div>
                <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Types</option>
                    <option value="residential" {{ request('type') == 'residential' ? 'selected' : '' }}>Residential</option>
                    <option value="commercial" {{ request('type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                    <option value="mixed" {{ request('type') == 'mixed' ? 'selected' : '' }}>Mixed Use</option>
                </select>
            </div>
            <div>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="under_construction" {{ request('status') == 'under_construction' ? 'selected' : '' }}>Under Construction</option>
                    <option value="ready_to_move" {{ request('status') == 'ready_to_move' ? 'selected' : '' }}>Ready to Move</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div>
                <input type="text" name="city" value="{{ request('city') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="City">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('projects.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Projects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($projects as $project)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition">
            <!-- Project Image -->
            <div class="relative h-56 bg-gray-200">
                @if($project->featured_image)
                    <img src="{{ asset('storage/' . $project->featured_image) }}" 
                         alt="{{ $project->name }}" 
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-100 to-purple-200">
                        <i class="fas fa-city text-7xl text-purple-300"></i>
                    </div>
                @endif
                
                <!-- Status Badge -->
                <div class="absolute top-3 left-3">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-{{ $project->status_color }}-100 text-{{ $project->status_color }}-800">
                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                    </span>
                </div>

                @if($project->is_featured)
                <div class="absolute top-3 right-3">
                    <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-400 text-yellow-900">
                        <i class="fas fa-star mr-1"></i> Featured
                    </span>
                </div>
                @endif

                <!-- Type Badge -->
                <div class="absolute bottom-3 right-3">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-white text-gray-800">
                        {{ ucfirst($project->type) }}
                    </span>
                </div>
            </div>

            <!-- Project Details -->
            <div class="p-5">
                <h3 class="font-bold text-xl text-gray-900 mb-1 line-clamp-1">{{ $project->name }}</h3>
                @if($project->builder_name)
                    <p class="text-sm text-gray-600 mb-3">by {{ $project->builder_name }}</p>
                @endif

                <div class="flex items-center text-sm text-gray-600 mb-4">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    <span class="line-clamp-1">{{ $project->city }}, {{ $project->state }}@if($project->pincode) - {{ $project->pincode }}@endif</span>
                </div>

                <!-- Project Stats -->
                <div class="grid grid-cols-3 gap-3 mb-4 pb-4 border-b">
                    @if($project->total_units)
                    <div class="text-center">
                        <p class="text-lg font-bold text-gray-900">{{ $project->total_units }}</p>
                        <p class="text-xs text-gray-500">Total Units</p>
                    </div>
                    @endif

                                        @if($project->total_towers)
                    <div class="text-center">
                        <p class="text-lg font-bold text-gray-900">{{ $project->total_towers }}</p>
                        <p class="text-xs text-gray-500">Total Units</p>
                    </div>
                    @endif

                    <div class="text-center">
                        <p class="text-lg font-bold text-blue-600">{{ $project->properties_count }}</p>
                        <p class="text-xs text-gray-500">Listed</p>
                    </div>
                </div>

                <!-- Price & Action -->
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Starting from</p>
                        <p class="text-xl font-bold text-blue-600">
                            @if($project->price_min)
                                {{ $project->formatted_price_range }}
                            @else
                                <span class="text-sm">Price on Request</span>
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('projects.show', $project) }}" 
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12">
            <i class="fas fa-city text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No projects found. Add your first project!</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $projects->links() }}
    </div>
</div>
@endsection
