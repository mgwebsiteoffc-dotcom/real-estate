@extends('layouts.admin')
@section('title', 'Microsite Analytics')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">{{ $microsite->title }}</h1>
            <p class="text-gray-600">Analytics & Performance</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('microsite.show', $microsite->slug) }}" target="_blank"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-external-link-alt"></i> View Live
            </a>
            <a href="{{ route('microsites.edit', $microsite) }}"
               class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('microsites.index') }}"
               class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Total Views</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total_views'] }}</p>
                </div>
                <i class="fas fa-eye text-5xl text-blue-200"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Leads Captured</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['total_leads'] }}</p>
                </div>
                <i class="fas fa-user-plus text-5xl text-green-200"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Conversion Rate</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['conversion_rate'] }}%</p>
                </div>
                <i class="fas fa-chart-line text-5xl text-purple-200"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Status</p>
                    <p class="text-lg font-semibold {{ $microsite->is_published ? 'text-green-600' : 'text-gray-600' }}">
                        {{ $microsite->is_published ? 'Published' : 'Draft' }}
                    </p>
                </div>
                <i class="fas fa-{{ $microsite->is_published ? 'check-circle' : 'clock' }} text-5xl {{ $microsite->is_published ? 'text-green-200' : 'text-gray-200' }}"></i>
            </div>
        </div>
    </div>

    <!-- Captured Leads Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold">Captured Leads</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Message</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($microsite->capturedLeads as $capturedLead)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $capturedLead->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $capturedLead->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $capturedLead->phone }}</td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-600 max-w-xs truncate">{{ $capturedLead->message ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $capturedLead->created_at->format('M d, Y h:i A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($capturedLead->lead_id)
                            <a href="{{ route('leads.show', $capturedLead->lead_id) }}" 
                               class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i> View Lead
                            </a>
                            @else
                            <span class="text-gray-400">No CRM lead</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                            <p>No leads captured yet</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Microsite Info -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4">Microsite Information</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">URL</p>
                <p class="font-medium">{{ $microsite->url }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Template</p>
                <p class="font-medium">{{ ucfirst($microsite->template) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Created</p>
                <p class="font-medium">{{ $microsite->created_at->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Published</p>
                <p class="font-medium">{{ $microsite->published_at ? $microsite->published_at->format('M d, Y') : 'Not published' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
