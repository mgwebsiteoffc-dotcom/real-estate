@extends('layouts.admin')

@section('title', 'Create Task')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('tasks.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Tasks
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Create New Task</h2>

        <form action="{{ route('tasks.store') }}" method="POST" x-data="{ hasRelation: false }">
            @csrf

            <div class="space-y-4">
                <!-- Task Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Task Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g., Follow up call with John">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="Task details...">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Task Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Task Type *</label>
                        <select name="type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Type</option>
                            <option value="call" {{ old('type') == 'call' ? 'selected' : '' }}>Call</option>
                            <option value="meeting" {{ old('type') == 'meeting' ? 'selected' : '' }}>Meeting</option>
                            <option value="email" {{ old('type') == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="site_visit" {{ old('type') == 'site_visit' ? 'selected' : '' }}>Site Visit</option>
                            <option value="follow_up" {{ old('type') == 'follow_up' ? 'selected' : '' }}>Follow-up</option>
                            <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                        <select name="priority" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>

                    <!-- Assign To -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Assign To *</label>
                        <select name="assigned_to" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Team Member</option>
                            @foreach($teamMembers as $member)
                                <option value="{{ $member->id }}" {{ old('assigned_to', Auth::id()) == $member->id ? 'selected' : '' }}>
                                    {{ $member->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Due Date & Time *</label>
                        <input type="datetime-local" name="due_date" value="{{ old('due_date') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Link to Lead/Property -->
                <div>
                    <label class="flex items-center mb-2">
                        <input type="checkbox" x-model="hasRelation" class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                        <span class="ml-2 text-sm font-medium text-gray-700">Link to Lead or Property</span>
                    </label>
                </div>

                <div x-show="hasRelation" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Related Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Related To</label>
                        <select name="taskable_type" x-model="relatedType"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Type</option>
                            <option value="App\Models\Lead">Lead</option>
                            <option value="App\Models\Property">Property</option>
                        </select>
                    </div>

                    <!-- Lead Selection -->
                    <div x-show="relatedType === 'App\\Models\\Lead'">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Lead</label>
                        <select name="taskable_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Lead</option>
                            @foreach($leads as $lead)
                                <option value="{{ $lead->id }}">{{ $lead->name }} - {{ $lead->phone }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Property Selection -->
                    <div x-show="relatedType === 'App\\Models\\Property'">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Property</label>
                        <select name="taskable_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Property</option>
                            @foreach($properties as $property)
                                <option value="{{ $property->id }}">{{ $property->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('tasks.index') }}" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Create Task
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('relatedType', () => ({
            relatedType: ''
        }))
    })
</script>
@endsection
