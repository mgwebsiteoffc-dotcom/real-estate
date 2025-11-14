@extends('layouts.admin')

@section('title', 'Task Details')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('tasks.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Tasks
        </a>
        <div class="flex gap-2">
            @if($task->status !== 'completed')
            <form action="{{ route('tasks.complete', $task) }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i> Mark Complete
                </button>
            </form>
            @endif
            <a href="{{ route('tasks.edit', $task) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-trash mr-2"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Task Details -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $task->title }}</h1>
                        <p class="text-sm text-gray-500 mt-1">Task #{{ $task->id }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold bg-{{ $task->status_color }}-100 text-{{ $task->status_color }}-800">
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </span>
                </div>

                @if($task->description)
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Description</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $task->description }}</p>
                </div>
                @endif

                <!-- Task Info Grid -->
                <div class="grid grid-cols-2 gap-4 py-4 border-t">
                    <div>
                        <p class="text-sm text-gray-600">Task Type</p>
                        <p class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $task->type) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Priority</p>
                        <span class="inline-block px-3 py-1 text-sm font-medium rounded-full bg-{{ $task->priority_color }}-100 text-{{ $task->priority_color }}-800">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Due Date</p>
                        <p class="font-medium text-gray-900">{{ $task->due_date->format('M d, Y h:i A') }}</p>
                        @if($task->is_overdue)
                            <p class="text-sm text-red-600 font-medium">Overdue by {{ $task->due_date->diffForHumans() }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Created</p>
                        <p class="font-medium text-gray-900">{{ $task->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @if($task->completed_at)
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600">Completed At</p>
                        <p class="font-medium text-green-600">{{ $task->completed_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @endif
                </div>

                <!-- Related Item -->
                @if($task->taskable)
                <div class="mt-6 pt-6 border-t">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Related To</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">{{ class_basename($task->taskable_type) }}</p>
                                <p class="font-medium text-gray-900">
                                    {{ $task->taskable->name ?? $task->taskable->title ?? 'N/A' }}
                                </p>
                                @if($task->taskable_type === 'App\Models\Lead' && $task->taskable)
                                    <p class="text-sm text-gray-600">{{ $task->taskable->phone }}</p>
                                @endif
                            </div>
                            @if($task->taskable)
                                <a href="#" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Completion Notes -->
                @if($task->completion_notes)
                <div class="mt-6 pt-6 border-t">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Completion Notes</h3>
                    <div class="bg-green-50 rounded-lg p-4">
                        <p class="text-gray-700">{{ $task->completion_notes }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Assignment Info -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Assignment</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-2">Assigned To</p>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-semibold">{{ substr($task->assignedTo->name, 0, 1) }}</span>
                            </div>
                            <div class="ml-3">
                                <p class="font-medium text-gray-900">{{ $task->assignedTo->name }}</p>
                                <p class="text-sm text-gray-500">{{ $task->assignedTo->role_label }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t">
                        <p class="text-sm text-gray-600 mb-2">Created By</p>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <span class="text-green-600 font-semibold">{{ substr($task->createdBy->name, 0, 1) }}</span>
                            </div>
                            <div class="ml-3">
                                <p class="font-medium text-gray-900">{{ $task->createdBy->name }}</p>
                                <p class="text-sm text-gray-500">{{ $task->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    @if($task->taskable_type === 'App\Models\Lead' && $task->taskable)
                    <a href="tel:{{ $task->taskable->phone }}" class="block w-full px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-center font-medium transition">
                        <i class="fas fa-phone mr-2"></i> Call Lead
                    </a>
                    @endif
                    @if($task->taskable_type === 'App\Models\Property' && $task->taskable)
                    <a href="{{ route('properties.show', $task->taskable) }}" class="block w-full px-4 py-2 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg text-center font-medium transition">
                        <i class="fas fa-building mr-2"></i> View Property
                    </a>
                    @endif
                    <a href="{{ route('tasks.create') }}" class="block w-full px-4 py-2 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg text-center font-medium transition">
                        <i class="fas fa-plus mr-2"></i> Create New Task
                    </a>
                </div>
            </div>

            <!-- Timeline Placeholder -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Activity Timeline</h3>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-plus text-blue-600 text-xs"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-900">Task created</p>
                            <p class="text-xs text-gray-500">{{ $task->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    @if($task->completed_at)
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check text-green-600 text-xs"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-900">Task completed</p>
                            <p class="text-xs text-gray-500">{{ $task->completed_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
