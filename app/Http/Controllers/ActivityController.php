<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    public function store(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'type' => 'required|in:call,email,note,meeting,whatsapp',
            'description' => 'required|string',
            'activity_date' => 'required|date',
            'duration_minutes' => 'nullable|integer',
            'status' => 'nullable|in:completed,scheduled,cancelled',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        $activity = Activity::create([
            'company_id' => auth()->user()->company_id,
            'lead_id' => $lead->id,
            'user_id' => auth()->id(),
            'type' => $validated['type'],
            'description' => $validated['description'],
            'activity_date' => $validated['activity_date'],
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'status' => $validated['status'] ?? 'completed',
        ]);

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('activity-attachments', 'public');
                $activity->attachments()->create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return back()->with('success', 'Activity logged successfully');
    }
}
