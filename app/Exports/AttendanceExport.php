<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromArray, WithHeadings, WithStyles
{
    protected $attendances;

    public function __construct(array $attendances)
    {
        $this->attendances = $attendances;
    }

    public function array(): array
    {
        $data = [];
        foreach ($this->attendances as $attendance) {
            $data[] = [
                'ID' => $attendance['id'],
                'Student Name' => $attendance['full_name'],
                'Class' => $attendance['class_name'],
                'Session Date' => $attendance['session_date'],
                'Status' => $attendance['status'],
                'Check In Time' => $attendance['check_in_time'],
                'Check Out Time' => $attendance['check_out_time'],
                'Start Time' => $attendance['start_time'],
                'End Time' => $attendance['end_time'],
                'Phone' => $attendance['phone'],
                'Email' => $attendance['email'],
            ];
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Student Name',
            'Class',
            'Session Date',
            'Status',
            'Check In Time',
            'Check Out Time',
            'Start Time',
            'End Time',
            'Phone',
            'Email',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
