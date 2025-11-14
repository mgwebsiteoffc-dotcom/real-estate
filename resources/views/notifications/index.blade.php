@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Notifications</h2>
        <form action="{{ route('notifications.read-all') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Mark All as Read
            </button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm divide-y">
        @forelse($notifications as $notification)
        <div class="p-4 hover:bg-gray-50 {{ $notification->read_at ? 'opacity-60' : 'bg-blue-50' }}">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-1">
                        @if(!$notification->read_at)
                            <span class="w-2 h-2 bg-blue-600 rounded-full mr-2"></span>
                        @endif
                        <p class="font-medium text-gray-900">{{ $notification->data['message'] ?? 'New notification' }}</p>
                    </div>
                    <p class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
                <div class="flex items-center space-x-2 ml-4">
                    @if(isset($notification->data['url']))
                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm">
                            View
                        </button>
                    </form>
                    @endif
                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <i class="fas fa-bell-slash text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No notifications yet</p>
        </div>
        @endforelse
    </div>

    {{ $notifications->links() }}
</div>
@endsection
