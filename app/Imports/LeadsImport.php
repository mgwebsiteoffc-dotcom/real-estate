<?php

namespace App\Imports;

use App\Models\Lead;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LeadsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    protected $companyId;
    protected $createdBy;
    protected $imported = 0;
    protected $duplicates = 0;
    protected $errors = [];

    public function __construct($companyId, $createdBy)
    {
        $this->companyId = $companyId;
        $this->createdBy = $createdBy;
    }

    public function model(array $row)
    {
        // Check for duplicates by email or phone
        $existingLead = Lead::where('company_id', $this->companyId)
            ->where(function($query) use ($row) {
                $query->where('email', $row['email'])
                      ->orWhere('phone', $row['phone']);
            })
            ->first();

        if ($existingLead) {
            $this->duplicates++;
            Log::info("Duplicate lead skipped: {$row['email']}");
            return null;
        }

        // Find or default assigned user
        $assignedTo = null;
        if (isset($row['assigned_to_email'])) {
            $user = User::where('email', $row['assigned_to_email'])
                       ->where('company_id', $this->companyId)
                       ->first();
            $assignedTo = $user ? $user->id : null;
        }

        $this->imported++;

        return new Lead([
            'company_id' => $this->companyId,
            'created_by' => $this->createdBy,
            'assigned_to' => $assignedTo,
            'name' => $row['name'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'status' => $row['status'] ?? 'new',
            'priority' => $row['priority'] ?? 'medium',
            'source' => $row['source'] ?? 'import',
            'budget' => $row['budget'] ?? null,
            'address' => $row['address'] ?? null,
            'city' => $row['city'] ?? null,
            'state' => $row['state'] ?? null,
            'country' => $row['country'] ?? null,
            'property_type' => $row['property_type'] ?? null,
            'notes' => $row['notes'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'status' => 'nullable|in:new,contacted,qualified,proposal,won,lost',
            'priority' => 'nullable|in:low,medium,high',
        ];
    }

    public function getImported()
    {
        return $this->imported;
    }

    public function getDuplicates()
    {
        return $this->duplicates;
    }

    public function onError(\Throwable $error)
    {
        $this->errors[] = $error->getMessage();
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
