<?php

namespace App\Http\Controllers;

use App\Models\Automation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutomationController extends Controller
{
    public function index()
    {
        $automations = Automation::where('company_id', Auth::user()->company_id)->paginate(10);
        return view('automations.index', compact('automations'));
    }

    public function create()
    {
        return view('automations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'module' => 'required|in:lead,task,property',
            'trigger_event' => 'required|string',
            'action_type' => 'required|string',
            'action_details' => 'required|json',
            'active' => 'required|boolean',
        ]);

        // Add company_id to auto associate
        $validated['company_id'] = Auth::user()->company_id;

        Automation::create($validated);

        return redirect()->route('automations.index')->with('success', 'Automation created successfully!');
    }

    public function edit(Automation $automation)
    {
        $this->authorize('view', $automation);
        return view('automations.edit', compact('automation'));
    }

    public function update(Request $request, Automation $automation)
    {
        $this->authorize('update', $automation);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'module' => 'required|in:lead,task,property',
            'trigger_event' => 'required|string',
            'action_type' => 'required|string',
            'action_details' => 'required|json',
            'active' => 'required|boolean',
        ]);

        $automation->update($validated);

        return redirect()->route('automations.index')->with('success', 'Automation updated successfully!');
    }

    public function destroy(Automation $automation)
    {
        $this->authorize('delete', $automation);
        $automation->delete();

        return redirect()->route('automations.index')->with('success', 'Automation deleted');
    }
}
