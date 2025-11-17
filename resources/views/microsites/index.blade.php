@extends('layouts.admin')
@section('title', 'Microsites')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">Microsites</h1>
            <p class="text-gray-600">Create custom landing pages for your properties</p>
        </div>
        <a href="{{ route('microsites.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-plus"></i> Create Microsite
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-100 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif

    <!-- Microsites Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($microsites as $microsite)
        <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
            <!-- Preview Image -->
            <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                <div class="text-center text-white">
                    <i class="fas fa-globe text-5xl mb-2"></i>
                    <p class="text-sm font-medium">{{ ucfirst($microsite->template) }} Template</p>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="font-semibold text-lg mb-1">{{ $microsite->title }}</h3>
                        <p class="text-sm text-gray-600">{{ $microsite->property->title }}</p>
                    </div>
                    <span class="px-2 py-1 rounded text-xs font-medium {{ $microsite->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $microsite->is_published ? 'Published' : 'Draft' }}
                    </span>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-2 gap-3 mb-4 py-3 border-t border-b">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ $microsite->views }}</p>
                        <p class="text-xs text-gray-600">Views</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-green-600">{{ $microsite->leads_captured }}</p>
                        <p class="text-xs text-gray-600">Leads</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-2">
                    @if($microsite->is_published)
                    <a href="{{ route('microsite.show', $microsite->slug) }}" target="_blank" 
                       class="flex-1 text-center bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 text-sm">
                        <i class="fas fa-external-link-alt"></i> View
                    </a>
                    @endif
                    
                    <a href="{{ route('microsites.edit', $microsite) }}" 
                       class="flex-1 text-center bg-gray-600 text-white px-3 py-2 rounded hover:bg-gray-700 text-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>

                    <a href="{{ route('microsites.analytics', $microsite) }}" 
                       class="text-center bg-purple-600 text-white px-3 py-2 rounded hover:bg-purple-700 text-sm">
                        <i class="fas fa-chart-line"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 p-12 bg-white rounded-lg shadow text-center">
            <i class="fas fa-globe text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No Microsites Yet</h3>
            <p class="text-gray-500 mb-4">Create your first microsite to showcase your properties</p>
            <a href="{{ route('microsites.create') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700">
                <i class="fas fa-plus"></i> Create Your First Microsite
            </a>
        </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $microsites->links() }}
    </div>
</div>
@endsection
