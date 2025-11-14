@extends('layouts.admin')
@section('title', isset($automation) ? 'Edit Automation' : 'Create Automation')

@section('content')
<h2 class="text-2xl font-bold mb-4">{{ isset($automation) ? 'Edit Automation' : 'Create Automation' }}</h2>

<form action="{{ isset($automation) ? route('automations.update', $automation) : route('automations.store') }}" method="POST" class="max-w-lg space-y-4">
    @csrf
    @if(isset($automation))
        @method('PUT')
    @endif

    <div>
        <label class="block mb-1 font-semibold">Name</label>
        <input type="text" name="name" value="{{ old('name', $automation->name ?? '') }}" required 
            class="w-full px-3 py-2 border rounded" />
    </div>

    <div>
        <label class="block mb-1 font-semibold">Module</label>
        <select name="module" required class="w-full px-3 py-2 border rounded">
            @foreach(['lead', 'task', 'property'] as $module)
                <option value="{{ $module }}" {{ old('module', $automation->module ?? '') == $module ? 'selected' : '' }}>{{ ucfirst($module) }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block mb-1 font-semibold">Trigger Event</label>
      <select name="trigger_event" required class="w-full px-3 py-2 border rounded">
    <option value="">-- Choose Trigger Event --</option>
    <option value="lead_created">Lead Created</option>
    <option value="lead_updated">Lead Updated</option>
    <option value="task_due">Task Due</option>
    <option value="property_added">Property Added</option>
    <option value="custom_date">By Scheduled Time</option>
</select>
    </div>

    <div>
        <label class="block mb-1 font-semibold">Action Type</label>
        <input type="text" name="action_type" value="{{ old('action_type', $automation->action_type ?? '') }}" required 
            placeholder="e.g. send_email, create_task" class="w-full px-3 py-2 border rounded" />
    </div>

    <div>
        <label class="block mb-1 font-semibold">Action Details (JSON)</label>
        <textarea name="action_details" rows="5" required class="w-full border rounded p-2 font-mono">{{ old('action_details', isset($automation) ? json_encode($automation->action_details, JSON_PRETTY_PRINT) : '') }}</textarea>
        <p class="text-xs text-gray-500 mt-1">Enter valid JSON for action details.</p>
    </div>

    <div>
        <label class="inline-flex items-center">
            <input type="checkbox" name="active" value="1" {{ old('active', $automation->active ?? true) ? 'checked' : '' }} class="form-checkbox" />
            <span class="ml-2">Active</span>
        </label>
    </div>

    <div>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
            {{ isset($automation) ? 'Update' : 'Create' }}
        </button>
        <a href="{{ route('automations.index') }}" class="ml-3 text-gray-600 hover:underline">Cancel</a>
    </div>
</form>
@endsection
