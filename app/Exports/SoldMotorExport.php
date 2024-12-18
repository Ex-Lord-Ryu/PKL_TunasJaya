<?php

namespace App\Exports;

use App\Models\SoldMotor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class SoldMotorExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle, WithCustomStartCell
{
    protected $month;
    protected $year;

    public function __construct($month = null, $year = null)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        $query = SoldMotor::with(['motor', 'warna']);
        
        if ($this->month && $this->year) {
            $query->whereMonth('tanggal_penjualan', (int)$this->month)
                  ->whereYear('tanggal_penjualan', (int)$this->year);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Motor',
            'Warna',
            'No Rangka',
            'No Mesin',
            'Nama Pembeli',
            'Total Harga',
        ];
    }

    public function map($soldMotor): array
    {
        return [
            $soldMotor->tanggal_penjualan->format('d-m-Y'),
            $soldMotor->motor->nama_motor,
            $soldMotor->warna->nama_warna,
            $soldMotor->no_rangka,
            $soldMotor->no_mesin,
            $soldMotor->nama_pembeli,
            $soldMotor->total_harga,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4B5563'],
            ],
        ]);

        // Style untuk seluruh tabel
        $sheet->getStyle('A1:G' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Format currency untuk kolom total harga
        $sheet->getStyle('G2:G' . $sheet->getHighestRow())
              ->getNumberFormat()
              ->setFormatCode('_("Rp"* #,##0_);_("Rp"* -#,##0_);_("Rp"* "-"??_);_(@_)');
    }

    public function title(): string
    {
        return 'Laporan Penjualan Motor';
    }

    public function startCell(): string
    {
        return 'A1';
    }
} 