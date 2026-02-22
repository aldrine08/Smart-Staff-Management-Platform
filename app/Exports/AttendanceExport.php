<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Attendance;  
use Illuminate\Support\Facades\DB;  
use Carbon\Carbon;  
use Maatwebsite\Excel\Concerns\WithHeadings;


class AttendanceExport implements FromCollection, WithHeadings
{
    protected $attendances;

    public function __construct($attendances)
    {
        $this->attendances = $attendances;
    }

    public function collection()
    {
        return $this->attendances->map(function($att){
            return [
                'Name' => $att->user->name,
                'Phone' => $att->user->phone,
                'Email' => $att->user->email,
                'Unit' => $att->user->unit->name ?? '—',
                'Department' => $att->user->department->name ?? '—',
                'Date' => $att->date->format('d M Y'),
                'Clock In' => $att->clock_in ? $att->clock_in->format('H:i') : '—',
                'Clock Out' => $att->clock_out ? $att->clock_out->format('H:i') : '—',
            ];
        });
    }

    public function headings(): array
    {
        return ['Name','Phone','Email','Unit','Department','Date','Clock In','Clock Out'];
    }
}
