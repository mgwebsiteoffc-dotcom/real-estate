@extends('layouts.admin')

@section('title', 'Lead Details')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('leads.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Leads
        </a>
        <div class="flex gap-2">
            <a href="{{ route('leads.edit', $lead) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <form action="{{ route('leads.destroy', $lead) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-trash mr-2"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $lead->name }}</h2>
                        <p class="text-gray-600 mt-1">Lead ID: #{{ $lead->id }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold bg-{{ $lead->status_color }}-100 text-{{ $lead->status_color }}-800">
                        {{ $lead->status_label }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Phone</p>
                        <p class="font-medium text-gray-900">{{ $lead->phone }}</p>
                    </div>
                    @if($lead->email)
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-gray-900">{{ $lead->email }}</p>
                    </div>
                    @endif
                    @if($lead->phone_secondary)
                    <div>
                        <p class="text-sm text-gray-500">Secondary Phone</p>
                        <p class="font-medium text-gray-900">{{ $lead->phone_secondary }}</p>
                    </div>
                    @endif
                    @if($lead->address)
                    <div class="col-span-2">
                        <p class="text-sm text-gray-500">Address</p>
                        <p class="font-medium text-gray-900">{{ $lead->address }}</p>
                        @if($lead->city || $lead->state)
                            <p class="text-sm text-gray-600">{{ $lead->city }}, {{ $lead->state }}</p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Property Requirements -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Property Requirements</h3>
                <div class="grid grid-cols-2 gap-4">
                    @if($lead->budget_min && $lead->budget_max)
                    <div>
                        <p class="text-sm text-gray-500">Budget Range</p>
                        <p class="font-medium text-gray-900">₹{{ number_format($lead->budget_min) }} - ₹{{ number_format($lead->budget_max) }}</p>
                    </div>
                    @endif
                    @if($lead->property_type)
                    <div>
                        <p class="text-sm text-gray-500">Property Type</p>
                        <p class="font-medium text-gray-900">{{ ucfirst($lead->property_type) }}</p>
                    </div>
                    @endif
                    @if($lead->preferred_location)
                    <div class="col-span-2">
                        <p class="text-sm text-gray-500">Preferred Location</p>
                        <p class="font-medium text-gray-900">{{ $lead->preferred_location }}</p>
                    </div>
                    @endif
                    @if($lead->requirements)
                    <div class="col-span-2">
                        <p class="text-sm text-gray-500">Requirements</p>
                        <p class="font-medium text-gray-900">{{ $lead->requirements }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Notes -->
            @if($lead->notes)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Notes</h3>
                <p class="text-gray-700">{{ $lead->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Lead Info -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Lead Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Source</p>
                        <p class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $lead->source)) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Priority</p>
                        <span class="px-2 py-1 text-xs font-medium rounded
                            {{ $lead->priority == 'urgent' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $lead->priority == 'high' ? 'bg-orange-100 text-orange-800' : '' }}
                            {{ $lead->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $lead->priority == 'low' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst($lead->priority) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Assigned To</p>
                        <p class="font-medium text-gray-900">{{ $lead->assignedTo->name ?? 'Unassigned' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Created By</p>
                        <p class="font-medium text-gray-900">{{ $lead->createdBy->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Created At</p>
                        <p class="font-medium text-gray-900">{{ $lead->created_at->format('d M, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
