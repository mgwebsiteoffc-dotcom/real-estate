@extends('layouts.admin')
@section('title', 'Calendar')

@section('content')
<div class="space-y-4">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Calendar</h1>
        <button onclick="openAddModal()" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-plus"></i> New Appointment
        </button>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <div id="calendar"></div>
    </div>
</div>

<!-- Add/Edit Appointment Modal -->
<div id="appointmentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold" id="modalTitle">New Appointment</h3>
            <button onclick="closeModal()" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="appointmentForm">
            @csrf
            <input type="hidden" id="appointmentId">
            
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Title *</label>
                <input type="text" id="title" name="title" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Type *</label>
                <select id="type" name="type" class="w-full border rounded px-3 py-2" required>
                    <option value="meeting">Meeting</option>
                    <option value="site_visit">Site Visit</option>
                    <option value="call">Call</option>
                    <option value="follow_up">Follow Up</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-sm font-medium mb-1">Start Time *</label>
                    <input type="datetime-local" id="start_time" name="start_time" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">End Time *</label>
                    <input type="datetime-local" id="end_time" name="end_time" class="w-full border rounded px-3 py-2" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Assign To *</label>
                <select id="assigned_to" name="assigned_to" class="w-full border rounded px-3 py-2" required>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Location</label>
                <input type="text" id="location" name="location" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Description</label>
                <textarea id="description" name="description" class="w-full border rounded px-3 py-2" rows="3"></textarea>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Save
                </button>
                <button type="button" onclick="closeModal()" class="flex-1 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: '{{ route('calendar.events') }}',
        editable: true,
        selectable: true,
        eventClick: function(info) {
            editAppointment(info.event);
        },
        select: function(info) {
            openAddModal(info.start, info.end);
        }
    });
    calendar.render();

    // Form submission
    document.getElementById('appointmentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveAppointment();
    });
});

function openAddModal(start, end) {
    document.getElementById('modalTitle').textContent = 'New Appointment';
    document.getElementById('appointmentForm').reset();
    document.getElementById('appointmentId').value = '';
    
    if (start) {
        document.getElementById('start_time').value = formatDateTimeLocal(start);
        document.getElementById('end_time').value = formatDateTimeLocal(end || new Date(start.getTime() + 60*60*1000));
    }
    
    document.getElementById('appointmentModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('appointmentModal').classList.add('hidden');
}

function formatDateTimeLocal(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return `${year}-${month}-${day}T${hours}:${minutes}`;
}

function saveAppointment() {
    const formData = new FormData(document.getElementById('appointmentForm'));
    const data = Object.fromEntries(formData);
    
    fetch('{{ route('calendar.store') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endsection
