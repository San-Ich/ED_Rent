<?php

namespace App\Models;

use App\Models\Rental;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Motor extends Model
{
    protected $fillable = ['category_id', 'image', 'brand', 'model', 'plate_nomor', 'harga_per_hari', 'status'];
    use HasFactory;
    use SoftDeletes;
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    protected static function booted()
    {
        static::creating(function ($motor) {
            $motor->slug = Str::slug($motor->model . '-' . Str::random(5));
        });
    }

    public function specification()
    {
        return $this->hasOne(MotorSpecification::class);
    }
}
