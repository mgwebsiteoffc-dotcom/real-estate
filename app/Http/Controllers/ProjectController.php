<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(['createdBy'])
            ->where('company_id', Auth::user()->company_id)
            ->withCount('properties');

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('project_code', 'like', '%' . $request->search . '%')
                  ->orWhere('builder_name', 'like', '%' . $request->search . '%');
            });
        }

        $projects = $query->latest()->paginate(12);

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'builder_name' => 'nullable|string|max:255',
            'type' => 'required|in:residential,commercial,mixed',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
              'pincode' => 'nullable|string|max:10',       
            'total_towers' => 'nullable|integer|min:0',
            'total_units' => 'nullable|integer|min:0',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
            'launch_date' => 'nullable|date',
            'possession_date' => 'nullable|date',
            'description' => 'nullable|string',
            'amenities' => 'nullable|array',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        $validated['project_code'] = 'PRJ-' . strtoupper(Str::random(8));
        $validated['company_id'] = Auth::user()->company_id;
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'upcoming';
        $validated['available_units'] = $validated['total_units'] ?? 0;

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('projects', 'public');
            $validated['featured_image'] = $path;
        }

        $project = Project::create($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project created successfully!');
    }

    public function show(Project $project)
    {
        $project->load(['images', 'createdBy', 'properties']);
        $project->increment('views_count');
        
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'builder_name' => 'nullable|string|max:255',
            'type' => 'required|in:residential,commercial,mixed',
            'status' => 'required|in:upcoming,under_construction,ready_to_move,completed',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
              'pincode' => 'nullable|string|max:10', 
                       'total_towers' => 'nullable|integer|min:0',
            'total_units' => 'nullable|integer|min:0',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
            'launch_date' => 'nullable|date',
            'possession_date' => 'nullable|date',
            'description' => 'nullable|string',
            'amenities' => 'nullable|array',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            if ($project->featured_image) {
                Storage::disk('public')->delete($project->featured_image);
            }
            $path = $request->file('featured_image')->store('projects', 'public');
            $validated['featured_image'] = $path;
        }

        $project->update($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project updated successfully!');
    }

    public function destroy(Project $project)
    {
        // Delete images
        if ($project->featured_image) {
            Storage::disk('public')->delete($project->featured_image);
        }

        foreach ($project->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully!');
    }
}
