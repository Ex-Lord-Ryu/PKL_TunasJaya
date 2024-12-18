<?php

namespace App\Exports;

use App\Models\Stock;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class StockExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle, WithCustomStartCell
{
    protected $status;

    public function __construct($status = null)
    {
        $this->status = $status;
    }

    public function collection()
    {
        $query = Stock::with(['master_motor', 'master_warna']);
        
        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Nama Motor',
            'Warna',
            'No Rangka',
            'No Mesin',
            'Status',
            'Harga Beli',
            'Harga Jual',
        ];
    }

    public function map($stock): array
    {
        return [
            $stock->master_motor->nama_motor,
            $stock->master_warna->nama_warna,
            $stock->no_rangka,
            $stock->no_mesin,
            $stock->status,
            $stock->harga_beli,
            $stock->harga_jual,
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
        $sheet->getStyle('F2:G' . $sheet->getHighestRow())
              ->getNumberFormat()
              ->setFormatCode('_("Rp"* #,##0_);_("Rp"* -#,##0_);_("Rp"* "-"??_);_(@_)');
    }

    public function title(): string
    {
        return 'Laporan Stock Motor';
    }

    public function startCell(): string
    {
        return 'A1';
    }
} 