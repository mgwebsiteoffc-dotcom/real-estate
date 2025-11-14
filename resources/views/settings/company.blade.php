@extends('layouts.admin')

@section('title', 'Company Settings')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Company Settings</h2>
    <form action="{{ route('settings.company.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">Company Name *</label>
            <input type="text" name="name" value="{{ old('name', $company->name) }}" class="w-full border rounded px-3 py-2 mt-1" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Logo</label>
            @if($company->logo)
                <img src="{{ asset('storage/' . $company->logo) }}" alt="logo" class="h-12 mb-2">
            @endif
            <input type="file" name="logo" class="w-full border rounded px-3 py-2 mt-1">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Contact Email</label>
            <input type="email" name="contact_email" value="{{ old('contact_email', $company->contact_email) }}" class="w-full border rounded px-3 py-2 mt-1">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Contact Phone</label>
            <input type="text" name="contact_phone" value="{{ old('contact_phone', $company->contact_phone) }}" class="w-full border rounded px-3 py-2 mt-1">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Address</label>
            <textarea name="address" rows="2" class="w-full border rounded px-3 py-2 mt-1">{{ old('address', $company->address) }}</textarea>
        </div>

        <div class="flex gap-2 justify-end">
            <button type="submit" class="bg-blue-600 text-white font-semibold px-6 py-2 rounded hover:bg-blue-700">Save</button>
        </div>
    </form>
</div>
@endsection
 