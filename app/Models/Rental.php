<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $fillable = ['user_id', 'motor_id','kode_booking', 'tanggal_mulai', 'tanggal_rencana_kembali', 'tanggal_pengembalian', 'total_harga', 'penalty', 'status', 'metode_pengantaran', 'alamat_pengantaran'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function motor()
    {
        return $this->belongsTo(Motor::class);
    }

    public function perlengkapan()
    {
        return $this->belongsToMany(Perlengkapan::class, 'rental_perlengkapan')
            ->withPivot('jumlah')
            ->withTimestamps();
    }
}
