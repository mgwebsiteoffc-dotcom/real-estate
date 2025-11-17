@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-gray-600">Welcome back, {{ Auth::user()->name }}!</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
            <p class="text-xs text-gray-400">{{ now()->format('g:i A') }}</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Leads -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Leads</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalLeads }}</h3>
                    <p class="text-xs text-green-600 mt-2">
                        <i class="fas fa-arrow-up"></i> {{ $newLeads }} new this week
                    </p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Conversion Rate -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Conversion Rate</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $conversionRate }}%</h3>
                    <p class="text-xs text-gray-500 mt-2">{{ $wonLeads }} / {{ $totalLeads }} leads won</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-chart-line text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Properties -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Properties</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalProperties }}</h3>
                    <p class="text-xs text-blue-600 mt-2">{{ $availableProperties }} available</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-home text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Today's Meetings</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $todayAppointments }}</h3>
                    <p class="text-xs text-orange-600 mt-2">{{ $myTasks }} pending tasks</p>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <i class="fas fa-calendar-check text-orange-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Lead Trend Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Lead Trends (Last 6 Months)</h3>
            <div style="height: 300px;">
                <canvas id="leadTrendChart"></canvas>
            </div>
        </div>

        <!-- Lead by Status Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Leads by Status</h3>
            <div style="height: 300px;">
                <canvas id="leadStatusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bottom Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activities -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Recent Activities</h3>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($recentActivities as $activity)
                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded hover:bg-gray-100 transition">
                    <div class="flex-shrink-0">
                        <i class="fas fa-{{ $activity->type == 'call' ? 'phone' : ($activity->type == 'email' ? 'envelope' : ($activity->type == 'whatsapp' ? 'whatsapp' : 'sticky-note')) }} 
                           text-{{ $activity->type == 'call' ? 'blue' : ($activity->type == 'email' ? 'purple' : ($activity->type == 'whatsapp' ? 'green' : 'gray')) }}-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">
                            {{ $activity->user->name }} {{ $activity->type }} with {{ $activity->lead->name ?? 'Unknown' }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $activity->activity_date->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No recent activities</p>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Follow-ups -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Upcoming Follow-ups</h3>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($upcomingFollowUps as $followUp)
                <div class="p-3 bg-orange-50 rounded border-l-4 border-orange-500">
                    <p class="text-sm font-medium text-gray-800">{{ $followUp->lead->name }}</p>
                    <p class="text-xs text-gray-600">{{ $followUp->follow_up_date->format('M d, h:i A') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Assigned to: {{ $followUp->assignedTo->name }}</p>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4 text-sm">No upcoming follow-ups</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Top Agents (Admin Only) -->
    @if(Auth::user()->role === 'company_admin' && count($topAgents) > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Top Performing Agents</h3>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            @foreach($topAgents as $agent)
            <div class="text-center p-4 bg-gray-50 rounded">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <span class="text-2xl font-bold text-blue-600">{{ substr($agent->name, 0, 1) }}</span>
                </div>
                <p class="font-medium text-gray-800">{{ $agent->name }}</p>
                <p class="text-sm text-gray-500">{{ $agent->won_leads }} leads won</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Lead Trend Chart
const leadTrendCtx = document.getElementById('leadTrendChart').getContext('2d');
new Chart(leadTrendCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($monthlyLeads->pluck('month')) !!},
        datasets: [{
            label: 'Leads',
            data: {!! json_encode($monthlyLeads->pluck('total')) !!},
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 2,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { 
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});

// Lead Status Chart
const leadStatusCtx = document.getElementById('leadStatusChart').getContext('2d');
new Chart(leadStatusCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($leadsByStatus->pluck('status')->map(fn($s) => ucfirst($s))) !!},
        datasets: [{
            data: {!! json_encode($leadsByStatus->pluck('total')) !!},
            backgroundColor: [
                'rgb(59, 130, 246)',
                'rgb(16, 185, 129)',
                'rgb(245, 158, 11)',
                'rgb(139, 92, 246)',
                'rgb(239, 68, 68)',
                'rgb(107, 114, 128)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 2,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: {
                        size: 12
                    }
                }
            }
        }
    }
});
</script>
@endsection
