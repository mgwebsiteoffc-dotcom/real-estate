@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Leads -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Leads</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_leads'] ?? 0 }}</h3>
                    <p class="text-sm text-green-600 mt-2">+12% from last month</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Properties -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Properties</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_properties'] ?? 0 }}</h3>
                    <p class="text-sm text-green-600 mt-2">+8% from last month</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-building text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Projects -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Projects</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_projects'] ?? 0 }}</h3>
                    <p class="text-sm text-green-600 mt-2">+5% from last month</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-folder text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Tasks -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pending Tasks</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending_tasks'] ?? 0 }}</h3>
                    <p class="text-sm text-yellow-600 mt-2">{{ $stats['today_tasks'] ?? 0 }} due today</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tasks text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Leads -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Leads</h3>
            <div class="space-y-4">
                @forelse($recentLeads ?? [] as $lead)
                <div class="flex items-center justify-between py-3 border-b last:border-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-gray-600 font-medium">{{ substr($lead->name ?? 'N/A', 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $lead->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-500">{{ $lead->email ?? 'No email' }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                        New
                    </span>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No recent leads</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activities</h3>
            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user text-blue-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-900">New lead added</p>
                        <p class="text-xs text-gray-500">2 hours ago</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-building text-green-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-900">Property listed</p>
                        <p class="text-xs text-gray-500">4 hours ago</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check text-purple-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-900">Task completed</p>
                        <p class="text-xs text-gray-500">Yesterday</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection