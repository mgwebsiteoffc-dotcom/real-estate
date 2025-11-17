@extends('layouts.admin')
@section('title', 'Task Details')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">{{ $task->title }}</h1>
            <p class="text-gray-600">Task #{{ $task->id }}</p>
        </div>
        <a href="{{ route('tasks.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-100 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-12 gap-6">
        <!-- Left: Task Details -->
        <div class="col-span-12 lg:col-span-8 space-y-6">
            <!-- Task Information -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold text-lg mb-4 border-b pb-2">Task Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Title</span>
                        <p class="font-medium">{{ $task->title }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Status</span>
                        <span class="px-3 py-1 rounded text-sm font-medium
                            @if($task->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($task->status == 'in_progress') bg-blue-100 text-blue-800
                            @elseif($task->status == 'completed') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Priority</span>
                        <span class="px-3 py-1 rounded text-sm font-medium
                            @if($task->priority == 'high') bg-red-100 text-red-800
                            @elseif($task->priority == 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Due Date</span>
                        <p class="font-medium">{{ $task->due_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Assigned To</span>
                        <p class="font-medium">{{ $task->assignedTo->name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Created By</span>
                        <p class="font-medium">{{ $task->createdBy->name }}</p>
                    </div>
                    @if($task->lead)
                    <div class="col-span-2">
                        <span class="text-gray-600 text-sm block mb-1">Related Lead</span>
                        <a href="{{ route('leads.show', $task->lead) }}" class="text-blue-600 hover:underline">
                            {{ $task->lead->name }}
                        </a>
                    </div>
                    @endif
                    @if($task->description)
                    <div class="col-span-2">
                        <span class="text-gray-600 text-sm block mb-1">Description</span>
                        <p class="text-gray-700">{{ $task->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Change Status -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold text-lg mb-4 border-b pb-2">Update Status</h3>
                <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" class="flex gap-3">
                    @csrf @method('PATCH')
                    <select name="status" class="flex-1 border rounded px-3 py-2" required>
                        <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $task->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: Activity Timeline -->
        <div class="col-span-12 lg:col-span-4">
            <div class="bg-white p-6 rounded shadow sticky top-4">
                <h3 class="font-semibold text-lg mb-4 border-b pb-2">Activity Timeline</h3>
                
                <!-- Add Comment Form -->
                <form action="{{ route('tasks.addComment', $task) }}" method="POST" class="mb-6 p-4 bg-gray-50 rounded border">
                    @csrf
                    <textarea name="comment" placeholder="Add a comment..." 
                              class="w-full border rounded px-3 py-2 mb-2" rows="3" required></textarea>
                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        <i class="fas fa-comment"></i> Add Comment
                    </button>
                </form>

                <!-- Activities List -->
                <div class="space-y-3 max-h-[500px] overflow-y-auto">
                    @forelse($task->activities as $activity)
                    <div class="border-l-4 
                        @if($activity->action == 'created') border-blue-500
                        @elseif($activity->action == 'status_changed') border-purple-500
                        @elseif($activity->action == 'commented') border-green-500
                        @else border-gray-500
                        @endif
                        pl-3 py-2 bg-gray-50 rounded">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-{{ $activity->action == 'created' ? 'plus-circle' : ($activity->action == 'status_changed' ? 'sync-alt' : 'comment') }} 
                               text-{{ $activity->action == 'created' ? 'blue' : ($activity->action == 'status_changed' ? 'purple' : 'green') }}-600 mt-1"></i>
                            <div class="flex-1">
                                <p class="text-xs text-gray-600 mb-1">
                                    <strong>{{ $activity->user->name }}</strong>
                                    @if($activity->action == 'created')
                                        created this task
                                    @elseif($activity->action == 'status_changed')
                                        changed status from 
                                        <span class="font-semibold">{{ $activity->old_value }}</span> to 
                                        <span class="font-semibold">{{ $activity->new_value }}</span>
                                    @elseif($activity->action == 'commented')
                                        commented
                                    @endif
                                </p>
                                @if($activity->description && $activity->action == 'commented')
                                <p class="text-sm text-gray-700 mt-1">{{ $activity->description }}</p>
                                @endif
                                <p class="text-xs text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4 text-sm">No activities yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
