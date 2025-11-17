<?php

namespace App\Http\Controllers;

use App\Imports\LeadsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class LeadImportController extends Controller
{
    public function showImportForm()
    {
        return view('leads.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $import = new LeadsImport(Auth::user()->company_id, Auth::id());
            
            Excel::import($import, $request->file('file'));

            $imported = $import->getImported();
            $duplicates = $import->getDuplicates();
            $errors = $import->getErrors();

            $message = "Import completed! ";
            $message .= "Successfully imported: {$imported} leads. ";
            if ($duplicates > 0) {
                $message .= "Skipped {$duplicates} duplicates. ";
            }
            if (count($errors) > 0) {
                $message .= "Errors: " . count($errors);
            }

            return redirect()->route('leads.index')
                ->with('success', $message)
                ->with('import_stats', [
                    'imported' => $imported,
                    'duplicates' => $duplicates,
                    'errors' => $errors,
                ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'name',
            'email',
            'phone',
            'status',
            'priority',
            'source',
            'budget',
            'address',
            'city',
            'state',
            'country',
            'property_type',
            'notes',
            'assigned_to_email'
        ];

        $sampleData = [
            [
                'John Doe',
                'john@example.com',
                '+1234567890',
                'new',
                'medium',
                'website',
                '50000-100000',
                '123 Main St',
                'New York',
                'NY',
                'USA',
                'Apartment',
                'Interested in 2BHK',
                'agent@yourcompany.com'
            ]
        ];

        $filename = 'lead_import_template.csv';
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($handle, $headers);
        foreach ($sampleData as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);
        exit;
    }
}
