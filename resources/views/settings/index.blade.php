@extends('layouts.admin')

@section('title', 'Company Settings')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <h2 class="text-2xl font-bold text-gray-800">Company Settings</h2>
    
    <form action="{{ route('settings.company.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm p-6 space-y-4">
        @csrf

        <!-- Company Logo -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
            @if($company->logo)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $company->logo) }}" alt="Company Logo" class="h-14 rounded">
                </div>
            @endif
            <input type="file" name="logo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <p class="text-xs text-gray-500 mt-1">Upload new logo (Max 2MB)</p>
        </div>

        <!-- Name -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Company Name *</label>
            <input type="text" name="name" value="{{ old('name', $company->name) }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Contact Email -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
            <input type="email" name="contact_email" value="{{ old('contact_email', $company->contact_email) }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Contact Phone -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
            <input type="text" name="contact_phone" value="{{ old('contact_phone', $company->contact_phone) }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Address -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
            <textarea name="address" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('address', $company->address) }}</textarea>
        </div>

        <!-- Branding Color -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Branding Color</label>
            <input type="color" name="branding_color" value="{{ old('branding_color', $company->branding_color ?? '#2563eb') }}"
                class="w-16 h-10 border-2 border-gray-300 rounded">
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
