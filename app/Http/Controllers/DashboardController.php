<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Property;
use App\Models\Project;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'super_admin') {
            return $this->superAdminDashboard();
        } else {
            return $this->companyDashboard();
        }
    }

    private function superAdminDashboard()
    {
        $stats = [
            'total_companies' => Company::count(),
            'active_companies' => Company::where('status', 'active')->count(),
            'total_users' => User::count(),
            'total_leads' => Lead::count(),
        ];

        $recentCompanies = Company::with('package')->latest()->limit(5)->get();
        $companiesData = Company::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        return view('dashboard.super-admin', compact('stats', 'recentCompanies', 'companiesData'));
    }

    private function companyDashboard()
    {
        $companyId = Auth::user()->company_id;

        $stats = [
            'total_leads' => Lead::where('company_id', $companyId)->count(),
            'new_leads' => Lead::where('company_id', $companyId)->where('status', 'new')->count(),
            'won_leads' => Lead::where('company_id', $companyId)->where('status', 'won')->count(),
            'total_properties' => Property::where('company_id', $companyId)->count(),
            'available_properties' => Property::where('company_id', $companyId)->where('status', 'available')->count(),
            'sold_properties' => Property::where('company_id', $companyId)->where('status', 'sold')->count(),
            'total_projects' => Project::where('company_id', $companyId)->count(),
            'team_members' => User::where('company_id', $companyId)->count(),
        ];

        // Lead status distribution
        $leadsData = Lead::where('company_id', $companyId)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Recent leads
        $recentLeads = Lead::where('company_id', $companyId)
            ->with(['assignedTo'])
            ->latest()
            ->limit(5)
            ->get();

        // Recent properties
        $recentProperties = Property::where('company_id', $companyId)
            ->latest()
            ->limit(5)
            ->get();

        // Top performing agents
        $topAgents = User::where('company_id', $companyId)
            ->where('role', 'agent')
            ->withCount(['assignedLeads as won_leads' => function($query) {
                $query->where('status', 'won');
            }])
            ->orderBy('won_leads', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.company', compact('stats', 'leadsData', 'recentLeads', 'recentProperties', 'topAgents'));
    }
}
