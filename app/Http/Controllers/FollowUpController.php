<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Models\Lead;
use Illuminate\Http\Request;

class FollowUpController extends Controller
{
    public function store(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'follow_up_date' => 'required|date',
            'assigned_to' => 'required|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        FollowUp::create([
            'company_id' => auth()->user()->company_id,
            'lead_id' => $lead->id,
            'assigned_to' => $validated['assigned_to'],
            'created_by' => auth()->id(),
            'follow_up_date' => $validated['follow_up_date'],
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Follow-up scheduled successfully');
    }

    public function complete(FollowUp $followUp)
    {
        $followUp->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Follow-up marked as completed');
    }
}
