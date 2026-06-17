<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $fillable = ['user_id', 'motor_id','kode_booking', 'tanggal_mulai', 'tanggal_rencana_kembali', 'tanggal_pengembalian', 'total_harga', 'penalty', 'status', 'metode_pengantaran', 'alamat_pengantaran', 'cabang_kembali_id', 'foto_serah_terima_cabang',
        'denda_snap_token',
        'denda_expired_at'];
    
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
