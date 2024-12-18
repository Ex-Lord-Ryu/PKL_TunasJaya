<?php

namespace App\Http\Controllers;

use App\Models\SoldMotor;
use App\Models\OrderMotor;
use App\Http\Requests\StoreSoldMotorRequest;
use App\Http\Requests\UpdateSoldMotorRequest;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class SoldMotorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $soldMotors = SoldMotor::with(['motor', 'warna'])
            ->orderBy('tanggal_penjualan', 'desc')
            ->get();

        $years = SoldMotor::selectRaw('YEAR(tanggal_penjualan) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('layouts.sold_motor.index', compact('soldMotors', 'years'));
    }

    public function show(SoldMotor $soldMotor)
    {
        $soldMotor->load(['motor', 'warna']);
        return view('layouts.sold_motor.show', compact('soldMotor'));
    }

}
