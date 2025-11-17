@extends('layouts.admin')
@section('title', 'Integrations')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <h2 class="text-2xl font-bold mb-4">Integrations</h2>

    @if(session('success'))
    <div class="p-4 bg-green-100 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-red-100 text-red-700 rounded">
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('integrations.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- WhatsApp Integration -->
        <div class="bg-white p-6 rounded shadow">
            <div class="flex items-center gap-3 mb-4">
                <i class="fab fa-whatsapp text-green-500 text-3xl"></i>
                <h3 class="text-xl font-semibold">WhatsApp Integration (Whatify)</h3>
            </div>
            
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="whatsapp_enabled" value="1" 
                           {{ old('whatsapp_enabled', $company->whatsapp_enabled) ? 'checked' : '' }} 
                           class="form-checkbox h-5 w-5 text-blue-600">
                    <span class="ml-2 text-gray-700">Enable WhatsApp Integration</span>
                </label>
            </div>

            <div>
                <label class="block mb-2 font-semibold">API Token</label>
                <input type="text" name="whatsapp_api_token" 
                       value="{{ old('whatsapp_api_token', $company->whatsapp_api_token) }}" 
                       class="w-full px-4 py-2 border rounded" 
                       placeholder="Enter your Whatify Bearer Token">
                <p class="text-sm text-gray-500 mt-1">
                    Get your token from <a href="https://whatify.in" target="_blank" class="text-blue-600 underline">Whatify.in</a>
                </p>
            </div>
        </div>

        <!-- Google Calendar Integration -->
        <div class="bg-white p-6 rounded shadow">
            <div class="flex items-center gap-3 mb-4">
                <i class="fab fa-google text-red-500 text-3xl"></i>
                <h3 class="text-xl font-semibold">Google Calendar Integration</h3>
            </div>
            
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="google_calendar_enabled" value="1" 
                           {{ old('google_calendar_enabled', $company->google_calendar_enabled) ? 'checked' : '' }} 
                           class="form-checkbox h-5 w-5 text-blue-600">
                    <span class="ml-2 text-gray-700">Enable Google Calendar Sync</span>
                </label>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-semibold">Google Calendar Credentials (JSON)</label>
                <textarea name="google_calendar_credentials" rows="6" 
                          class="w-full px-4 py-2 border rounded font-mono text-sm"
                          placeholder='{"type": "service_account", "project_id": "..."}'>{{ old('google_calendar_credentials', $company->google_calendar_credentials) }}</textarea>
                <p class="text-sm text-gray-500 mt-1">
                    Upload your Google Calendar API credentials JSON file content here.
                    <a href="https://console.cloud.google.com" target="_blank" class="text-blue-600 underline">Get credentials</a>
                </p>
            </div>

            @if($company->google_calendar_credentials && !$company->google_calendar_enabled)
            <a href="{{ route('integrations.google.auth') }}" 
               class="inline-block bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600">
                <i class="fab fa-google"></i> Connect Google Calendar
            </a>
            @elseif($company->google_calendar_enabled)
            <div class="p-3 bg-green-100 text-green-700 rounded flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span>Google Calendar is connected and syncing</span>
            </div>
            @endif
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded hover:bg-blue-700 font-semibold">
                Save Integration Settings
            </button>
        </div>
    </form>

    <!-- Instructions -->
    <div class="bg-blue-50 p-6 rounded">
        <h4 class="font-semibold mb-3 text-blue-900">Setup Instructions:</h4>
        <div class="space-y-4 text-sm text-blue-800">
            <div>
                <strong>Google Calendar Setup:</strong>
                <ol class="list-decimal pl-5 mt-2 space-y-1">
                    <li>Go to <a href="https://console.cloud.google.com" target="_blank" class="underline">Google Cloud Console</a></li>
                    <li>Create a new project or select existing</li>
                    <li>Enable Google Calendar API</li>
                    <li>Create Service Account credentials</li>
                    <li>Download JSON key and paste above</li>
                    <li>Click "Connect Google Calendar" button</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
