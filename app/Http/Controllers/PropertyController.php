<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User; // <-- Add this line
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Notifications\PropertyAddedNotification;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with(['createdBy', 'images'])
            ->where('company_id', Auth::user()->company_id);

        // Filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('listing_type')) {
            $query->where('listing_type', $request->listing_type);
        }
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('property_code', 'like', '%' . $request->search . '%')
                  ->orWhere('address', 'like', '%' . $request->search . '%');
            });
        }

        $properties = $query->latest()->paginate(12);

        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        return view('properties.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:apartment,villa,plot,commercial,office,warehouse,shop',
            'listing_type' => 'required|in:sale,rent,lease',
            'price' => 'required|numeric|min:0',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'area_sqft' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'amenities' => 'nullable|array',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        // Generate property code
        $validated['property_code'] = 'PROP-' . strtoupper(Str::random(8));
        $validated['company_id'] = Auth::user()->company_id;
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'available';

        // Calculate price per sqft
        if (!empty($validated['area_sqft']) && $validated['area_sqft'] > 0) {
            $validated['price_per_sqft'] = $validated['price'] / $validated['area_sqft'];
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('properties', 'public');
            $validated['featured_image'] = $path;
        }

        $property = Property::create($validated);

        // Notify all company admins (on add)
        $admins = User::where('company_id', Auth::user()->company_id)
            ->where('role', 'company_admin')
            ->get();

        foreach ($admins as $admin) {
            $admin->notify(new PropertyAddedNotification($property));
        }

        return redirect()->route('properties.show', $property)
            ->with('success', 'Property created successfully!');
    }

    public function show(Property $property)
    {
        $property->load(['images', 'createdBy']);
        $property->increment('views_count');

        return view('properties.show', compact('property'));
    }

    public function edit(Property $property)
    {
        return view('properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:apartment,villa,plot,commercial,office,warehouse,shop',
            'listing_type' => 'required|in:sale,rent,lease',
            'status' => 'required|in:available,sold,rented,reserved,under_construction',
            'price' => 'required|numeric|min:0',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'area_sqft' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'amenities' => 'nullable|array',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        // Calculate price per sqft
        if (!empty($validated['area_sqft']) && $validated['area_sqft'] > 0) {
            $validated['price_per_sqft'] = $validated['price'] / $validated['area_sqft'];
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($property->featured_image) {
                Storage::disk('public')->delete($property->featured_image);
            }
            $path = $request->file('featured_image')->store('properties', 'public');
            $validated['featured_image'] = $path;
        }

        $property->update($validated);

        // Notify all company admins (on update)
        $admins = User::where('company_id', Auth::user()->company_id)
            ->where('role', 'company_admin')
            ->get();

        foreach ($admins as $admin) {
            $admin->notify(new PropertyAddedNotification($property));
        }

        return redirect()->route('properties.show', $property)
            ->with('success', 'Property updated successfully!');
    }

    public function destroy(Property $property)
    {
        // Delete images
        if ($property->featured_image) {
            Storage::disk('public')->delete($property->featured_image);
        }

        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $property->delete();

        return redirect()->route('properties.index')
            ->with('success', 'Property deleted successfully!');
    }
}
