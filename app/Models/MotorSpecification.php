<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotorSpecification extends Model
{
    protected $fillable = [
        'motor_id',
        'kapasitas_mesin',
        'konfigurasi_silinder',
        'transmisi',
        'bahan_bakar_min',
        'sistem_pengereman',
        'tenaga_maksimum',
    ];

    public function motor()
    {
        return $this->belongsTo(Motor::class);
    }
}
