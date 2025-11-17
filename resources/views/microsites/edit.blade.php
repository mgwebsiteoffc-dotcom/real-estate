@extends('layouts.admin')
@section('title', 'Edit Microsite')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">{{ $microsite->title }}</h1>
            <p class="text-gray-600">{{ $microsite->property->title }}</p>
        </div>
        <div class="flex gap-2">
            @if($microsite->is_published)
            <a href="{{ route('microsite.show', $microsite->slug) }}" target="_blank"
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                <i class="fas fa-external-link-alt"></i> View Live
            </a>
            <form action="{{ route('microsites.unpublish', $microsite) }}" method="POST">
                @csrf
                <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                    <i class="fas fa-eye-slash"></i> Unpublish
                </button>
            </form>
            @else
            <form action="{{ route('microsites.publish', $microsite) }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    <i class="fas fa-check-circle"></i> Publish
                </button>
            </form>
            @endif
            
            <a href="{{ route('microsites.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-100 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Views</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $microsite->views }}</p>
                </div>
                <i class="fas fa-eye text-4xl text-blue-200"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Leads Captured</p>
                    <p class="text-3xl font-bold text-green-600">{{ $microsite->leads_captured }}</p>
                </div>
                <i class="fas fa-user-plus text-4xl text-green-200"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Conversion Rate</p>
                    <p class="text-3xl font-bold text-purple-600">
                        {{ $microsite->views > 0 ? round(($microsite->leads_captured / $microsite->views) * 100, 1) : 0 }}%
                    </p>
                </div>
                <i class="fas fa-chart-line text-4xl text-purple-200"></i>
            </div>
        </div>
    </div>

    <form action="{{ route('microsites.update', $microsite) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Info -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-semibold text-lg mb-4">Basic Information</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Title</label>
                    <input type="text" name="title" value="{{ old('title', $microsite->title) }}"
                           class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <textarea name="description" rows="4" class="w-full border rounded px-3 py-2">{{ old('description', $microsite->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">URL Slug</label>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-600">{{ url('site') }}/</span>
                        <input type="text" value="{{ $microsite->slug }}" class="flex-1 border rounded px-3 py-2 bg-gray-100" readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-semibold text-lg mb-4">SEO Settings</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $microsite->meta_title) }}"
                           maxlength="60" class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Meta Description</label>
                    <textarea name="meta_description" rows="3" maxlength="160"
                              class="w-full border rounded px-3 py-2">{{ old('meta_description', $microsite->meta_description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Keywords</label>
                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $microsite->meta_keywords) }}"
                           class="w-full border rounded px-3 py-2">
                </div>
            </div>
        </div>

        <!-- Template -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-semibold text-lg mb-4">Template</h3>
            
            <div class="grid grid-cols-4 gap-4">
                @foreach($templates as $key => $name)
                <label class="cursor-pointer">
                    <input type="radio" name="template" value="{{ $key }}" 
                           {{ $microsite->template === $key ? 'checked' : '' }} class="hidden peer">
                    <div class="border-2 border-gray-300 peer-checked:border-blue-600 peer-checked:bg-blue-50 rounded-lg p-4 text-center">
                        <p class="font-medium text-sm">{{ $name }}</p>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded hover:bg-blue-700 font-semibold">
            <i class="fas fa-save"></i> Save Changes
        </button>
    </form>
</div>
@endsection
