<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lead;
use App\Models\Property;
use App\Models\Project;
use App\Models\User;

class ReportsController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company_id;

        // Lead status counts
        $leadStatusCounts = Lead::select('status')
            ->where('company_id', $companyId)
            ->groupBy('status')
            ->selectRaw('count(*) as total')
            ->pluck('total', 'status');

        // Leads per month
        $leadsPerMonth = Lead::selectRaw('YEAR(created_at) year, MONTH(created_at) month, COUNT(*) total')
            ->where('company_id', $companyId)
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Agent wise leads won
        $agentsPerformance = User::select('id', 'name')
            ->withCount(['assignedLeads as won_leads' => function ($q) {
                $q->where('status', 'won');
            }])
            ->where('company_id', $companyId)
            ->orderByDesc('won_leads')
            ->limit(5)
            ->get();

        // Property status counts
        $propertyStatusCounts = Property::select('status')
            ->where('company_id', $companyId)
            ->groupBy('status')
            ->selectRaw('count(*) as total')
            ->pluck('total', 'status');

        // Lead counts by project
        $projectLeadCounts = Project::withCount(['leads' => function ($q) use($companyId) {
            $q->where('company_id', $companyId);
        }])
        ->where('company_id', $companyId)
        ->get();

        return view('reports.index', compact(
            'leadStatusCounts',
            'leadsPerMonth',
            'agentsPerformance',
            'propertyStatusCounts',
            'projectLeadCounts'
        ));
    }
}
