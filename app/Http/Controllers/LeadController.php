<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\LeadAssignedNotification;


class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with(['assignedTo', 'createdBy'])
            ->where('company_id', Auth::user()->company_id);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by assigned user
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $leads = $query->latest()->paginate(20);
        $teamMembers = User::where('company_id', Auth::user()->company_id)->get();

        return view('leads.index', compact('leads', 'teamMembers'));
    }

    public function kanban()
    {
        $statuses = ['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'won', 'lost'];
        
        $leadsByStatus = [];
        foreach ($statuses as $status) {
            $leadsByStatus[$status] = Lead::with(['assignedTo'])
                ->where('company_id', Auth::user()->company_id)
                ->where('status', $status)
                ->latest()
                ->get();
        }

        $teamMembers = User::where('company_id', Auth::user()->company_id)->get();

        return view('leads.kanban', compact('leadsByStatus', 'teamMembers'));
    }

    public function create()
    {
        $teamMembers = User::where('company_id', Auth::user()->company_id)->get();
        return view('leads.create', compact('teamMembers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'required|string|max:20',
            'phone_secondary' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'source' => 'required|in:website,referral,social_media,advertisement,cold_call,walk_in,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'property_type' => 'nullable|string',
            'preferred_location' => 'nullable|string',
            'requirements' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['company_id'] = Auth::user()->company_id;
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'new';

      $lead = Lead::create($validated);
// Send notification if assigned
if ($lead->assigned_to) {
    $lead->assignedTo->notify(new LeadAssignedNotification($lead, Auth::user()->name));
}
        return redirect()->route('leads.index')
            ->with('success', 'Lead created successfully!');
    }

    public function show(Lead $lead)
    {
        $this->authorize('view', $lead);
        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $this->authorize('update', $lead);
        $teamMembers = User::where('company_id', Auth::user()->company_id)->get();
        return view('leads.edit', compact('lead', 'teamMembers'));
    }

    public function update(Request $request, Lead $lead)
    {
        $this->authorize('update', $lead);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'required|string|max:20',
            'phone_secondary' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'status' => 'required|in:new,contacted,qualified,proposal,negotiation,won,lost',
            'source' => 'required|in:website,referral,social_media,advertisement,cold_call,walk_in,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'property_type' => 'nullable|string',
            'preferred_location' => 'nullable|string',
            'requirements' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
$oldAssignee = $lead->assigned_to;
        $lead->update($validated);
// Notify if assigned to someone new
if ($lead->assigned_to && $lead->assigned_to != $oldAssignee) {
    $lead->assignedTo->notify(new LeadAssignedNotification($lead, Auth::user()->name));
}
        return redirect()->route('leads.index')
            ->with('success', 'Lead updated successfully!');
    }

    public function destroy(Lead $lead)
    {
        $this->authorize('delete', $lead);
        $lead->delete();

        return redirect()->route('leads.index')
            ->with('success', 'Lead deleted successfully!');
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,qualified,proposal,negotiation,won,lost',
        ]);

        $lead->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Lead status updated successfully!',
        ]);
    }
}
