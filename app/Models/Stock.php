<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stocks';

    protected $fillable = [
        'pembelian_detail_id',
        'motor_id',
        'warna_id',
        'no_rangka',
        'no_mesin',
        'harga_beli',
        'harga_jual',
        'order_id',
        'status'
    ];

    public function master_motor()
    {
        return $this->belongsTo(MasterMotor::class, 'motor_id', 'id');
    }

    public function master_warna()
    {
        return $this->belongsTo(MasterWarna::class, 'warna_id', 'id_warna');
    }

    public function pembelian_detail()
    {
        return $this->belongsTo(PembelianDetail::class, 'pembelian_detail_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(OrderMotor::class, 'order_id');
    }
}
