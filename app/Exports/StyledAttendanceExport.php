<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class StyledAttendanceExport implements FromCollection, WithHeadings, WithEvents
{
    protected $attendances;
    protected $filters;
    protected $startOfWeek;
    protected $endOfWeek;

    public function __construct($attendances, $filters = [])
    {
        $this->attendances = $attendances;
        $this->filters = $filters;

        // Determine the week range from start_date or end_date
        $this->startOfWeek = !empty($filters['start_date']) ? Carbon::parse($filters['start_date']) : Carbon::now()->startOfWeek();
        $this->endOfWeek = !empty($filters['end_date']) ? Carbon::parse($filters['end_date']) : Carbon::now()->endOfWeek();
    }

    public function collection()
    {
        $data = collect();
        $grouped = $this->attendances->groupBy(function($att){
            return $att->user->unit->name ?? 'Others';
        });

        foreach ($grouped as $section => $attList) {
            // Section row
            $data->push([
                'no' => '',
                'payment_date' => '',
                'name' => strtoupper($section),
                's_no' => '',
                'dept' => '',
                'sat' => '',
                'sun' => '',
                'mon' => '',
                'tue' => '',
                'wed' => '',
                'thu' => '',
                'fri' => '',
                'special_instruction' => '',
                'clock_in' => '',
                'clock_out' => '',
                'status' => '',
                'late_reason' => '',
            ]);

            $counter = 1;
            foreach ($attList as $att) {

                // Determine P/A for each day (Sat-Fri)
                $weekDays = ['sat','sun','mon','tue','wed','thu','fri'];
                $weekStatus = [];
                foreach ($weekDays as $day) {
                    $dayDate = $this->startOfWeek->copy();
                    switch ($day) {
                        case 'sat': $dayDate = $this->startOfWeek->copy()->next(Carbon::SATURDAY); break;
                        case 'sun': $dayDate = $this->startOfWeek->copy()->next(Carbon::SUNDAY); break;
                        case 'mon': $dayDate = $this->startOfWeek->copy()->next(Carbon::MONDAY); break;
                        case 'tue': $dayDate = $this->startOfWeek->copy()->next(Carbon::TUESDAY); break;
                        case 'wed': $dayDate = $this->startOfWeek->copy()->next(Carbon::WEDNESDAY); break;
                        case 'thu': $dayDate = $this->startOfWeek->copy()->next(Carbon::THURSDAY); break;
                        case 'fri': $dayDate = $this->startOfWeek->copy()->next(Carbon::FRIDAY); break;
                    }

                    $attendanceOnDay = $attList->first(function($a) use ($dayDate, $att) {
                        return Carbon::parse($a->date)->isSameDay($dayDate) && $a->user_id === $att->user_id;
                    });

                    $weekStatus[$day] = $attendanceOnDay ? 'P' : 'A';
                }

                $row = [
                    'no' => $counter,
                    'payment_date' => Carbon::parse($att->date)->format('d/m/Y'),
                    'name' => $att->user->name,
                    's_no' => $att->user->id,
                    'dept' => $att->user->department->name ?? '',
                    'sat' => $weekStatus['sat'],
                    'sun' => $weekStatus['sun'],
                    'mon' => $weekStatus['mon'],
                    'tue' => $weekStatus['tue'],
                    'wed' => $weekStatus['wed'],
                    'thu' => $weekStatus['thu'],
                    'fri' => $weekStatus['fri'],
                    'special_instruction' => $att->late_reason ?? '',
                    'clock_in' => $att->clock_in ? Carbon::parse($att->clock_in)->format('H:i') : '',
                    'clock_out' => $att->clock_out ? Carbon::parse($att->clock_out)->format('H:i') : '',
                    'status' => ucfirst($att->status ?? '-'),
                    'late_reason' => $att->late_reason ?? '',
                ];

                $data->push($row);
                $counter++;
            }
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'NO','PAYMENT DATE','NAMES','S/NO','DEPT',
            'SAT','SUN','MON','TUE','WED','THUR','FRI',
            'SPECIAL INSTRUCTION','CLOCK IN','CLOCK OUT','STATUS','LATE REASON'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // Add Title and filter info at top
                $sheet->insertNewRowBefore(1, 3);
                $sheet->mergeCells('A1:P1');
                $sheet->setCellValue('A1', 'SMART STAFF ATTENDANCE SYSTEM');
                $sheet->mergeCells('A2:P2');
                $sheet->setCellValue('A2', 'STAFF ATTENDANCE DATA');

                // Display filter info
                $filterText = "Filters: ";
                if (!empty($this->filters['start_date'])) $filterText .= "Start: ".$this->filters['start_date']." ";
                if (!empty($this->filters['end_date'])) $filterText .= "End: ".$this->filters['end_date']." ";
                if (!empty($this->filters['unit'])) $filterText .= "Unit: ".$this->filters['unit']." ";
                if (!empty($this->filters['department'])) $filterText .= "Dept: ".$this->filters['department']." ";
                $sheet->mergeCells('A3:P3');
                $sheet->setCellValue('A3', $filterText);

                // Bold the first 3 rows
                $sheet->getStyle('A1:P3')->getFont()->setBold(true)->setSize(12);

                // Bold header row
                $sheet->getStyle('A4:P4')->getFont()->setBold(true);

                // Set column widths
                foreach (range('A','P') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Fill weekend columns red (Sat=F, Sun=G)
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle("F5:G{$highestRow}")
                      ->getFill()
                      ->setFillType(Fill::FILL_SOLID)
                      ->getStartColor()->setARGB('FFFF0000');
            },
        ];
    }
}