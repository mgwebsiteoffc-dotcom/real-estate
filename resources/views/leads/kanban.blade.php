@extends('layouts.admin')

@section('title', 'Leads Kanban')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Leads Pipeline</h2>
            <p class="text-gray-600 mt-1">Drag and drop to update lead status</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('leads.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-list mr-2"></i> List View
            </a>
            <a href="{{ route('leads.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Add Lead
            </a>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="flex gap-4 overflow-x-auto pb-4" x-data="kanbanBoard()">
        <!-- New -->
        <div class="flex-shrink-0 w-80 bg-gray-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-700 flex items-center">
                    <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                    New
                </h3>
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">
                    {{ $leadsByStatus['new']->count() }}
                </span>
            </div>
            <div class="space-y-3" data-status="new">
                @foreach($leadsByStatus['new'] as $lead)
                    @include('leads.partials.kanban-card', ['lead' => $lead])
                @endforeach
            </div>
        </div>

        <!-- Contacted -->
        <div class="flex-shrink-0 w-80 bg-gray-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-700 flex items-center">
                    <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                    Contacted
                </h3>
                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-1 rounded-full">
                    {{ $leadsByStatus['contacted']->count() }}
                </span>
            </div>
            <div class="space-y-3" data-status="contacted">
                @foreach($leadsByStatus['contacted'] as $lead)
                    @include('leads.partials.kanban-card', ['lead' => $lead])
                @endforeach
            </div>
        </div>

        <!-- Qualified -->
        <div class="flex-shrink-0 w-80 bg-gray-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-700 flex items-center">
                    <span class="w-3 h-3 bg-purple-500 rounded-full mr-2"></span>
                    Qualified
                </h3>
                <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2 py-1 rounded-full">
                    {{ $leadsByStatus['qualified']->count() }}
                </span>
            </div>
            <div class="space-y-3" data-status="qualified">
                @foreach($leadsByStatus['qualified'] as $lead)
                    @include('leads.partials.kanban-card', ['lead' => $lead])
                @endforeach
            </div>
        </div>

        <!-- Proposal -->
        <div class="flex-shrink-0 w-80 bg-gray-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-700 flex items-center">
                    <span class="w-3 h-3 bg-indigo-500 rounded-full mr-2"></span>
                    Proposal
                </h3>
                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2 py-1 rounded-full">
                    {{ $leadsByStatus['proposal']->count() }}
                </span>
            </div>
            <div class="space-y-3" data-status="proposal">
                @foreach($leadsByStatus['proposal'] as $lead)
                    @include('leads.partials.kanban-card', ['lead' => $lead])
                @endforeach
            </div>
        </div>

        <!-- Negotiation -->
        <div class="flex-shrink-0 w-80 bg-gray-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-700 flex items-center">
                    <span class="w-3 h-3 bg-orange-500 rounded-full mr-2"></span>
                    Negotiation
                </h3>
                <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2 py-1 rounded-full">
                    {{ $leadsByStatus['negotiation']->count() }}
                </span>
            </div>
            <div class="space-y-3" data-status="negotiation">
                @foreach($leadsByStatus['negotiation'] as $lead)
                    @include('leads.partials.kanban-card', ['lead' => $lead])
                @endforeach
            </div>
        </div>

        <!-- Won -->
        <div class="flex-shrink-0 w-80 bg-gray-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-700 flex items-center">
                    <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                    Won
                </h3>
                <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">
                    {{ $leadsByStatus['won']->count() }}
                </span>
            </div>
            <div class="space-y-3" data-status="won">
                @foreach($leadsByStatus['won'] as $lead)
                    @include('leads.partials.kanban-card', ['lead' => $lead])
                @endforeach
            </div>
        </div>

        <!-- Lost -->
        <div class="flex-shrink-0 w-80 bg-gray-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-700 flex items-center">
                    <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                    Lost
                </h3>
                <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full">
                    {{ $leadsByStatus['lost']->count() }}
                </span>
            </div>
            <div class="space-y-3" data-status="lost">
                @foreach($leadsByStatus['lost'] as $lead)
                    @include('leads.partials.kanban-card', ['lead' => $lead])
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- SortableJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
function kanbanBoard() {
    return {
        init() {
            const columns = document.querySelectorAll('[data-status]');
            
            columns.forEach(column => {
                new Sortable(column, {
                    group: 'leads',
                    animation: 150,
                    ghostClass: 'opacity-50',
                    onEnd: function (evt) {
                        const leadId = evt.item.dataset.leadId;
                        const newStatus = evt.to.dataset.status;
                        
                        // Update status via AJAX
                        fetch(`/leads/${leadId}/update-status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ status: newStatus })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success message
                                console.log('Status updated successfully');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Revert the move if there's an error
                            evt.item.remove();
                            evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex]);
                        });
                    }
                });
            });
        }
    }
}
</script>
@endsection
