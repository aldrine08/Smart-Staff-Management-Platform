<?php

namespace App\Exports;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class OffDayRequestsExport implements
    FromCollection,
    WithHeadings,
    WithCustomStartCell,
    WithEvents
{
    protected Collection $requests;

    protected array $filters;

    public function __construct($requests, array $filters = [])
    {
        $this->requests = collect($requests);
        $this->filters = $filters;
    }

    /**
     * Start the column headings at row 7.
     */
    public function startCell(): string
    {
        return 'A7';
    }

    /**
     * Excel column headings.
     */
    public function headings(): array
    {
        return [
            'NO.',
            'STAFF NAME',
            'EMPLOYMENT NUMBER',
            'PHONE',
            'EMAIL',
            'UNIT',
            'DEPARTMENT',
            'APPLICATION DATE',
            'APPLICATION TIME',
            'OFF DAY START DATE',
            'OFF DAY END DATE',
            'DURATION (DAYS)',
            'REASON',
            'STATUS',
            'ADMIN COMMENT',
        ];
    }

    /**
     * Transform off-day requests into Excel rows.
     */
    public function collection()
    {
        return $this->requests
            ->values()
            ->map(function ($request, $index) {

                $user = $request->user;

                $startDate = CarbonImmutable::parse(
                    $request->start_date
                );

                $endDate = CarbonImmutable::parse(
                    $request->end_date
                );

                /*
                 * The existing approval logic treats end_date
                 * as an exclusive boundary.
                 *
                 * Example:
                 * 29 Oct to 30 Oct = 1 requested day.
                 */
                $duration = $endDate->greaterThan($startDate)
                    ? (int) $startDate->diffInDays($endDate)
                    : 0;

                $submittedAt = $request->created_at
                    ? CarbonImmutable::parse(
                        $request->created_at
                    )
                    : null;

                return [
                    'no' => $index + 1,

                    'staff_name' => $user?->name ?? 'N/A',

                    'employment_number' =>
                        $user?->employment_number ?? 'N/A',

                    'phone' => $user?->phone ?? 'N/A',

                    'email' => $user?->email ?? 'N/A',

                    'unit' => $user?->unit?->name ?? 'N/A',

                    'department' =>
                        $user?->department?->name ?? 'N/A',

                    'application_date' => $submittedAt
                        ? $submittedAt->format('d M Y')
                        : 'N/A',

                    'application_time' => $submittedAt
                        ? $submittedAt->format('h:i A')
                        : 'N/A',

                    'start_date' => $startDate->format('d M Y'),

                    'end_date' => $endDate->format('d M Y'),

                    'duration' => $duration,

                    'reason' => $request->reason ?? 'N/A',

                    'status' => ucfirst(
                        $request->status ?? 'Unknown'
                    ),

                    'admin_comment' =>
                        $request->admin_comment ?? '—',
                ];
            });
    }

    /**
     * Generate a readable description of selected filters.
     */
    protected function filterDescription(): string
    {
        $parts = [];

        if (!empty($this->filters['created_month'])) {
            $month = CarbonImmutable::parse(
                $this->filters['created_month'] . '-01'
            )->format('F Y');

            $parts[] = "Application Month: {$month}";
        } elseif (
            !empty($this->filters['created_from']) ||
            !empty($this->filters['created_to'])
        ) {
            $from = !empty($this->filters['created_from'])
                ? CarbonImmutable::parse(
                    $this->filters['created_from']
                )->format('d M Y')
                : 'Any date';

            $to = !empty($this->filters['created_to'])
                ? CarbonImmutable::parse(
                    $this->filters['created_to']
                )->format('d M Y')
                : 'Any date';

            $parts[] = "Application Date Range: {$from} to {$to}";
        } else {
            $parts[] = 'Application Period: All Dates';
        }

        if (!empty($this->filters['staff_name'])) {
            $parts[] = 'Staff: ' . $this->filters['staff_name'];
        }

        if (!empty($this->filters['unit'])) {
            $parts[] = 'Unit: ' . $this->filters['unit'];
        }

        if (!empty($this->filters['department'])) {
            $parts[] = 'Department: ' . $this->filters['department'];
        }

        if (!empty($this->filters['status'])) {
            $parts[] = 'Status: ' . ucfirst(
                $this->filters['status']
            );
        } else {
            $parts[] = 'Status: All';
        }

        return implode(' | ', $parts);
    }

    /**
     * Apply professional Excel formatting.
     */
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $lastColumn = 'O';

                $total = $this->requests->count();

                $approved = $this->requests
                    ->where('status', 'approved')
                    ->count();

                $pending = $this->requests
                    ->where('status', 'pending')
                    ->count();

                $declined = $this->requests
                    ->where('status', 'declined')
                    ->count();

                $generatedAt = now()->format(
                    'd M Y, h:i A'
                );

                /*
                 * REPORT TITLE
                 */

                $sheet->mergeCells("A1:{$lastColumn}1");

                $sheet->setCellValue(
                    'A1',
                    strtoupper(config('app.name', 'HRMS'))
                );

                $sheet->mergeCells("A2:{$lastColumn}2");

                $sheet->setCellValue(
                    'A2',
                    'OFF DAY APPLICATION REPORT'
                );

                $sheet->mergeCells("A3:{$lastColumn}3");

                $sheet->setCellValue(
                    'A3',
                    'Reporting Basis: Staff Application Date (created_at)'
                );

                /*
                 * SUMMARY STATISTICS
                 */

                $sheet->mergeCells('A4:C4');
                $sheet->setCellValue(
                    'A4',
                    "TOTAL REQUESTS: {$total}"
                );

                $sheet->mergeCells('D4:F4');
                $sheet->setCellValue(
                    'D4',
                    "APPROVED: {$approved}"
                );

                $sheet->mergeCells('G4:I4');
                $sheet->setCellValue(
                    'G4',
                    "PENDING: {$pending}"
                );

                $sheet->mergeCells('J4:L4');
                $sheet->setCellValue(
                    'J4',
                    "DECLINED: {$declined}"
                );

                $sheet->mergeCells('M4:O4');
                $sheet->setCellValue(
                    'M4',
                    "GENERATED: {$generatedAt}"
                );

                /*
                 * SELECTED FILTERS
                 */

                $sheet->mergeCells("A5:{$lastColumn}5");

                $sheet->setCellValue(
                    'A5',
                    'SELECTED FILTERS: ' .
                    $this->filterDescription()
                );

                /*
                 * DATE INTERPRETATION
                 */

                $sheet->mergeCells("A6:{$lastColumn}6");

                $sheet->setCellValue(
                    'A6',
                    'Note: Application date comes from created_at. ' .
                    'The off-day end date is exclusive; duration is ' .
                    'calculated as the difference between start_date and end_date.'
                );

                /*
                 * MAIN TITLE STYLING
                 */

                $sheet->getStyle('A1:O1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '172554'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                /*
                 * REPORT SUBTITLE
                 */

                $sheet->getStyle('A2:O3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => '1E3A8A'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'DBEAFE'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                /*
                 * SUMMARY STYLING
                 */

                $sheet->getStyle('A4:O4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 10,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '334155'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                /*
                 * FILTER AND NOTE STYLING
                 */

                $sheet->getStyle('A5:O6')->applyFromArray([
                    'font' => [
                        'size' => 9,
                        'color' => ['rgb' => '475569'],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                $sheet->getStyle('A6:O6')->getFont()
                    ->setItalic(true);

                /*
                 * COLUMN HEADER STYLING
                 */

                $sheet->getStyle('A7:O7')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 10,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1D4ED8'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                /*
                 * DATA ROW STYLING
                 */

                $highestRow = max(
                    7,
                    $sheet->getHighestRow()
                );

                if ($highestRow >= 8) {

                    $sheet->getStyle(
                        "A8:O{$highestRow}"
                    )->applyFromArray([
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => Border::BORDER_HAIR,
                                'color' => ['rgb' => 'CBD5E1'],
                            ],
                        ],
                    ]);

                    // Wrap long reasons and admin comments.
                    $sheet->getStyle(
                        "M8:M{$highestRow}"
                    )->getAlignment()->setWrapText(true);

                    $sheet->getStyle(
                        "O8:O{$highestRow}"
                    )->getAlignment()->setWrapText(true);

                    // Colour-code request statuses.
                    for ($row = 8; $row <= $highestRow; $row++) {

                        $status = strtolower(
                            (string) $sheet->getCell("N{$row}")->getValue()
                        );

                        $statusColor = match ($status) {
                            'approved' => '15803D',
                            'pending' => 'D97706',
                            'declined' => 'DC2626',
                            default => '334155',
                        };

                        $sheet->getStyle("N{$row}")
                            ->getFont()
                            ->setBold(true)
                            ->setColor(
                                new \PhpOffice\PhpSpreadsheet\Style\Color(
                                    'FF' . $statusColor
                                )
                            );
                    }
                }

                /*
                 * COLUMN WIDTHS
                 */

                foreach (range('A', 'O') as $column) {
                    $sheet->getColumnDimension($column)
                        ->setAutoSize(true);
                }

                // Prevent long text columns from becoming too wide.
                $sheet->getColumnDimension('B')->setWidth(25);
                $sheet->getColumnDimension('E')->setWidth(30);
                $sheet->getColumnDimension('M')->setWidth(40);
                $sheet->getColumnDimension('O')->setWidth(40);

                /*
                 * ROW HEIGHTS
                 */

                $sheet->getRowDimension(1)->setRowHeight(30);
                $sheet->getRowDimension(2)->setRowHeight(24);
                $sheet->getRowDimension(3)->setRowHeight(22);
                $sheet->getRowDimension(4)->setRowHeight(25);
                $sheet->getRowDimension(5)->setRowHeight(25);
                $sheet->getRowDimension(6)->setRowHeight(30);
                $sheet->getRowDimension(7)->setRowHeight(32);

                /*
                 * FILTERABLE HEADINGS AND FROZEN HEADER.
                 */

                $sheet->setAutoFilter("A7:O{$highestRow}");

                $sheet->freezePane('A8');
            },
        ];
    }
}
