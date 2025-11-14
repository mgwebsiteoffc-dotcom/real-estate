@extends('layouts.admin')
@section('title', 'Integrations')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <h2 class="text-2xl font-bold mb-4">Integrations</h2>

    <form action="{{ route('integrations.update') }}" method="POST" class="bg-white p-6 rounded shadow space-y-4">
        @csrf

        <h3 class="text-xl font-semibold">WhatsApp Integration (Whatify)</h3>
        
        <div>
            <label class="flex items-center">
                <input type="checkbox" name="whatsapp_enabled" value="1" {{ old('whatsapp_enabled', $company->whatsapp_enabled) ? 'checked' : '' }} class="form-checkbox" />
                <span class="ml-2">Enable WhatsApp</span>
            </label>
        </div>

        <div>
            <label class="block mb-1 font-semibold">WhatsApp API Token (Whatify Bearer Token)</label>
            <input type="text" name="whatsapp_api_token" value="{{ old('whatsapp_api_token', $company->whatsapp_api_token) }}" 
                class="w-full px-3 py-2 border rounded" placeholder="Enter your Whatify Bearer Token" />
            <p class="text-xs text-gray-500 mt-1">Get your token from <a href="https://whatify.in" target="_blank" class="text-blue-600 underline">Whatify.in</a></p>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
