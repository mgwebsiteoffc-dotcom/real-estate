@extends('layouts.admin')
@section('title', 'Automations')

@section('content')
<h2 class="text-2xl font-bold mb-4">Automations</h2>

<a href="{{ route('automations.create') }}" class="mb-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
    + Create Automation
</a>

<table class="min-w-full bg-white rounded shadow overflow-hidden">
    <thead class="bg-gray-100 border-b">
        <tr>
            <th class="p-3">Name</th>
            <th class="p-3">Module</th>
            <th class="p-3">Trigger Event</th>
            <th class="p-3">Action Type</th>
            <th class="p-3">Active</th>
            <th class="p-3">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($automations as $automation)
        <tr class="border-b hover:bg-gray-50">
            <td class="p-3">{{ $automation->name }}</td>
            <td class="p-3 capitalize">{{ $automation->module }}</td>
            <td class="p-3">{{ $automation->trigger_event }}</td>
            <td class="p-3">{{ $automation->action_type }}</td>
            <td class="p-3">{{ $automation->active ? 'Yes' : 'No' }}</td>
            <td class="p-3 space-x-2">
                <a href="{{ route('automations.edit', $automation) }}" class="text-blue-600 hover:underline">Edit</a>
                <form method="POST" action="{{ route('automations.destroy', $automation) }}" class="inline"
                    onsubmit="return confirm('Are you sure?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center p-4 text-gray-500">No automations created yet.</td></tr>
        @endforelse
    </tbody>
</table>

{{ $automations->links() }}
@endsection
