<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('company_id', Auth::user()->company_id)
            ->with('company')
            ->latest()
            ->paginate(20);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:manager,agent',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['company_id'] = Auth::user()->company_id;
        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = 'active';

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        
        $stats = [
            'total_leads' => $user->assignedLeads()->count(),
            'leads_won' => $user->assignedLeads()->where('status', 'won')->count(),
            'leads_active' => $user->assignedLeads()->whereNotIn('status', ['won', 'lost'])->count(),
        ];

        return view('users.show', compact('user', 'stats'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:manager,agent',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        // Only allow password update if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        // Don't allow deletion of user with active leads
        if ($user->assignedLeads()->whereNotIn('status', ['won', 'lost'])->count() > 0) {
            return redirect()->route('users.index')
                ->with('error', 'Cannot delete user with active leads. Please reassign leads first.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully!');
    }
}
