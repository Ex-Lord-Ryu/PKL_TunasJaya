<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelians';

    protected $fillable = [
        'vendor_id',
        'invoice_pembelian',
        'status',
        'metode_pembayaran',
        'metode_pengiriman',
        'tanggal_pembelian',
        'tanggal_pengiriman',
        'tanggal_penerimaan',
    ];

    protected $dates = ['tanggal_pembelian', 'tanggal_pengiriman', 'tanggal_penerimaan'];

    protected $casts = [
        'tanggal_pembelian' => 'date',
        'tanggal_pengiriman' => 'date',
        'tanggal_penerimaan' => 'date',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function pembelianDetails()
    {
        return $this->hasMany(PembelianDetail::class);
    }

    public function stock()
    {
        return $this->hasManyThrough(Stock::class, PembelianDetail::class);
    }
}
