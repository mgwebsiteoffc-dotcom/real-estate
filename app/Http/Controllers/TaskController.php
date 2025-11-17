<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskActivity;
use App\Models\User;
use App\Models\Lead;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TaskAssignedNotification;

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
                    $query->whereDate('due_date', today());
                    break;
                case 'overdue':
                    $query->where('due_date', '<', now())->where('status', '!=', 'completed');
                    break;
                case 'upcoming':
                    $query->where('due_date', '>', now());
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

        $task = Task::create($validated);
        
        // Log activity
        TaskActivity::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'action' => 'created',
            'description' => 'Task created',
        ]);
        
        // Send notification to assigned user (only if not assigning to self)
        if ($task->assigned_to && $task->assigned_to != Auth::id()) {
            $task->assignedTo->notify(new TaskAssignedNotification($task));
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task created and notification sent successfully!');
    }

    public function show(Task $task)
    {
        $task->load(['assignedTo', 'createdBy', 'taskable', 'activities.user']);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $teamMembers = User::where('company_id', Auth::user()->company_id)->get();
        $leads = Lead::where('company_id', Auth::user()->company_id)->latest()->limit(50)->get();
        $properties = Property::where('company_id', Auth::user()->company_id)->latest()->limit(50)->get();
        
        return view('tasks.edit', compact('task', 'teamMembers', 'leads', 'properties'));
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

        // Track status change
        $oldStatus = $task->status;
        $newStatus = $validated['status'];

        if ($newStatus === 'completed' && !$task->completed_at) {
            $validated['completed_at'] = now();
        }

        // Track assignment change
        $oldAssignee = $task->assigned_to;
        $newAssignee = $validated['assigned_to'];

        $task->update($validated);

        // Log status change
        if ($oldStatus != $newStatus) {
            TaskActivity::create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'action' => 'status_changed',
                'description' => 'Status changed',
                'old_value' => $oldStatus,
                'new_value' => $newStatus,
            ]);
        }

        // Log assignment change and notify
        if ($oldAssignee != $newAssignee) {
            TaskActivity::create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'action' => 'reassigned',
                'description' => 'Task reassigned',
                'old_value' => User::find($oldAssignee)->name ?? 'Unknown',
                'new_value' => User::find($newAssignee)->name ?? 'Unknown',
            ]);

            // Notify new assignee
            if ($newAssignee != Auth::id()) {
                $task->assignedTo->notify(new TaskAssignedNotification($task));
            }
        }

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
        $oldStatus = $task->status;
        
        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Log activity
        TaskActivity::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'action' => 'status_changed',
            'description' => 'Task marked as completed',
            'old_value' => $oldStatus,
            'new_value' => 'completed',
        ]);

        return back()->with('success', 'Task marked as completed!');
    }

    // New method: Update status only
    public function updateStatus(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $oldStatus = $task->status;
        $newStatus = $validated['status'];

        $updates = ['status' => $newStatus];
        
        if ($newStatus === 'completed' && !$task->completed_at) {
            $updates['completed_at'] = now();
        }

        $task->update($updates);

        // Log activity
        TaskActivity::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'action' => 'status_changed',
            'description' => 'Status changed',
            'old_value' => $oldStatus,
            'new_value' => $newStatus,
        ]);

        return back()->with('success', 'Task status updated successfully!');
    }

    // New method: Add comment
    public function addComment(Request $request, Task $task)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        TaskActivity::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'action' => 'commented',
            'description' => $validated['comment'],
        ]);

        return back()->with('success', 'Comment added successfully!');
    }
}
