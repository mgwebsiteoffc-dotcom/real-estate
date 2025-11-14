<div class="bg-white rounded-lg shadow-sm p-4 cursor-move hover:shadow-md transition" 
     data-lead-id="{{ $lead->id }}"
     draggable="true">
    <div class="flex justify-between items-start mb-2">
        <h4 class="font-medium text-gray-900">{{ $lead->name }}</h4>
        <span class="text-xs px-2 py-1 rounded
            {{ $lead->priority == 'urgent' ? 'bg-red-100 text-red-700' : '' }}
            {{ $lead->priority == 'high' ? 'bg-orange-100 text-orange-700' : '' }}
            {{ $lead->priority == 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
            {{ $lead->priority == 'low' ? 'bg-gray-100 text-gray-700' : '' }}">
            {{ ucfirst($lead->priority) }}
        </span>
    </div>
    
    <div class="space-y-1 text-sm text-gray-600 mb-3">
        <div class="flex items-center">
            <i class="fas fa-phone w-4 text-gray-400"></i>
            <span class="ml-2">{{ $lead->phone }}</span>
        </div>
        @if($lead->email)
        <div class="flex items-center">
            <i class="fas fa-envelope w-4 text-gray-400"></i>
            <span class="ml-2">{{ $lead->email }}</span>
        </div>
        @endif
        @if($lead->budget_min && $lead->budget_max)
        <div class="flex items-center">
            <i class="fas fa-rupee-sign w-4 text-gray-400"></i>
            <span class="ml-2">₹{{ number_format($lead->budget_min) }} - ₹{{ number_format($lead->budget_max) }}</span>
        </div>
        @endif
    </div>
    
    <div class="flex justify-between items-center pt-3 border-t">
        <div class="flex items-center text-xs text-gray-500">
            <i class="fas fa-user-circle mr-1"></i>
            {{ $lead->assignedTo->name ?? 'Unassigned' }}
        </div>
        <a href="{{ route('leads.show', $lead) }}" class="text-blue-600 hover:text-blue-800 text-sm">
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</div>
