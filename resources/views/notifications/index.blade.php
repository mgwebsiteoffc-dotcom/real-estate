@extends('layouts.admin')
@section('title', 'Notifications')

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Notifications</h1>
        <form action="{{ route('notifications.markAllRead') }}" method="POST">
            @csrf
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Mark All as Read
            </button>
        </form>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-100 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded shadow divide-y">
        @forelse($notifications as $notification)
        @php
            $data = $notification->data ?? [];
            $type = $data['type'] ?? 'general';
            $title = $data['title'] ?? 'Notification';
            $message = $data['message'] ?? 'You have a new notification';
            $url = $data['url'] ?? null;
            
            // Icon mapping
            $iconMap = [
                'follow_up_reminder' => 'calendar-check',
                'task_assigned' => 'tasks',
                'lead_assigned' => 'user-plus',
                'general' => 'bell'
            ];
            $icon = $iconMap[$type] ?? 'bell';
        @endphp
        
        <div class="p-4 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }} hover:bg-gray-50 transition">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-{{ $icon }} text-blue-600"></i>
                        <h3 class="font-semibold text-gray-800">{{ $title }}</h3>
                        @if(!$notification->read_at)
                        <span class="px-2 py-1 bg-blue-600 text-white text-xs rounded">New</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-700 mb-2">{{ $message }}</p>
                    <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
                <div class="flex gap-2">
                    @if(!$notification->read_at)
                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-blue-600 hover:underline text-sm" title="Mark as read">
                            <i class="fas fa-check"></i>
                        </button>
                    </form>
                    @endif
                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this notification?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline text-sm" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @if($url)
            <a href="{{ $url }}" class="inline-block mt-2 text-sm text-blue-600 hover:underline">
                View Details →
            </a>
            @endif
        </div>
        @empty
        <div class="p-8 text-center text-gray-500">
            <i class="fas fa-bell text-4xl mb-3 text-gray-300"></i>
            <p>No notifications yet</p>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
