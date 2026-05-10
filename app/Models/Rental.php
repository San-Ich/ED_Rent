<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $fillable = ['user_id', 'motor_id', 'tanggal_mulai', 'tanggal_rencana_kembali', 'tanggal_pengembalian', 'total_harga', 'status'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function motor()
    {
        return $this->belongsTo(Motor::class);
    }
}
