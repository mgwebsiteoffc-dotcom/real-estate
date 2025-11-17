@extends('layouts.admin')
@section('title', 'Import Leads')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">Import Leads</h1>
            <p class="text-gray-600">Upload CSV or Excel file to bulk import leads</p>
        </div>
        <a href="{{ route('leads.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-100 text-green-700 rounded">
        {{ session('success') }}
        
        @if(session('import_stats'))
        <div class="mt-3 text-sm">
            <p><strong>Import Summary:</strong></p>
            <ul class="list-disc pl-5 mt-2">
                <li>Successfully imported: {{ session('import_stats')['imported'] }} leads</li>
                <li>Duplicates skipped: {{ session('import_stats')['duplicates'] }} leads</li>
                @if(count(session('import_stats')['errors']) > 0)
                <li>Errors: {{ count(session('import_stats')['errors']) }}</li>
                @endif
            </ul>
        </div>
        @endif
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-red-100 text-red-700 rounded">
        {{ session('error') }}
    </div>
    @endif

    <!-- Instructions -->
    <div class="bg-blue-50 p-6 rounded shadow">
        <h3 class="font-semibold text-blue-900 mb-3">
            <i class="fas fa-info-circle"></i> Import Instructions
        </h3>
        <ol class="list-decimal pl-5 space-y-2 text-blue-800 text-sm">
            <li>Download the template file below</li>
            <li>Fill in your lead data following the column headers</li>
            <li>Save the file as CSV or Excel format</li>
            <li>Upload the file using the form below</li>
            <li>Review the import summary after upload</li>
        </ol>
        
        <div class="mt-4">
            <a href="{{ route('leads.import.template') }}" 
               class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-download"></i>
                Download CSV Template
            </a>
        </div>
    </div>

    <!-- Upload Form -->
    <div class="bg-white p-6 rounded shadow">
        <h3 class="font-semibold text-lg mb-4">Upload Leads File</h3>
        
        <form action="{{ route('leads.import.process') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Select File (CSV or Excel)</label>
                <input type="file" name="file" accept=".csv,.xlsx,.xls" required
                       class="block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded file:border-0
                              file:text-sm file:font-semibold
                              file:bg-blue-50 file:text-blue-700
                              hover:file:bg-blue-100">
                @error('file')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700 font-semibold">
                <i class="fas fa-upload"></i> Upload and Import Leads
            </button>
        </form>
    </div>

    <!-- Template Columns Reference -->
    <div class="bg-white p-6 rounded shadow">
        <h3 class="font-semibold text-lg mb-4">CSV Column Reference</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Column Name</th>
                        <th class="px-4 py-2 text-left">Required</th>
                        <th class="px-4 py-2 text-left">Format</th>
                        <th class="px-4 py-2 text-left">Example</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <tr>
                        <td class="px-4 py-2 font-mono">name</td>
                        <td class="px-4 py-2 text-red-600">Required</td>
                        <td class="px-4 py-2">Text</td>
                        <td class="px-4 py-2">John Doe</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-mono">email</td>
                        <td class="px-4 py-2 text-red-600">Required</td>
                        <td class="px-4 py-2">Email</td>
                        <td class="px-4 py-2">john@example.com</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-mono">phone</td>
                        <td class="px-4 py-2 text-red-600">Required</td>
                        <td class="px-4 py-2">Text</td>
                        <td class="px-4 py-2">+1234567890</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-mono">status</td>
                        <td class="px-4 py-2">Optional</td>
                        <td class="px-4 py-2">new, contacted, qualified, proposal, won, lost</td>
                        <td class="px-4 py-2">new</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-mono">priority</td>
                        <td class="px-4 py-2">Optional</td>
                        <td class="px-4 py-2">low, medium, high</td>
                        <td class="px-4 py-2">medium</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-mono">source</td>
                        <td class="px-4 py-2">Optional</td>
                        <td class="px-4 py-2">Text</td>
                        <td class="px-4 py-2">website</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-mono">budget</td>
                        <td class="px-4 py-2">Optional</td>
                        <td class="px-4 py-2">Text</td>
                        <td class="px-4 py-2">50000-100000</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-mono">assigned_to_email</td>
                        <td class="px-4 py-2">Optional</td>
                        <td class="px-4 py-2">Email (must exist in system)</td>
                        <td class="px-4 py-2">agent@company.com</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
