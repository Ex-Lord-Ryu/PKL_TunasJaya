<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'report_type',
        'start_date',
        'end_date',
        'status',
        'created_by',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getSoldMotorsData()
    {
        $query = SoldMotor::with(['motor', 'warna']);
        
        if ($this->start_date && $this->end_date) {
            $query->whereBetween('tanggal_penjualan', [$this->start_date, $this->end_date]);
        }

        return $query->get();
    }

    public function getOrderMotorsData()
    {
        $query = OrderMotor::with(['master_motor', 'master_warna']);
        
        if ($this->start_date && $this->end_date) {
            $query->whereBetween('created_at', [$this->start_date, $this->end_date]);
        }

        return $query->get();
    }

    public function getStocksData()
    {
        $query = Stock::with(['master_motor', 'master_warna']);
        
        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->get();
    }
}
