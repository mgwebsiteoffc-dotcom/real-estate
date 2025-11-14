<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntegrationController extends Controller
{
    public function index()
    {
        $company = Auth::user()->company;
        return view('integrations.index', compact('company'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'whatsapp_api_token' => 'nullable|string',
            'whatsapp_enabled' => 'nullable|boolean',
        ]);

        $company = Auth::user()->company;
        $company->update($validated);

        return back()->with('success', 'Integration settings updated successfully!');
    }
}
