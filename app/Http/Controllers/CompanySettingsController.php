<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanySettingsController extends Controller
{
    public function edit()
    {
        $company = Auth::user()->company;
        return view('settings.company', compact('company'));
    }

    public function update(Request $request)
    {
        $company = Auth::user()->company;
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'branding_color' => 'nullable|string|max:20'
        ]);
        
        if($request->hasFile('logo')) {
            $path = $request->file('logo')->store('company-logos', 'public');
            $validated['logo'] = $path;
        }

        $company->update($validated);

        return back()->with('success', 'Company settings updated successfully!');
    }
}
