<?php

namespace App\Exports;

use App\Models\OrderMotor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class OrderMotorExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle, WithCustomStartCell
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
        $query = OrderMotor::with(['master_motor', 'master_warna']);
        
        if ($this->month && $this->year) {
            $query->whereMonth('created_at', (int)$this->month)
                  ->whereYear('created_at', (int)$this->year);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal Order',
            'Nama Motor',
            'Warna',
            'Jumlah',
            'Nama Pembeli',
            'Status',
            'Harga',
        ];
    }

    public function map($order): array
    {
        return [
            $order->created_at->format('d-m-Y'),
            $order->master_motor->nama_motor,
            $order->master_warna->nama_warna,
            $order->jumlah_motor,
            $order->nama_pembeli,
            $order->status,
            $order->harga_jual,
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

        // Format currency untuk kolom harga
        $sheet->getStyle('G2:G' . $sheet->getHighestRow())
              ->getNumberFormat()
              ->setFormatCode('_("Rp"* #,##0_);_("Rp"* -#,##0_);_("Rp"* "-"??_);_(@_)');
    }

    public function title(): string
    {
        return 'Laporan Pembelian Motor';
    }

    public function startCell(): string
    {
        return 'A1';
    }
} 