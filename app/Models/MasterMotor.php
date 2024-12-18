<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMotor extends Model
{
    use HasFactory;

    protected $table = 'master_motors';

    protected $fillable = [
        'nama_motor',
        'nomor_rangka',
        'nomor_mesin',
    ];

    public function pembelian_detail()
    {
        return $this->hasMany(PembelianDetail::class);
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
}
