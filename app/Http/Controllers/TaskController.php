<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Lead;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['assignedTo', 'createdBy', 'taskable'])
            ->where('company_id', Auth::user()->company_id);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Date filters
        if ($request->filled('filter')) {
            switch ($request->filter) {
                case 'today':
                    $query->today();
                    break;
                case 'overdue':
                    $query->overdue();
                    break;
                case 'upcoming':
                    $query->upcoming();
                    break;
            }
        }

        $tasks = $query->orderBy('due_date')->paginate(20);
        $teamMembers = User::where('company_id', Auth::user()->company_id)->get();

        return view('tasks.index', compact('tasks', 'teamMembers'));
    }

    public function create()
    {
        $teamMembers = User::where('company_id', Auth::user()->company_id)->get();
        $leads = Lead::where('company_id', Auth::user()->company_id)->latest()->limit(50)->get();
        $properties = Property::where('company_id', Auth::user()->company_id)->latest()->limit(50)->get();

        return view('tasks.create', compact('teamMembers', 'leads', 'properties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:call,meeting,email,site_visit,follow_up,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'required|exists:users,id',
            'due_date' => 'required|date|after:now',
            'taskable_type' => 'nullable|string',
            'taskable_id' => 'nullable|integer',
        ]);

        $validated['company_id'] = Auth::user()->company_id;
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'pending';

        Task::create($validated);

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully!');
    }

    public function show(Task $task)
    {
        $task->load(['assignedTo', 'createdBy', 'taskable']);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $teamMembers = User::where('company_id', Auth::user()->company_id)->get();
        return view('tasks.edit', compact('task', 'teamMembers'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:call,meeting,email,site_visit,follow_up,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'assigned_to' => 'required|exists:users,id',
            'due_date' => 'required|date',
            'completion_notes' => 'nullable|string',
        ]);

        if ($request->status === 'completed' && !$task->completed_at) {
            $validated['completed_at'] = now();
        }

        $task->update($validated);

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully!');
    }

    public function complete(Task $task)
    {
        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Task marked as completed!');
    }
}
