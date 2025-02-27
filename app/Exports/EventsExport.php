<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EventsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $events;
    protected $counter = 0;

    public function __construct($events)
    {
        $this->events = $events;
    }

    public function collection()
    {
        return $this->events;
    }

    public function map($event): array
    {
        $this->counter++;
        return [
            $this->counter,
            $event->acara,
            $event->nama ? $event->nama . ' - ' . $event->divisi : $event->divisi,
            $event->start_date,
            $event->end_date,
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Acara',
            'Nama - Divisi',
            'Tanggal Mulai',
            'Tanggal Selesai',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // // Buat baris header (baris 1) menjadi bold
        // $sheet->getStyle('1')->getFont()->setBold(true);

        // // Berikan warna latar belakang pada baris header
        // $sheet->getStyle('1')->applyFromArray([
        //     'fill' => [
        //         'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        //         'startColor' => ['argb' => 'FFCCCCCC'], // Warna abu-abu muda
        //     ],
        // ]);

        // Atur lebar kolom secara otomatis
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
    }
}