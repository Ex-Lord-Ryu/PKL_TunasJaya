<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\OrderMotor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        
        // Data untuk stok per bulan
        $stockData = Stock::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Data untuk pembelian per bulan
        $purchaseData = OrderMotor::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Data untuk penjualan per bulan
        $salesData = DB::table('sold_motors')
            ->selectRaw('MONTH(tanggal_penjualan) as month, COUNT(*) as total')
            ->whereYear('tanggal_penjualan', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Menyiapkan array 12 bulan
        $months = range(1, 12);
        $monthlyData = [
            'stock' => array_map(function($month) use ($stockData) {
                return $stockData[$month] ?? 0;
            }, $months),
            'purchase' => array_map(function($month) use ($purchaseData) {
                return $purchaseData[$month] ?? 0;
            }, $months),
            'sales' => array_map(function($month) use ($salesData) {
                return $salesData[$month] ?? 0;
            }, $months)
        ];

        // Mendapatkan list tahun untuk filter
        $years = DB::table('sold_motors')
            ->selectRaw('YEAR(tanggal_penjualan) as year')
            ->union(OrderMotor::selectRaw('YEAR(created_at) as year'))
            ->union(Stock::selectRaw('YEAR(created_at) as year'))
            ->distinct()
            ->pluck('year')
            ->sort()
            ->values();

        return view('home', compact('monthlyData', 'years', 'year'));
    }

    public function blank()
    {
        return view('layouts.blank-page');
    }
}
