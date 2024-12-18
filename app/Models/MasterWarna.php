<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterWarna extends Model
{
    use HasFactory;

    protected $table = 'master_warnas';

    protected $primaryKey = 'id_warna';
    
    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_warna',
        'nama_warna',
    ];

    public function master_motors()
    {
        return $this->belongsToMany(MasterMotor::class, 'stocks', 'warna_id', 'motor_id');
    }
}
