<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntegrationController extends Controller
{public function index()
{
    $company = Auth::user()->company;
    return view('integrations.index', compact('company'));
}

public function update(Request $request)
{
    $validated = $request->validate([
        'whatsapp_api_token' => 'nullable|string',
        'whatsapp_enabled' => 'nullable|boolean',
        'google_calendar_enabled' => 'nullable|boolean',
        'google_calendar_credentials' => 'nullable|string',
    ]);

    $company = Auth::user()->company;
    $company->update([
        'whatsapp_api_token' => $request->whatsapp_api_token,
        'whatsapp_enabled' => $request->has('whatsapp_enabled'),
        'google_calendar_enabled' => $request->has('google_calendar_enabled'),
        'google_calendar_credentials' => $request->google_calendar_credentials,
    ]);

    return back()->with('success', 'Integration settings updated successfully!');
}

public function googleCalendarAuth()
{
    $company = Auth::user()->company;
    
    if (!$company->google_calendar_credentials) {
        return back()->with('error', 'Please upload Google Calendar credentials first');
    }

    $service = new \App\Services\GoogleCalendarService($company->google_calendar_credentials);
    $authUrl = $service->getAuthUrl();

    return redirect($authUrl);
}

public function googleCalendarCallback(Request $request)
{
    $code = $request->get('code');
    $company = Auth::user()->company;

    $service = new \App\Services\GoogleCalendarService($company->google_calendar_credentials);
    $token = $service->authenticate($code);

    if (isset($token['error'])) {
        return redirect()->route('integrations.index')->with('error', 'Authentication failed');
    }

    $company->update([
        'google_calendar_enabled' => true,
    ]);

    return redirect()->route('integrations.index')->with('success', 'Google Calendar connected successfully!');
}
}
