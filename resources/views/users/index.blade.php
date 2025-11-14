@extends('layouts.admin')

@section('title', 'Team Members')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Team Members</h2>
            <p class="text-gray-600 mt-1">Manage your team and their access</p>
        </div>
        <a href="{{ route('users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-user-plus mr-2"></i> Add Team Member
        </a>
    </div>

    <!-- Users Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($users as $user)
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 font-semibold text-lg">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    <div class="ml-3">
                        <h3 class="font-semibold text-gray-900">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $user->role_label }}</p>
                    </div>
                </div>
                <span class="px-2 py-1 text-xs font-medium rounded
                    {{ $user->status == 'active' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $user->status == 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}
                    {{ $user->status == 'suspended' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ ucfirst($user->status) }}
                </span>
            </div>

            <div class="space-y-2 text-sm text-gray-600 mb-4">
                <div class="flex items-center">
                    <i class="fas fa-envelope w-5 text-gray-400"></i>
                    <span class="ml-2">{{ $user->email }}</span>
                </div>
                @if($user->phone)
                <div class="flex items-center">
                    <i class="fas fa-phone w-5 text-gray-400"></i>
                    <span class="ml-2">{{ $user->phone }}</span>
                </div>
                @endif
                <div class="flex items-center">
                    <i class="fas fa-calendar w-5 text-gray-400"></i>
                    <span class="ml-2">Joined {{ $user->created_at->format('M d, Y') }}</span>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 gap-3 mb-4 pt-4 border-t">
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900">{{ $user->assignedLeads()->count() }}</p>
                    <p class="text-xs text-gray-500">Total Leads</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-green-600">{{ $user->assignedLeads()->where('status', 'won')->count() }}</p>
                    <p class="text-xs text-gray-500">Leads Won</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-2 pt-4 border-t">
                <a href="{{ route('users.show', $user) }}" class="flex-1 text-center px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium text-gray-700">
                    View
                </a>
                <a href="{{ route('users.edit', $user) }}" class="flex-1 text-center px-3 py-2 bg-blue-100 hover:bg-blue-200 rounded-lg text-sm font-medium text-blue-700">
                    Edit
                </a>
                @if(Auth::id() !== $user->id)
                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-2 bg-red-100 hover:bg-red-200 rounded-lg text-sm font-medium text-red-700">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12">
            <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No team members found. Add your first member!</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection
