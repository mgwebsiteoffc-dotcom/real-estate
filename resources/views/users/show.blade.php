@extends('layouts.admin')

@section('title', 'User Profile')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('users.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Team
        </a>
        <div class="flex gap-2">
            <a href="{{ route('users.edit', $user) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            @if(Auth::id() !== $user->id)
            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-trash mr-2"></i> Delete
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-blue-600 font-bold text-3xl">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                <p class="text-gray-600 mt-1">{{ $user->role_label }}</p>
                
                <div class="mt-4">
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        {{ $user->status == 'active' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $user->status == 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}
                        {{ $user->status == 'suspended' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst($user->status) }}
                    </span>
                </div>

                <div class="mt-6 pt-6 border-t text-left space-y-3">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-envelope w-5 text-gray-400"></i>
                        <span class="ml-3">{{ $user->email }}</span>
                    </div>
                    @if($user->phone)
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-phone w-5 text-gray-400"></i>
                        <span class="ml-3">{{ $user->phone }}</span>
                    </div>
                    @endif
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-building w-5 text-gray-400"></i>
                        <span class="ml-3">{{ $user->company->name }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-calendar w-5 text-gray-400"></i>
                        <span class="ml-3">Joined {{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    @if($user->last_login_at)
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-clock w-5 text-gray-400"></i>
                        <span class="ml-3">Last login {{ $user->last_login_at->diffForHumans() }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stats & Activity -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total Leads</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_leads'] }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Leads Won</p>
                            <h3 class="text-3xl font-bold text-green-600 mt-2">{{ $stats['leads_won'] }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-trophy text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Active Leads</p>
                            <h3 class="text-3xl font-bold text-orange-600 mt-2">{{ $stats['leads_active'] }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-orange-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Assigned Leads -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Assigned Leads</h3>
                <div class="space-y-3">
                    @forelse($user->assignedLeads()->latest()->limit(5)->get() as $lead)
                    <div class="flex items-center justify-between py-3 border-b last:border-0">
                        <div>
                            <p class="font-medium text-gray-900">{{ $lead->name }}</p>
                            <p class="text-sm text-gray-500">{{ $lead->phone }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-{{ $lead->status_color }}-100 text-{{ $lead->status_color }}-800">
                                {{ $lead->status_label }}
                            </span>
                            <a href="{{ route('leads.show', $lead) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-4">No leads assigned yet</p>
                    @endforelse
                </div>
                
                @if($user->assignedLeads()->count() > 5)
                <div class="mt-4 text-center">
                    <a href="{{ route('leads.index', ['assigned_to' => $user->id]) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View All Leads <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                @endif
            </div>

            <!-- Performance Chart Placeholder -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Performance Overview</h3>
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <i class="fas fa-chart-bar text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Performance charts coming soon!</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
