<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::withCount('companies')->latest()->get();
        return view('packages.index', compact('packages'));
    }

    public function create()
    {
        return view('packages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'max_users' => 'required|integer|min:-1',
            'max_projects' => 'required|integer|min:-1',
            'max_leads' => 'required|integer|min:-1',
            'max_properties' => 'required|integer|min:-1',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string',
        ]);

        // Filter empty features and convert to JSON
        if (isset($validated['features'])) {
            $features = array_filter($validated['features'], function($feature) {
                return !empty(trim($feature));
            });
            $validated['features'] = !empty($features) ? json_encode(array_values($features)) : null;
        } else {
            $validated['features'] = null;
        }

        $validated['is_active'] = $request->has('is_active');

        Package::create($validated);

        return redirect()->route('packages.index')
            ->with('success', 'Package created successfully!');
    }

    public function edit(Package $package)
    {
        return view('packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'max_users' => 'required|integer|min:-1',
            'max_projects' => 'required|integer|min:-1',
            'max_leads' => 'required|integer|min:-1',
            'max_properties' => 'required|integer|min:-1',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string',
        ]);

        // Filter empty features and convert to JSON
        if (isset($validated['features'])) {
            $features = array_filter($validated['features'], function($feature) {
                return !empty(trim($feature));
            });
            $validated['features'] = !empty($features) ? json_encode(array_values($features)) : null;
        } else {
            $validated['features'] = null;
        }

        $validated['is_active'] = $request->has('is_active');

        $package->update($validated);

        return redirect()->route('packages.index')
            ->with('success', 'Package updated successfully!');
    }

    public function destroy(Package $package)
{
    // Check if package is assigned to active companies
    $activeCompanies = $package->companies()->where('status', 'active')->count();
    
    if ($activeCompanies > 0) {
        return redirect()->route('packages.index')
            ->with('error', "Cannot delete package. It is assigned to {$activeCompanies} active companies.");
    }

    // Soft delete the package
    $package->delete();

    return redirect()->route('packages.index')
        ->with('success', 'Package deleted successfully!');
}

// Show trashed packages
public function trashed()
{
    $packages = Package::onlyTrashed()->withCount('companies')->latest('deleted_at')->get();
    return view('packages.trashed', compact('packages'));
}

// Restore a deleted package
public function restore($id)
{
    $package = Package::withTrashed()->findOrFail($id);
    $package->restore();

    return redirect()->route('packages.index')
        ->with('success', 'Package restored successfully!');
}

// Permanently delete
public function forceDelete($id)
{
    $package = Package::withTrashed()->findOrFail($id);
    
    if ($package->companies()->count() > 0) {
        return redirect()->route('packages.trashed')
            ->with('error', 'Cannot permanently delete. Package has associated companies.');
    }
    
    $package->forceDelete();

    return redirect()->route('packages.trashed')
        ->with('success', 'Package permanently deleted!');
}

}
