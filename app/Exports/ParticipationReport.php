<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ParticipationReport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    private $registrations;
    private $statistics;

    public function __construct($registrations, $statistics)
    {
        $this->registrations = $registrations;
        $this->statistics = $statistics;
    }

    public function collection()
    {
        return $this->registrations;
    }

    public function headings(): array
    {
        return [
            'S.No',
            'Registration Number',
            'Employee ID',
            'Employee Name',
            'Gender',
            'Department',
            'Designation',
            'Airport',
            'Region',
            'Event Name',
            'Event Type',
            'Event Dates',
            'Registration Date',
            'Status',
        ];
    }

    public function map($registration): array
    {
        static $serial = 0;
        $serial++;

        return [
            $serial,
            $registration->registration_number,
            $registration->employee->employee_id ?? 'N/A',
            $registration->employee->name ?? 'N/A',
            ucfirst($registration->employee->gender ?? 'N/A'),
            $registration->employee->department ?? 'N/A',
            $registration->employee->designation ?? 'N/A',
            $registration->employee->airport->name ?? 'N/A',
            $registration->employee->region->name ?? 'N/A',
            $registration->event->event_name ?? 'N/A',
            ucwords(str_replace('_', ' ', $registration->event->event_type ?? 'N/A')),
            ($registration->event->start_date ?? '')->format('d M Y') . ' - ' . ($registration->event->end_date ?? '')->format('d M Y'),
            $registration->created_at->format('d M Y H:i'),
            ucfirst($registration->status),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header style
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Add statistics summary
        $lastRow = $this->registrations->count() + 2;
        $sheet->setCellValue('A' . ($lastRow + 2), 'Summary Statistics');
        $sheet->mergeCells('A' . ($lastRow + 2) . ':N' . ($lastRow + 2));
        $sheet->getStyle('A' . ($lastRow + 2))->getFont()->setBold(true);

        $summaryData = [
            ['Total Registrations', $this->statistics['total']],
            ['Approved', $this->statistics['approved']],
            ['Pending', $this->statistics['pending']],
            ['Rejected', $this->statistics['rejected']],
            ['Male Participants', $this->statistics['male']],
            ['Female Participants', $this->statistics['female']],
            ['Unique Employees', $this->statistics['unique_employees']],
            ['Unique Events', $this->statistics['unique_events']],
        ];

        foreach ($summaryData as $index => $data) {
            $row = $lastRow + 3 + $index;
            $sheet->setCellValue('A' . $row, $data[0]);
            $sheet->setCellValue('B' . $row, $data[1]);
        }

        return [];
    }

    public function title(): string
    {
        return 'Participation Report';
    }
}
