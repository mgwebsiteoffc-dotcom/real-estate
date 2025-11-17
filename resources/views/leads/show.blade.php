@extends('layouts.admin')
@section('title', 'Lead Details - ' . $lead->name)

@section('content')
<div class="container-fluid px-4">
    <!-- Header with Quick Actions -->
    <div class="flex justify-between items-center mb-4">
        <div>
            <h1 class="text-2xl font-bold">{{ $lead->name }}</h1>
            <p class="text-gray-600 text-sm">Lead ID: #{{ $lead->id }}</p>
        </div>
        <div class="flex gap-2">
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->phone) }}" target="_blank" 
               class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 flex items-center gap-2">
                <i class="fab fa-whatsapp"></i> WhatsApp
            </a>
            <a href="tel:{{ $lead->phone }}" 
               class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex items-center gap-2">
                <i class="fas fa-phone"></i> Call
            </a>
            <a href="mailto:{{ $lead->email }}" 
               class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 flex items-center gap-2">
                <i class="fas fa-envelope"></i> Email
            </a>
            <a href="{{ route('leads.edit', $lead) }}" 
               class="bg-teal-500 text-white px-4 py-2 rounded hover:bg-teal-600 flex items-center gap-2">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-12 gap-6">
        <!-- LEFT SIDE: Lead Details (8 columns) -->
        <div class="col-span-12 lg:col-span-8 space-y-6">
            
            <!-- Basic Information Card -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold text-lg mb-4 border-b pb-2 flex items-center gap-2">
                    <i class="fas fa-user text-blue-600"></i> Basic Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Full Name</span>
                        <p class="font-medium">{{ $lead->name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Email</span>
                        <p class="font-medium">{{ $lead->email }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Phone</span>
                        <p class="font-medium">{{ $lead->phone }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Alternate Phone</span>
                        <p class="font-medium">{{ $lead->alternate_phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Status</span>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded text-sm font-medium">
                            {{ ucfirst($lead->status) }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Priority</span>
                        <span class="px-3 py-1 
                            @if(($lead->priority ?? 'medium') == 'high') bg-red-100 text-red-800
                            @elseif(($lead->priority ?? 'medium') == 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800
                            @endif
                            rounded text-sm font-medium">
                            {{ ucfirst($lead->priority ?? 'medium') }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Lead Score</span>
                        <p class="font-bold text-lg text-blue-600">
                            {{ $lead->score->score ?? 0 }} 
                            @if($lead->score)
                            <span class="text-sm text-gray-600">(Grade: {{ $lead->score->grade }})</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Source</span>
                        <p class="font-medium">{{ $lead->source ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Budget Range</span>
                        <p class="font-medium">{{ $lead->budget ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Address Section -->
                @if($lead->address || $lead->city || $lead->state || $lead->country || $lead->pincode)
                <div class="mt-6 pt-4 border-t">
                    <h4 class="font-semibold text-md mb-3">Address Details</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-3">
                            <span class="text-gray-600 text-sm block mb-1">Full Address</span>
                            <p class="font-medium">{{ $lead->address ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 text-sm block mb-1">City</span>
                            <p class="font-medium">{{ $lead->city ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 text-sm block mb-1">State</span>
                            <p class="font-medium">{{ $lead->state ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 text-sm block mb-1">Country</span>
                            <p class="font-medium">{{ $lead->country ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 text-sm block mb-1">PIN Code</span>
                            <p class="font-medium">{{ $lead->pincode ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Property Requirements Card -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold text-lg mb-4 border-b pb-2 flex items-center gap-2">
                    <i class="fas fa-home text-green-600"></i> Property Requirements
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Property Type</span>
                        <p class="font-medium">{{ $lead->property_type ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Property Purpose</span>
                        <p class="font-medium">{{ ucfirst($lead->property_purpose ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Bedrooms</span>
                        <p class="font-medium">{{ $lead->bedrooms ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Bathrooms</span>
                        <p class="font-medium">{{ $lead->bathrooms ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Min Area (sq ft)</span>
                        <p class="font-medium">{{ $lead->min_area ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Max Area (sq ft)</span>
                        <p class="font-medium">{{ $lead->max_area ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Preferred Location</span>
                        <p class="font-medium">{{ $lead->preferred_location ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Min Budget</span>
                        <p class="font-medium">{{ $lead->min_budget ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Max Budget</span>
                        <p class="font-medium">{{ $lead->max_budget ?? 'N/A' }}</p>
                    </div>
                    @if($lead->property_requirements)
                    <div class="md:col-span-3">
                        <span class="text-gray-600 text-sm block mb-1">Additional Requirements</span>
                        <p class="font-medium">{{ $lead->property_requirements }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Management Details Card -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold text-lg mb-4 border-b pb-2 flex items-center gap-2">
                    <i class="fas fa-users-cog text-purple-600"></i> Management Details
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Assigned To</span>
                        <p class="font-medium">{{ $lead->assignedTo->name ?? 'Unassigned' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Created By</span>
                        <p class="font-medium">{{ $lead->createdBy->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Created Date</span>
                        <p class="font-medium">{{ $lead->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Last Updated</span>
                        <p class="font-medium">{{ $lead->updated_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Last Contact Date</span>
                        <p class="font-medium">{{ $lead->last_contact_date ? $lead->last_contact_date->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600 text-sm block mb-1">Expected Close Date</span>
                        <p class="font-medium">{{ $lead->expected_close_date ? $lead->expected_close_date->format('M d, Y') : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold text-lg mb-4 border-b pb-2 flex items-center gap-2">
                    <i class="fas fa-sticky-note text-yellow-600"></i> Notes
                </h3>
                <form action="{{ route('leads.notes.update', $lead) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <textarea name="notes" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500" 
                              rows="5" placeholder="Add notes about this lead...">{{ $lead->notes }}</textarea>
                    <button type="submit" class="mt-3 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        <i class="fas fa-save"></i> Save Notes
                    </button>
                </form>
            </div>

            <!-- Follow-ups Section -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-semibold text-lg mb-4 border-b pb-2 flex items-center gap-2">
                    <i class="fas fa-calendar-check text-orange-600"></i> Follow-ups
                </h3>
                
                <!-- Add Follow-up Form -->
                <form action="{{ route('leads.follow-ups.store', $lead) }}" method="POST" 
                      class="mb-6 p-4 bg-gray-50 rounded border">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-sm font-medium mb-1">Follow-up Date & Time</label>
                            <input type="datetime-local" name="follow_up_date" 
                                   class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Assign To</label>
                            <select name="assigned_to" class="w-full border rounded px-3 py-2" required>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Notes</label>
                        <textarea name="notes" placeholder="Follow-up notes..." 
                                  class="w-full border rounded px-3 py-2" rows="2"></textarea>
                    </div>
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                        <i class="fas fa-calendar-plus"></i> Schedule Follow-up
                    </button>
                </form>

                <!-- Follow-ups List -->
                <div class="space-y-3">
                    @forelse($lead->followUps as $followUp)
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded border hover:shadow-sm transition">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-calendar-alt text-blue-600"></i>
                                <strong class="text-gray-800">{{ $followUp->follow_up_date->format('M d, Y h:i A') }}</strong>
                            </div>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-user text-gray-400"></i> 
                                Assigned to: <span class="font-medium">{{ $followUp->assignedTo->name }}</span>
                            </p>
                            @if($followUp->notes)
                            <p class="text-sm text-gray-700 mt-2 pl-5">{{ $followUp->notes }}</p>
                            @endif
                        </div>
                        <div>
                            @if($followUp->status === 'pending')
                            <form action="{{ route('follow-ups.complete', $followUp) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm">
                                    <i class="fas fa-check"></i> Mark Complete
                                </button>
                            </form>
                            @else
                            <span class="text-green-600 font-semibold flex items-center gap-1">
                                <i class="fas fa-check-circle"></i> Completed
                            </span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">No follow-ups scheduled</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE: Activity Timeline (4 columns) -->
        <div class="col-span-12 lg:col-span-4">
            <div class="bg-white p-6 rounded shadow sticky top-4">
                <h3 class="font-semibold text-lg mb-4 border-b pb-2 flex items-center gap-2">
                    <i class="fas fa-history text-indigo-600"></i> Activity Timeline
                </h3>
                
                <!-- Add Activity Form -->
                <form action="{{ route('leads.activities.store', $lead) }}" method="POST" 
                      enctype="multipart/form-data" class="mb-6 p-4 bg-gray-50 rounded border">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Activity Type</label>
                        <select name="type" class="w-full border rounded px-3 py-2" required>
                            <option value="call">📞 Call</option>
                            <option value="email">📧 Email</option>
                            <option value="note">📝 Note</option>
                            <option value="meeting">🤝 Meeting</option>
                            <option value="whatsapp">💬 WhatsApp</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Date & Time</label>
                        <input type="datetime-local" name="activity_date" 
                               class="w-full border rounded px-3 py-2" 
                               value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea name="description" placeholder="Activity details..." 
                                  class="w-full border rounded px-3 py-2" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Attachments</label>
                        <input type="file" name="attachments[]" multiple class="w-full text-sm border rounded px-3 py-2">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        <i class="fas fa-plus-circle"></i> Log Activity
                    </button>
                </form>

                <!-- Activities List (Timeline Style) -->
                <div class="space-y-3 max-h-[600px] overflow-y-auto pr-2">
                    @forelse($lead->activities as $activity)
                    <div class="border-l-4 
                        @if($activity->type == 'call') border-blue-500
                        @elseif($activity->type == 'email') border-purple-500
                        @elseif($activity->type == 'whatsapp') border-green-500
                        @elseif($activity->type == 'meeting') border-orange-500
                        @else border-gray-500
                        @endif
                        pl-3 py-3 bg-gray-50 rounded-r hover:shadow-sm transition">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-{{ $activity->type == 'call' ? 'phone' : ($activity->type == 'email' ? 'envelope' : ($activity->type == 'whatsapp' ? 'whatsapp' : ($activity->type == 'meeting' ? 'calendar' : 'sticky-note'))) }} 
                               text-{{ $activity->type == 'call' ? 'blue' : ($activity->type == 'email' ? 'purple' : ($activity->type == 'whatsapp' ? 'green' : ($activity->type == 'meeting' ? 'orange' : 'gray'))) }}-600 mt-1"></i>
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-1">
                                    <span class="font-semibold text-sm">{{ ucfirst($activity->type) }}</span>
                                    <span class="text-xs text-gray-500">{{ $activity->activity_date->format('M d, h:i A') }}</span>
                                </div>
                                <p class="text-xs text-gray-500 mb-1">by {{ $activity->user->name }}</p>
                                <p class="text-sm text-gray-700">{{ $activity->description }}</p>
                                @if($activity->attachments->count())
                                <div class="mt-2 space-y-1">
                                    @foreach($activity->attachments as $attachment)
                                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" 
                                       class="text-xs text-blue-600 hover:underline flex items-center gap-1">
                                        <i class="fas fa-paperclip"></i> {{ Str::limit($attachment->file_name, 25) }}
                                    </a>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-6 text-sm">No activities logged yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
