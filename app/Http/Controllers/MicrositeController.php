<?php

namespace App\Http\Controllers;

use App\Models\Microsite;
use App\Models\Property;
use App\Models\MicrositeLead;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MicrositeController extends Controller
{
    public function index()
    {
        $microsites = Microsite::with('property')
            ->where('company_id', Auth::user()->company_id)
            ->latest()
            ->paginate(15);

        return view('microsites.index', compact('microsites'));
    }

    public function create()
    {
        $properties = Property::where('company_id', Auth::user()->company_id)
            ->where('status', 'available')
            ->get();
        
        $templates = [
            'default' => 'Default Template',
            'modern' => 'Modern Template',
            'luxury' => 'Luxury Template',
            'minimal' => 'Minimal Template',
        ];

        return view('microsites.create', compact('properties', 'templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'template' => 'required|in:default,modern,luxury,minimal',
            'custom_domain' => 'nullable|string|unique:microsites',
        ]);

        $microsite = Microsite::create([
            'company_id' => Auth::user()->company_id,
            'created_by' => Auth::id(),
            'property_id' => $validated['property_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'meta_title' => $validated['meta_title'] ?? $validated['title'],
            'meta_description' => $validated['meta_description'] ?? Str::limit($validated['description'], 160),
            'meta_keywords' => $validated['meta_keywords'],
            'template' => $validated['template'],
            'custom_domain' => $validated['custom_domain'],
            'slug' => Str::slug($validated['title']) . '-' . Str::random(6),
            'theme_colors' => [
                'primary' => '#3B82F6',
                'secondary' => '#10B981',
                'accent' => '#F59E0B',
            ],
            'sections' => ['hero', 'features', 'gallery', 'contact'],
        ]);

        return redirect()->route('microsites.edit', $microsite)
            ->with('success', 'Microsite created successfully! Customize it now.');
    }

    public function edit(Microsite $microsite)
    {
        $microsite->load('property');
        
        $templates = [
            'default' => 'Default Template',
            'modern' => 'Modern Template',
            'luxury' => 'Luxury Template',
            'minimal' => 'Minimal Template',
        ];

        return view('microsites.edit', compact('microsite', 'templates'));
    }

    public function update(Request $request, Microsite $microsite)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'template' => 'required|in:default,modern,luxury,minimal',
            'theme_colors' => 'nullable|array',
            'sections' => 'nullable|array',
        ]);

        $microsite->update($validated);

        return back()->with('success', 'Microsite updated successfully!');
    }

    public function publish(Microsite $microsite)
    {
        $microsite->update([
            'is_published' => true,
            'published_at' => now(),
        ]);

        return back()->with('success', 'Microsite published successfully!');
    }

    public function unpublish(Microsite $microsite)
    {
        $microsite->update(['is_published' => false]);
        return back()->with('success', 'Microsite unpublished!');
    }

    public function destroy(Microsite $microsite)
    {
        $microsite->delete();
        return redirect()->route('microsites.index')
            ->with('success', 'Microsite deleted successfully!');
    }

    public function analytics(Microsite $microsite)
    {
        $microsite->load(['capturedLeads', 'property']);
        
        $stats = [
            'total_views' => $microsite->views,
            'total_leads' => $microsite->leads_captured,
            'conversion_rate' => $microsite->views > 0 
                ? round(($microsite->leads_captured / $microsite->views) * 100, 2) 
                : 0,
        ];

        return view('microsites.analytics', compact('microsite', 'stats'));
    }

    // PUBLIC METHODS (No Auth Required)
    
    public function show($slug)
    {
        $microsite = Microsite::where('slug', $slug)
            ->where('is_published', true)
            ->with('property.images')
            ->firstOrFail();

        $microsite->incrementViews();

        return view('microsites.public.' . $microsite->template, compact('microsite'));
    }

    public function captureLead(Request $request, $slug)
    {
        $microsite = Microsite::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'message' => 'nullable|string',
        ]);

        // Create microsite lead
        $micrositeLead = MicrositeLead::create([
            'microsite_id' => $microsite->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'message' => $validated['message'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Create lead in CRM
        $lead = Lead::create([
            'company_id' => $microsite->company_id,
            'created_by' => $microsite->created_by,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'source' => 'microsite',
            'status' => 'new',
            'notes' => "Captured from microsite: {$microsite->title}\nMessage: " . ($validated['message'] ?? 'N/A'),
        ]);

        $micrositeLead->update(['lead_id' => $lead->id]);
        $microsite->increment('leads_captured');

        return back()->with('success', 'Thank you! We will contact you soon.');
    }
}
