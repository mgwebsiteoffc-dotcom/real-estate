@extends('layouts.admin')
@section('title', 'Portal Sync Status')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">Portal Sync Status</h1>
            <p class="text-gray-600">{{ $property->title }}</p>
        </div>
        <a href="{{ route('properties.show', $property) }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            <i class="fas fa-arrow-left"></i> Back to Property
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-100 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif

    <!-- Sync to New Portal -->
    @if($activePortals->count() > 0)
    <div class="bg-white p-6 rounded shadow">
        <h3 class="font-semibold text-lg mb-4">Sync to Portal</h3>
        
        <form action="{{ route('properties.sync', $property) }}" method="POST" class="flex gap-3">
            @csrf
            <select name="portal_id" class="flex-1 border rounded px-3 py-2" required>
                <option value="">Select Portal</option>
                @foreach($activePortals as $portal)
                <option value="{{ $portal->id }}">{{ ucfirst($portal->portal_name) }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                <i class="fas fa-sync"></i> Sync Now
            </button>
        </form>
    </div>
    @endif

    <!-- Sync Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($syncs as $sync)
        <div class="bg-white p-6 rounded shadow">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h4 class="font-semibold text-lg">{{ ucfirst($sync->portalConfig->portal_name) }}</h4>
                    <p class="text-sm text-gray-600">
                        Status: 
                        <span class="px-2 py-1 rounded text-xs font-medium
                            @if($sync->status == 'synced') bg-green-100 text-green-800
                            @elseif($sync->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($sync->status == 'failed') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($sync->status) }}
                        </span>
                    </p>
                </div>
                
                @if($sync->status == 'synced')
                <form action="{{ route('portal-syncs.remove', $sync) }}" method="POST" 
                      onsubmit="return confirm('Remove property from this portal?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-times-circle"></i> Remove
                    </button>
                </form>
                @endif
            </div>

            <div class="space-y-2 text-sm">
                @if($sync->portal_property_id)
                <p><strong>Portal ID:</strong> {{ $sync->portal_property_id }}</p>
                @endif
                
                @if($sync->last_synced_at)
                <p><strong>Last Synced:</strong> {{ $sync->last_synced_at->format('M d, Y h:i A') }}</p>
                @endif

                @if($sync->error_message)
                <div class="p-3 bg-red-50 text-red-700 rounded text-xs mt-2">
                    <strong>Error:</strong> {{ $sync->error_message }}
                </div>
                @endif
            </div>

            <!-- Sync Logs -->
            @if($sync->logs->count() > 0)
            <div class="mt-4 pt-4 border-t">
                <h5 class="font-semibold text-sm mb-2">Recent Activity</h5>
                <div class="space-y-2">
                    @foreach($sync->logs->take(3) as $log)
                    <div class="text-xs text-gray-600">
                        <span class="font-medium">{{ ucfirst($log->action) }}</span> - 
                        <span class="{{ $log->status == 'success' ? 'text-green-600' : 'text-red-600' }}">
                            {{ ucfirst($log->status) }}
                        </span>
                        <span class="text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @empty
        <div class="col-span-2 p-8 bg-white rounded shadow text-center text-gray-500">
            <i class="fas fa-sync-alt text-4xl mb-3 text-gray-300"></i>
            <p>This property hasn't been synced to any portal yet</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
