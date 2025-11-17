<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Lead;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $view = $request->get('view', 'month'); // day, week, month
        $date = $request->get('date', now());
        
        $users = User::where('company_id', Auth::user()->company_id)->get();
        
        return view('calendar.index', compact('view', 'date', 'users'));
    }

    public function getEvents(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');
        
        $appointments = Appointment::with(['lead', 'property', 'assignedTo'])
            ->where('company_id', Auth::user()->company_id)
            ->whereBetween('start_time', [$start, $end])
            ->get();
        
        $events = $appointments->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'title' => $appointment->title,
                'start' => $appointment->start_time->toIso8601String(),
                'end' => $appointment->end_time->toIso8601String(),
                'backgroundColor' => $this->getEventColor($appointment->type),
                'borderColor' => $this->getEventColor($appointment->type),
                'extendedProps' => [
                    'type' => $appointment->type,
                    'status' => $appointment->status,
                    'lead' => $appointment->lead ? $appointment->lead->name : null,
                    'property' => $appointment->property ? $appointment->property->title : null,
                    'assignedTo' => $appointment->assignedTo->name,
                    'description' => $appointment->description,
                    'location' => $appointment->location,
                ]
            ];
        });
        
        return response()->json($events);
    }

    private function getEventColor($type)
    {
        return match($type) {
            'meeting' => '#3b82f6',
            'site_visit' => '#10b981',
            'call' => '#8b5cf6',
            'follow_up' => '#f59e0b',
            default => '#6b7280',
        };
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'type' => 'required|in:meeting,site_visit,call,follow_up',
        'start_time' => 'required|date',
        'end_time' => 'required|date|after:start_time',
        'assigned_to' => 'required|exists:users,id',
        'lead_id' => 'nullable|exists:leads,id',
        'property_id' => 'nullable|exists:properties,id',
        'description' => 'nullable|string',
        'location' => 'nullable|string',
    ]);

    $appointment = Appointment::create([
        'company_id' => Auth::user()->company_id,
        'created_by' => Auth::id(),
        'title' => $validated['title'],
        'type' => $validated['type'],
        'start_time' => $validated['start_time'],
        'end_time' => $validated['end_time'],
        'assigned_to' => $validated['assigned_to'],
        'lead_id' => $validated['lead_id'] ?? null,
        'property_id' => $validated['property_id'] ?? null,
        'description' => $validated['description'] ?? null,
        'location' => $validated['location'] ?? null,
        'status' => 'scheduled',
    ]);

    // Sync with Google Calendar
    $company = Auth::user()->company;
    if ($company->google_calendar_enabled && $company->google_calendar_credentials) {
        try {
            $googleService = new \App\Services\GoogleCalendarService($company->google_calendar_credentials);
            $googleEventId = $googleService->createEvent($appointment);
            
            if ($googleEventId) {
                $appointment->update(['google_event_id' => $googleEventId]);
            }
        } catch (\Exception $e) {
            Log::error('Google Calendar Sync Error: ' . $e->getMessage());
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Appointment created successfully',
        'appointment' => $appointment
    ]);
}

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:meeting,site_visit,call,follow_up',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'assigned_to' => 'required|exists:users,id',
            'lead_id' => 'nullable|exists:leads,id',
            'property_id' => 'nullable|exists:properties,id',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
            'status' => 'nullable|in:scheduled,completed,cancelled,rescheduled',
        ]);

        $appointment->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Appointment updated successfully'
        ]);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Appointment deleted successfully'
        ]);
    }
}
