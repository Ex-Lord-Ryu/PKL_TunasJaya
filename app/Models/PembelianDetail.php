<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    use HasFactory;

    protected $table = 'pembelian_details';

    protected $fillable = [
        'pembelian_id',
        'motor_id',
        'warna_id',
        'jumlah_motor',
        'harga_motor',
        'total_harga',
        'status',
    ];

    public function master_motor()
    {
        return $this->belongsTo(MasterMotor::class, 'motor_id', 'id');
    }

    public function warna()
    {
        return $this->belongsTo(MasterWarna::class, 'warna_id', 'id_warna');
    }

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id', 'id');
    }
}
