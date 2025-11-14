@extends('layouts.admin')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Super Admin Dashboard</h2>
        <p class="text-gray-600 mt-1">Overview of all companies and system-wide statistics</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Companies -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Companies</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_companies'] }}</h3>
                    <p class="text-sm text-green-600 mt-2">
                        <span class="font-medium">{{ $stats['active_companies'] }}</span> active
                    </p>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-building text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Users</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_users'] }}</h3>
                    <p class="text-sm text-gray-600 mt-2">Across all companies</p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Leads -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Leads</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_leads'] }}</h3>
                    <p class="text-sm text-gray-600 mt-2">System-wide</p>
                </div>
                <div class="w-14 h-14 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-friends text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Packages -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Active Packages</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Package::where('is_active', true)->count() }}</h3>
                    <p class="text-sm text-gray-600 mt-2">Subscription plans</p>
                </div>
                <div class="w-14 h-14 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box text-orange-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Lists -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Company Status Distribution -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Company Status Distribution</h3>
            <div class="space-y-4">
                @php
                    $statusColors = [
                        'active' => 'green',
                        'suspended' => 'red',
                        'inactive' => 'gray',
                    ];
                    $totalCompanies = $stats['total_companies'];
                @endphp
                @foreach($companiesData as $status)
                    @php
                        $percentage = $totalCompanies > 0 ? ($status->count / $totalCompanies) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700 capitalize">{{ $status->status }}</span>
                            <span class="text-sm text-gray-600">{{ $status->count }} ({{ number_format($percentage, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-{{ $statusColors[$status->status] ?? 'gray' }}-500 h-3 rounded-full transition-all duration-300" 
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6 pt-4 border-t">
                <a href="{{ route('companies.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Manage Companies <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Stats</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <div>
                        <p class="text-xs text-gray-600">Properties</p>
                        <p class="text-2xl font-bold text-blue-600">{{ \App\Models\Property::count() }}</p>
                    </div>
                    <i class="fas fa-home text-blue-600 text-xl"></i>
                </div>

                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div>
                        <p class="text-xs text-gray-600">Projects</p>
                        <p class="text-2xl font-bold text-green-600">{{ \App\Models\Project::count() }}</p>
                    </div>
                    <i class="fas fa-city text-green-600 text-xl"></i>
                </div>

                <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                    <div>
                        <p class="text-xs text-gray-600">Won Leads</p>
                        <p class="text-2xl font-bold text-purple-600">{{ \App\Models\Lead::where('status', 'won')->count() }}</p>
                    </div>
                    <i class="fas fa-trophy text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Companies -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Recent Companies</h3>
            <a href="{{ route('companies.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Package</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Users</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentCompanies as $company)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $company->name }}</div>
                                <div class="text-sm text-gray-500">{{ $company->email }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-900">{{ $company->package->name ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                {{ $company->status == 'active' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $company->status == 'suspended' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $company->status == 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ ucfirst($company->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $company->users()->count() }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $company->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <a href="{{ route('companies.edit', $company) }}" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No companies found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('companies.create') }}" class="flex items-center justify-center px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg text-blue-700 font-medium transition">
                <i class="fas fa-plus mr-2"></i> Add Company
            </a>
            <a href="{{ route('packages.create') }}" class="flex items-center justify-center px-4 py-3 bg-green-50 hover:bg-green-100 rounded-lg text-green-700 font-medium transition">
                <i class="fas fa-box mr-2"></i> Add Package
            </a>
            <a href="{{ route('companies.index') }}" class="flex items-center justify-center px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg text-purple-700 font-medium transition">
                <i class="fas fa-building mr-2"></i> All Companies
            </a>
            <a href="{{ route('packages.index') }}" class="flex items-center justify-center px-4 py-3 bg-orange-50 hover:bg-orange-100 rounded-lg text-orange-700 font-medium transition">
                <i class="fas fa-layer-group mr-2"></i> All Packages
            </a>
        </div>
    </div>
</div>
@endsection
