@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Welcome back, {{ Auth::user()->name }}! 👋</h2>
        <p class="text-gray-600 mt-1">Here's what's happening with your business today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Leads -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Leads</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_leads'] }}</h3>
                    <p class="text-sm text-green-600 mt-2">
                        <span class="font-medium">{{ $stats['new_leads'] }}</span> new
                    </p>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Won Leads -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Leads Won</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['won_leads'] }}</h3>
                    @if($stats['total_leads'] > 0)
                        <p class="text-sm text-gray-600 mt-2">
                            {{ number_format(($stats['won_leads'] / $stats['total_leads']) * 100, 1) }}% conversion
                        </p>
                    @endif
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-trophy text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Properties -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Properties</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_properties'] }}</h3>
                    <p class="text-sm text-gray-600 mt-2">
                        <span class="text-green-600 font-medium">{{ $stats['available_properties'] }}</span> available
                    </p>
                </div>
                <div class="w-14 h-14 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-building text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Projects -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Projects</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_projects'] }}</h3>
                    <p class="text-sm text-gray-600 mt-2">
                        <span class="font-medium">{{ $stats['team_members'] }}</span> team members
                    </p>
                </div>
                <div class="w-14 h-14 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-city text-orange-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Lists -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Lead Pipeline -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Lead Pipeline</h3>
            <div class="space-y-3">
                @php
                    $statusColors = [
                        'new' => 'blue',
                        'contacted' => 'yellow',
                        'qualified' => 'purple',
                        'proposal' => 'indigo',
                        'negotiation' => 'orange',
                        'won' => 'green',
                        'lost' => 'red',
                    ];
                    $statusLabels = [
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'qualified' => 'Qualified',
                        'proposal' => 'Proposal',
                        'negotiation' => 'Negotiation',
                        'won' => 'Won',
                        'lost' => 'Lost',
                    ];
                @endphp
                @foreach(['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'won', 'lost'] as $status)
                    @php
                        $count = $leadsData[$status] ?? 0;
                        $percentage = $stats['total_leads'] > 0 ? ($count / $stats['total_leads']) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $statusLabels[$status] }}</span>
                            <span class="text-sm text-gray-600">{{ $count }} ({{ number_format($percentage, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-{{ $statusColors[$status] }}-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 pt-4 border-t">
                <a href="{{ route('leads.kanban') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    View Kanban Board <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Performers</h3>
            <div class="space-y-4">
                @forelse($topAgents as $agent)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-blue-600 font-semibold text-sm">{{ substr($agent->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $agent->name }}</p>
                            <p class="text-xs text-gray-500">{{ $agent->role_label }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-green-600">{{ $agent->won_leads }}</p>
                        <p class="text-xs text-gray-500">Closed</p>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-4">No data available</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Leads -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Leads</h3>
                <a href="{{ route('leads.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentLeads as $lead)
                <div class="flex items-center justify-between py-3 border-b last:border-0">
                    <div>
                        <p class="font-medium text-gray-900">{{ $lead->name }}</p>
                        <p class="text-sm text-gray-500">{{ $lead->phone }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $lead->status_color }}-100 text-{{ $lead->status_color }}-800">
                            {{ $lead->status_label }}
                        </span>
                        <p class="text-xs text-gray-500 mt-1">{{ $lead->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-4">No leads yet</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Properties -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Properties</h3>
                <a href="{{ route('properties.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentProperties as $property)
                <div class="flex items-center justify-between py-3 border-b last:border-0">
                    <div>
                        <p class="font-medium text-gray-900">{{ Str::limit($property->title, 30) }}</p>
                        <p class="text-sm text-gray-500">{{ $property->city }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-blue-600">{{ $property->formatted_price }}</p>
                        <span class="px-2 py-1 text-xs rounded-full bg-{{ $property->status_color }}-100 text-{{ $property->status_color }}-800">
                            {{ ucfirst($property->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-4">No properties yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('leads.create') }}" class="flex items-center justify-center px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg text-blue-700 font-medium transition">
                <i class="fas fa-user-plus mr-2"></i> Add Lead
            </a>
            <a href="{{ route('properties.create') }}" class="flex items-center justify-center px-4 py-3 bg-green-50 hover:bg-green-100 rounded-lg text-green-700 font-medium transition">
                <i class="fas fa-building mr-2"></i> Add Property
            </a>
            <a href="{{ route('projects.create') }}" class="flex items-center justify-center px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg text-purple-700 font-medium transition">
                <i class="fas fa-city mr-2"></i> Add Project
            </a>
            <a href="{{ route('leads.kanban') }}" class="flex items-center justify-center px-4 py-3 bg-orange-50 hover:bg-orange-100 rounded-lg text-orange-700 font-medium transition">
                <i class="fas fa-columns mr-2"></i> Kanban Board
            </a>
        </div>
    </div>
</div>
@endsection
