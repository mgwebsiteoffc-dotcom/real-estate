@extends('layouts.admin')

@section('title', 'Profile Settings')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <h2 class="text-2xl font-bold text-gray-800">Your Profile</h2>

    <form action="{{ route('settings.profile.update') }}" method="POST" class="bg-white rounded-lg shadow-sm p-6 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="pt-4 border-t">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Change Password</h3>
            <p class="text-xs text-gray-500 mb-2">Leave blank if you do not wish to change password.</p>
            <input type="password" name="password" autocomplete="new-password"
                placeholder="New Password"
                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <input type="password" name="password_confirmation" autocomplete="new-password"
                placeholder="Confirm Password"
                class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
