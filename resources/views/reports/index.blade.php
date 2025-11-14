@extends('layouts.admin')
@section('title', 'Reports & Analytics')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <h1 class="text-3xl font-bold mb-6">Reports & Analytics</h1>

    <!-- Lead statuses -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded shadow">
            <h2 class="font-semibold text-lg mb-3">Lead Status Overview</h2>
            <ul>
                @foreach ($leadStatusCounts as $status => $count)
                <li>{{ ucfirst($status) }}: <strong>{{ $count }}</strong></li>
                @endforeach
            </ul>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h2 class="font-semibold text-lg mb-3">Property Status Overview</h2>
            <ul>
                @foreach($propertyStatusCounts as $status => $count)
                <li>{{ ucfirst($status) }}: <strong>{{ $count }}</strong></li>
                @endforeach
            </ul>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h2 class="font-semibold text-lg mb-3">Top Agents by Leads Won</h2>
            <ol class="list-decimal pl-5 space-y-1">
                @foreach ($agentsPerformance as $agent)
                <li>{{ $agent->name }} — {{ $agent->won_leads }} leads won</li>
                @endforeach
            </ol>
        </div>
    </div>

    <!-- Leads per month chart -->
    <div class="bg-white p-6 rounded shadow mt-6">
        <h2 class="font-semibold text-lg mb-3">Leads Per Month</h2>
        <canvas id="leadsChart" width="100%" height="40"></canvas>
    </div>

    <!-- Leads per project -->
    <div class="bg-white p-6 rounded shadow mt-6">
        <h2 class="font-semibold text-lg mb-3">Leads by Project</h2>
        <ul>
            @foreach($projectLeadCounts as $project)
            <li>{{ $project->name }}: {{ $project->leads_count }}</li>
            @endforeach
        </ul>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const leadData = @json($leadsPerMonth);

    const labels = leadData.map(item => `${item.year}-${item.month.toString().padStart(2, '0')}`);
    const dataCounts = leadData.map(item => item.total);

    const ctx = document.getElementById('leadsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Leads per Month',
                data: dataCounts,
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });
</script>
@endsection
