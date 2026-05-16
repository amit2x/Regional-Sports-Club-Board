<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    private $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Employee::with(['region', 'airport']);

        if (!empty($this->filters['region_id'])) {
            $query->where('region_id', $this->filters['region_id']);
        }

        if (!empty($this->filters['airport_id'])) {
            $query->where('airport_id', $this->filters['airport_id']);
        }

        if (!empty($this->filters['employment_status'])) {
            $query->where('employment_status', $this->filters['employment_status']);
        }

        if (!empty($this->filters['gender'])) {
            $query->where('gender', $this->filters['gender']);
        }

        if (!empty($this->filters['department'])) {
            $query->where('department', $this->filters['department']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'S.No',
            'Employee ID',
            'Name',
            'PAN Number',
            'Designation',
            'Department',
            'Region',
            'Airport',
            'Gender',
            'Date of Birth',
            'Age',
            'Email',
            'Mobile',
            'Sports Category',
            'Blood Group',
            'Medical Conditions',
            'Employment Status',
            'Created Date',
        ];
    }

    public function map($employee): array
    {
        static $serial = 0;
        $serial++;

        return [
            $serial,
            $employee->employee_id,
            $employee->name,
            $employee->pan_number,
            $employee->designation,
            $employee->department,
            $employee->region->name ?? 'N/A',
            $employee->airport->name ?? 'N/A',
            ucfirst($employee->gender),
            $employee->date_of_birth ? $employee->date_of_birth->format('d-m-Y') : 'N/A',
            $employee->age ?? 'N/A',
            $employee->email,
            $employee->mobile ?? 'N/A',
            $employee->sports_category ?? 'N/A',
            $employee->blood_group ?? 'N/A',
            $employee->medical_conditions ?? 'None',
            ucfirst($employee->employment_status),
            $employee->created_at->format('d-m-Y H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
            ],
        ];
    }
}
