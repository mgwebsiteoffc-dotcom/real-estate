<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Property;
use App\Models\Task;
use App\Models\Appointment;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company_id;
        $userId = Auth::id();
        $isAdmin = Auth::user()->role === 'company_admin';

        // Lead Statistics
        $totalLeads = Lead::where('company_id', $companyId)->count();
        $newLeads = Lead::where('company_id', $companyId)
            ->where('status', 'new')
            ->count();
        $qualifiedLeads = Lead::where('company_id', $companyId)
            ->where('status', 'qualified')
            ->count();
        $wonLeads = Lead::where('company_id', $companyId)
            ->where('status', 'won')
            ->count();
        
        // Conversion Rate
        $conversionRate = $totalLeads > 0 ? round(($wonLeads / $totalLeads) * 100, 2) : 0;

        // My Tasks (if not admin, show only assigned)
        $myTasks = Task::where('company_id', $companyId)
            ->when(!$isAdmin, fn($q) => $q->where('assigned_to', $userId))
            ->where('status', 'pending')
            ->count();

        // Today's Appointments
        $todayAppointments = Appointment::where('company_id', $companyId)
            ->when(!$isAdmin, fn($q) => $q->where('assigned_to', $userId))
            ->whereDate('start_time', today())
            ->count();

        // Properties
        $totalProperties = Property::where('company_id', $companyId)->count();
        $availableProperties = Property::where('company_id', $companyId)
            ->where('status', 'available')
            ->count();

        // Monthly Lead Trends (last 6 months)
        $monthlyLeads = Lead::where('company_id', $companyId)
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Lead by Status
        $leadsByStatus = Lead::where('company_id', $companyId)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Recent Activities
        $recentActivities = Activity::with(['lead', 'user'])
            ->where('company_id', $companyId)
            ->when(!$isAdmin, fn($q) => $q->where('user_id', $userId))
            ->latest('activity_date')
            ->limit(10)
            ->get();

        // Top Performing Agents (admin only)
        $topAgents = [];
        if ($isAdmin) {
            $topAgents = User::withCount(['assignedLeads as won_leads' => function ($q) {
                $q->where('status', 'won');
            }])
            ->where('company_id', $companyId)
            ->orderByDesc('won_leads')
            ->limit(5)
            ->get();
        }

        // Upcoming Follow-ups
        $upcomingFollowUps = \App\Models\FollowUp::with(['lead', 'assignedTo'])
            ->where('company_id', $companyId)
            ->when(!$isAdmin, fn($q) => $q->where('assigned_to', $userId))
            ->where('status', 'pending')
            ->where('follow_up_date', '>=', now())
            ->orderBy('follow_up_date')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalLeads', 'newLeads', 'qualifiedLeads', 'wonLeads', 'conversionRate',
            'myTasks', 'todayAppointments', 'totalProperties', 'availableProperties',
            'monthlyLeads', 'leadsByStatus', 'recentActivities', 'topAgents', 'upcomingFollowUps'
        ));
    }
}
