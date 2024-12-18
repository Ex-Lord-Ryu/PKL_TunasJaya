<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMotor extends Model
{
    use HasFactory;

    protected $table = 'order_motors';

    protected $fillable = [
        'user_id',
        'motor_id',
        'warna_id',
        'jumlah_motor',
        'pengiriman',
        'pembayaran',
        'no_rangka',
        'no_mesin',
        'status',
        'harga_jual',
        'dp',
        'tenor',
        'angsuran',
        'nama_pembeli',
        'alamat_pembeli',
        'no_hp_pembeli',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function master_motor()
    {
        return $this->belongsTo(MasterMotor::class, 'motor_id', 'id');
    }

    public function master_warna()
    {
        return $this->belongsTo(MasterWarna::class, 'warna_id', 'id_warna');
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'no_mesin', 'no_mesin');
    }
}
