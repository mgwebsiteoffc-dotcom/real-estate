@extends('layouts.admin')
@section('title', 'Leads Management')

@section('content')
<div class="space-y-4">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">Leads Management</h1>
            <p class="text-gray-600">Manage and track all your leads</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('leads.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 flex items-center gap-2">
                <i class="fas fa-plus"></i> Add Lead
            </a>

            <a href="{{ route('leads.import') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
    <i class="fas fa-file-import"></i> Import Leads
</a>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('leads.index') }}" class="bg-white p-4 rounded shadow">
        <div class="flex gap-3 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search leads..." class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" class="border rounded px-3 py-2">
                    <option value="">All Status</option>
                    <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                    <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                    <option value="qualified" {{ request('status') == 'qualified' ? 'selected' : '' }}>Qualified</option>
                    <option value="proposal" {{ request('status') == 'proposal' ? 'selected' : '' }}>Proposal Sent</option>
                    <option value="won" {{ request('status') == 'won' ? 'selected' : '' }}>Won</option>
                    <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Team Member</label>
                <select name="assigned_to" class="border rounded px-3 py-2">
                    <option value="">All Team Members</option>
                    @foreach($teamMembers as $member)
                    <option value="{{ $member->id }}" {{ request('assigned_to') == $member->id ? 'selected' : '' }}>
                        {{ $member->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-filter"></i> Filter
            </button>
            @if(request()->anyFilled(['search', 'status', 'assigned_to']))
            <a href="{{ route('leads.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">
                <i class="fas fa-times"></i> Clear
            </a>
            @endif
        </div>
    </form>

    <!-- Leads Table -->
    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold">LEAD</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">CONTACT</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">STATUS</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">PRIORITY</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">ASSIGNED TO</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">BUDGET</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">ACTIONS</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($leads as $lead)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="font-medium">{{ $lead->name }}</div>
                        <div class="text-sm text-gray-500">{{ $lead->source ?? 'referral' }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-sm">{{ $lead->phone }}</div>
                        <div class="text-sm text-gray-500">{{ $lead->email }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded
                            @if($lead->status == 'new') bg-blue-100 text-blue-800
                            @elseif($lead->status == 'contacted') bg-yellow-100 text-yellow-800
                            @elseif($lead->status == 'qualified') bg-purple-100 text-purple-800
                            @elseif($lead->status == 'proposal') bg-indigo-100 text-indigo-800
                            @elseif($lead->status == 'won') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($lead->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded
                            @if(($lead->priority ?? 'medium') == 'high') bg-red-100 text-red-800
                            @elseif(($lead->priority ?? 'medium') == 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ ucfirst($lead->priority ?? 'medium') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        {{ $lead->assignedTo->name ?? 'Unassigned' }}
                    </td>
                    <td class="px-4 py-3 text-sm">
                        {{ $lead->budget ?? 'N/A' }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-1">
                            <!-- View Details -->
                            <a href="{{ route('leads.show', $lead) }}" title="View Details"
                               class="w-8 h-8 flex items-center justify-center bg-blue-500 text-white rounded hover:bg-blue-600">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            
                            <!-- WhatsApp -->
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->phone) }}" target="_blank" title="WhatsApp"
                               class="w-8 h-8 flex items-center justify-center bg-green-500 text-white rounded hover:bg-green-600">
                                <i class="fab fa-whatsapp text-sm"></i>
                            </a>
                            
                            <!-- Call -->
                            <a href="tel:{{ $lead->phone }}" title="Call"
                               class="w-8 h-8 flex items-center justify-center bg-blue-400 text-white rounded hover:bg-blue-500">
                                <i class="fas fa-phone text-sm"></i>
                            </a>
                            
                            <!-- Email -->
                            <a href="mailto:{{ $lead->email }}" title="Email"
                               class="w-8 h-8 flex items-center justify-center bg-purple-500 text-white rounded hover:bg-purple-600">
                                <i class="fas fa-envelope text-sm"></i>
                            </a>
                            
                            <!-- Activity Log -->
                            <a href="{{ route('leads.show', $lead) }}#activities" title="Activity Log"
                               class="w-8 h-8 flex items-center justify-center bg-orange-500 text-white rounded hover:bg-orange-600">
                                <i class="fas fa-clipboard-list text-sm"></i>
                            </a>
                            
                            <!-- Edit -->
                            <a href="{{ route('leads.edit', $lead) }}" title="Edit"
                               class="w-8 h-8 flex items-center justify-center bg-teal-500 text-white rounded hover:bg-teal-600">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            
                            <!-- Delete -->
                            <form action="{{ route('leads.destroy', $lead) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this lead?');">
                                @csrf @method('DELETE')
                                <button type="submit" title="Delete"
                                        class="w-8 h-8 flex items-center justify-center bg-red-500 text-white rounded hover:bg-red-600">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                        No leads found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $leads->appends(request()->query())->links() }}
    </div>
</div>
@endsection
