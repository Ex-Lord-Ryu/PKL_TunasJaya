<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Report;
use App\Exports\StockExport;
use App\Exports\SoldMotorExport;
use App\Exports\OrderMotorExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\SoldMotor;
use Carbon\Carbon;
use App\Models\Stock;
use App\Models\OrderMotor;

class ReportController extends Controller
{
    public function penjualan(Request $request)
    {
        $query = SoldMotor::with(['motor', 'warna']);
        
        if ($request->month && $request->year) {
            $query->whereMonth('tanggal_penjualan', (int)$request->month)
                  ->whereYear('tanggal_penjualan', (int)$request->year);
        }

        $soldMotors = $query->get();
        
        return view('reports.penjualan', compact('soldMotors'));
    }

    public function pembelian(Request $request)
    {
        $query = OrderMotor::with(['master_motor', 'master_warna']);
        
        if ($request->month && $request->year) {
            $query->whereMonth('created_at', (int)$request->month)
                  ->whereYear('created_at', (int)$request->year);
        }

        $orderMotors = $query->get();
        
        return view('reports.pembelian', compact('orderMotors'));
    }

    public function stock(Request $request)
    {
        $query = Stock::with(['master_motor', 'master_warna']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $stocks = $query->get();
        
        return view('reports.stock', compact('stocks'));
    }

    public function exportPenjualanPDF(Request $request)
    {
        $query = SoldMotor::with(['motor', 'warna']);
        
        if ($request->month && $request->year) {
            $query->whereMonth('tanggal_penjualan', (int)$request->month)
                  ->whereYear('tanggal_penjualan', (int)$request->year);
        }

        $soldMotors = $query->get();
        $periode = $request->month && $request->year 
            ? Carbon::create()->month((int)$request->month)->year((int)$request->year)->format('F Y')
            : 'Semua Periode';
        
        $pdf = PDF::loadView('reports.pdf.penjualan', compact('soldMotors', 'periode'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('laporan-penjualan.pdf');
    }

    public function exportPenjualanExcel(Request $request)
    {
        return Excel::download(new SoldMotorExport($request->start_date, $request->end_date), 'laporan-penjualan.xlsx');
    }

    public function exportPembelianPDF(Request $request)
    {
        $query = OrderMotor::with(['master_motor', 'master_warna']);
        
        if ($request->month && $request->year) {
            $query->whereMonth('created_at', (int)$request->month)
                  ->whereYear('created_at', (int)$request->year);
        }

        $orderMotors = $query->get();
        $periode = $request->month && $request->year 
            ? Carbon::create()->month((int)$request->month)->year((int)$request->year)->format('F Y')
            : 'Semua Periode';
        
        $pdf = PDF::loadView('reports.pdf.pembelian', compact('orderMotors', 'periode'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('laporan-pembelian.pdf');
    }

    public function exportPembelianExcel(Request $request)
    {
        return Excel::download(new OrderMotorExport($request->start_date, $request->end_date), 'laporan-pembelian.xlsx');
    }

    public function exportStockPDF(Request $request)
    {
        $query = Stock::with(['master_motor', 'master_warna']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $stocks = $query->get();
        $status = $request->status ? ucfirst($request->status) : 'Semua Status';
        
        $pdf = PDF::loadView('reports.pdf.stock', compact('stocks', 'status'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('laporan-stock.pdf');
    }

    public function exportStockExcel(Request $request)
    {
        return Excel::download(new StockExport($request->status), 'laporan-stock.xlsx');
    }
}
