<?php
// app/Imports/EmployeesImport.php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Airport;
use App\Models\Region;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;

class EmployeesImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    private $importedCount = 0;
    private $failedCount = 0;
    private $errors = [];
    private $rowCount = 0;

    public function collection(Collection $rows)
    {
        $this->rowCount = $rows->count();

        foreach ($rows as $row) {
            try {
                // Find airport and region
                $airport = Airport::where('code', $row['airport_code'])->first();
                $region = Region::where('code', $row['region_code'])->first();

                if (!$airport || !$region) {
                    throw new \Exception('Invalid airport or region code');
                }

                Employee::create([
                    'employee_id' => $row['employee_id'],
                    'name' => $row['name'],
                    'pan_number' => strtoupper($row['pan_number']),
                    'designation' => $row['designation'],
                    'department' => $row['department'],
                    'airport_id' => $airport->id,
                    'region_id' => $region->id,
                    'gender' => strtolower($row['gender']),
                    'date_of_birth' => $row['date_of_birth'],
                    'email' => $row['email'],
                    'mobile' => $row['mobile'] ?? null,
                    'sports_category' => $row['sports_category'] ?? null,
                    'blood_group' => $row['blood_group'] ?? null,
                    'medical_conditions' => $row['medical_conditions'] ?? null,
                    'employment_status' => $row['employment_status'] ?? 'active',
                    'password' => Hash::make($row['pan_number']),
                    'force_password_change' => true,
                ]);

                $this->importedCount++;

            } catch (\Exception $e) {
                $this->failedCount++;
                $this->errors[] = [
                    'row' => $this->importedCount + $this->failedCount,
                    'employee_id' => $row['employee_id'] ?? 'N/A',
                    'error' => $e->getMessage()
                ];
            }
        }
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|string|unique:employees,employee_id',
            'name' => 'required|string|max:255',
            'pan_number' => 'required|string|size:10|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
            'designation' => 'required|string',
            'department' => 'required|string',
            'airport_code' => 'required|string|exists:airports,code',
            'region_code' => 'required|string|exists:regions,code',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date',
            'email' => 'required|email|unique:employees,email',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'pan_number.regex' => 'Invalid PAN number format',
            'airport_code.exists' => 'Airport code not found',
            'region_code.exists' => 'Region code not found',
        ];
    }

    public function getRowCount()
    {
        return $this->rowCount;
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getFailedCount()
    {
        return $this->failedCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
