@extends('layouts.admin')
@section('title', 'Portal Integrations')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">Portal Integrations</h1>
            <p class="text-gray-600">Configure and manage property portal integrations</p>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-100 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-red-100 text-red-700 rounded">
        {{ session('error') }}
    </div>
    @endif

    <!-- Add Portal Configuration -->
    <div class="bg-white p-6 rounded shadow">
        <h3 class="font-semibold text-lg mb-4">Add New Portal</h3>
        
        <form action="{{ route('portals.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Portal Name</label>
                    <select name="portal_name" class="w-full border rounded px-3 py-2" required>
                        <option value="">Select Portal</option>
                        @foreach($availablePortals as $key => $name)
                        <option value="{{ $key }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">API Key</label>
                    <input type="text" name="api_key" class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">API Secret (Optional)</label>
                    <input type="text" name="api_secret" class="w-full border rounded px-3 py-2">
                </div>

                <div class="flex items-center">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-sm">Enable immediately</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-plus"></i> Add Portal
            </button>
        </form>
    </div>

    <!-- Configured Portals -->
    <div class="bg-white rounded shadow">
        <div class="p-6 border-b">
            <h3 class="font-semibold text-lg">Configured Portals</h3>
        </div>
        
        <div class="divide-y">
            @forelse($portals as $portal)
            <div class="p-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-building text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">{{ ucfirst($portal->portal_name) }}</h4>
                        <p class="text-sm text-gray-600">API Key: {{ substr($portal->api_key, 0, 20) }}***</p>
                        <p class="text-xs text-gray-500 mt-1">
                            Synced Properties: {{ $portal->propertySyncs()->where('status', 'synced')->count() }}
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <!-- Toggle Status -->
                    <form action="{{ route('portals.toggle', $portal) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="px-4 py-2 rounded text-sm font-medium {{ $portal->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            <i class="fas fa-{{ $portal->is_active ? 'check-circle' : 'times-circle' }}"></i>
                            {{ $portal->is_active ? 'Active' : 'Inactive' }}
                        </button>
                    </form>

                    <!-- Delete -->
                    <form action="{{ route('portals.destroy', $portal) }}" method="POST" 
                          onsubmit="return confirm('Are you sure? This will remove all synced properties.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-plug text-4xl mb-3 text-gray-300"></i>
                <p>No portals configured yet</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Portal Setup Instructions -->
    <div class="bg-blue-50 p-6 rounded">
        <h4 class="font-semibold text-blue-900 mb-3">Setup Instructions</h4>
        <div class="space-y-3 text-sm text-blue-800">
            <div>
                <strong>99acres:</strong> Get API credentials from <a href="https://www.99acres.com/developers" target="_blank" class="underline">99acres Developer Portal</a>
            </div>
            <div>
                <strong>MagicBricks:</strong> Contact MagicBricks support for API access
            </div>
            <div>
                <strong>Housing.com:</strong> Register at <a href="https://housing.com/api" target="_blank" class="underline">Housing API</a>
            </div>
            <div>
                <strong>NoBroker:</strong> Get credentials from NoBroker Partner Portal
            </div>
        </div>
    </div>
</div>
@endsection
