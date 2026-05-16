<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class EventReport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    private $events;
    private $statistics;

    public function __construct($events, $statistics)
    {
        $this->events = $events;
        $this->statistics = $statistics;
    }

    public function collection()
    {
        return $this->events;
    }

    public function headings(): array
    {
        return [
            'S.No',
            'Event Code',
            'Event Name',
            'Event Type',
            'Venue',
            'Region',
            'Airport',
            'Start Date',
            'End Date',
            'Registration Last Date',
            'Total Registrations',
            'Approved',
            'Pending',
            'Rejected',
            'Status',
            'Participation Rate (%)',
        ];
    }

    public function map($event): array
    {
        static $serial = 0;
        $serial++;

        $totalRegistrations = $event->registrations_count ?? 0;
        $approved = $event->approved_count ?? 0;
        $maxParticipants = $event->max_participants ?? 0;
        $participationRate = $maxParticipants > 0
            ? round(($totalRegistrations / $maxParticipants) * 100, 2)
            : 0;

        return [
            $serial,
            $event->event_code,
            $event->event_name,
            ucwords(str_replace('_', ' ', $event->event_type)),
            $event->venue,
            $event->region->name ?? 'N/A',
            $event->airport->name ?? 'N/A',
            $event->start_date->format('d M Y'),
            $event->end_date->format('d M Y'),
            $event->registration_last_date->format('d M Y'),
            $totalRegistrations,
            $approved,
            $totalRegistrations - $approved - ($event->registrations->where('status', 'rejected')->count() ?? 0),
            $event->registrations->where('status', 'rejected')->count() ?? 0,
            ucfirst($event->status),
            $participationRate,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:P1')->applyFromArray([
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

        return [];
    }

    public function title(): string
    {
        return 'Event Report';
    }
}
