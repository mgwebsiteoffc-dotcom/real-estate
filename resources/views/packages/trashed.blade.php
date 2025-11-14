@extends('layouts.admin')

@section('title', 'Deleted Packages')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Deleted Packages</h2>
            <p class="text-gray-600 mt-1">Restore or permanently delete packages</p>
        </div>
        <a href="{{ route('packages.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Packages
        </a>
    </div>

    <!-- Packages Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($packages as $package)
        <div class="bg-white rounded-lg shadow-sm border-2 border-red-200 overflow-hidden opacity-75">
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-xl font-bold text-white">{{ $package->name }}</h3>
                        <p class="text-red-100 text-sm mt-1">Deleted {{ $package->deleted_at->diffForHumans() }}</p>
                    </div>
                    <span class="px-2 py-1 bg-red-700 text-white text-xs rounded-full">Deleted</span>
                </div>
            </div>

            <div class="px-6 py-4">
                <div class="text-3xl font-bold text-gray-900">
                    ₹{{ number_format($package->price, 0) }}
                    <span class="text-lg font-normal text-gray-500">/month</span>
                </div>
                <p class="text-sm text-gray-500 mt-2">{{ $package->companies_count }} companies affected</p>
            </div>

            <div class="px-6 py-4 bg-gray-50 flex justify-between items-center">
                <form action="{{ route('packages.restore', $package->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="text-green-600 hover:text-green-800 text-sm font-medium">
                        <i class="fas fa-undo mr-1"></i> Restore
                    </button>
                </form>
                
                <form action="{{ route('packages.force-delete', $package->id) }}" method="POST" onsubmit="return confirm('Permanently delete this package? This cannot be undone!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                        <i class="fas fa-trash-alt mr-1"></i> Delete Forever
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12">
            <i class="fas fa-trash text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No deleted packages found.</p>
            <a href="{{ route('packages.index') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                Back to Packages
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection
