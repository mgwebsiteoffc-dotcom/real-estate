@extends('layouts.admin')

@section('title', 'Packages')

@section('content')
<div class="space-y-6">
    <!-- Header -->
<div class="flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Subscription Packages</h2>
        <p class="text-gray-600 mt-1">Manage pricing plans for companies</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('packages.trashed') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-trash mr-2"></i> Trash ({{ \App\Models\Package::onlyTrashed()->count() }})
        </a>
        <a href="{{ route('packages.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Add Package
        </a>
    </div>
</div>


    <!-- Packages Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($packages as $package)
        <div class="bg-white rounded-lg shadow-sm border-2 {{ $package->is_active ? 'border-blue-200' : 'border-gray-200' }} overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r {{ $package->is_active ? 'from-blue-500 to-blue-600' : 'from-gray-400 to-gray-500' }} px-6 py-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-xl font-bold text-white">{{ $package->name }}</h3>
                        <p class="text-blue-100 text-sm mt-1">{{ $package->companies_count }} companies</p>
                    </div>
                    @if($package->is_active)
                        <span class="px-2 py-1 bg-green-500 text-white text-xs rounded-full">Active</span>
                    @else
                        <span class="px-2 py-1 bg-gray-500 text-white text-xs rounded-full">Inactive</span>
                    @endif
                </div>
            </div>

            <!-- Price -->
            <div class="px-6 py-4 border-b">
                <div class="text-3xl font-bold text-gray-900">
                    ₹{{ number_format($package->price, 0) }}
                    <span class="text-lg font-normal text-gray-500">/month</span>
                </div>
                @if($package->description)
                    <p class="text-gray-600 text-sm mt-2">{{ $package->description }}</p>
                @endif
            </div>

            <!-- Features -->
            <div class="px-6 py-4 space-y-3">
                <div class="flex items-center text-sm">
                    <i class="fas fa-users w-5 text-blue-600"></i>
                    <span class="ml-2 text-gray-700">
                        <strong>{{ $package->max_users == -1 ? 'Unlimited' : $package->max_users }}</strong> Users
                    </span>
                </div>
                <div class="flex items-center text-sm">
                    <i class="fas fa-user-friends w-5 text-blue-600"></i>
                    <span class="ml-2 text-gray-700">
                        <strong>{{ $package->max_leads == -1 ? 'Unlimited' : number_format($package->max_leads) }}</strong> Leads
                    </span>
                </div>
                <div class="flex items-center text-sm">
                    <i class="fas fa-building w-5 text-blue-600"></i>
                    <span class="ml-2 text-gray-700">
                        <strong>{{ $package->max_properties == -1 ? 'Unlimited' : $package->max_properties }}</strong> Properties
                    </span>
                </div>
                <div class="flex items-center text-sm">
                    <i class="fas fa-folder w-5 text-blue-600"></i>
                    <span class="ml-2 text-gray-700">
                        <strong>{{ $package->max_projects == -1 ? 'Unlimited' : $package->max_projects }}</strong> Projects
                    </span>
                </div>

@php
    $packageFeatures = [];
    if (!empty($package->features)) {
        $decoded = json_decode($package->features, true);
        if (is_array($decoded)) {
            $packageFeatures = $decoded;
        }
    }
@endphp

@if(count($packageFeatures) > 0)
    <div class="mt-4 pt-4 border-t">
        <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Additional Features</p>
        @foreach($packageFeatures as $feature)
            <div class="flex items-center text-sm text-gray-600 mb-1">
                <i class="fas fa-check text-green-500 text-xs mr-2"></i>
                {{ $feature }}
            </div>
        @endforeach
    </div>
@endif
</div>

            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 flex justify-between items-center">
                <a href="{{ route('packages.edit', $package) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <form action="{{ route('packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Are you sure? Companies using this package will be affected.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12">
            <i class="fas fa-box text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No packages found. Create your first package!</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
