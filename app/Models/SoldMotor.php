<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldMotor extends Model
{
    use HasFactory;

    protected $fillable = [
        'motor_id',
        'warna_id',
        'harga',
        'total_harga',
        'no_rangka',
        'no_mesin',
        'tanggal_penjualan',
        'nama_pembeli',
        'alamat_pembeli',
        'no_hp_pembeli',
        'metode_pembayaran',
        'dp',
        'tenor',
        'angsuran'
    ];

    protected $casts = [
        'tanggal_penjualan' => 'date',
        'harga' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'dp' => 'decimal:2',
        'angsuran' => 'decimal:2'
    ];

    public function motor()
    {
        return $this->belongsTo(MasterMotor::class, 'motor_id');
    }

    public function warna()
    {
        return $this->belongsTo(MasterWarna::class, 'warna_id', 'id_warna');
    }
}
