<?php

namespace App\Models;

use App\Models\Motor;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];
    
    public function motors()
    {
        return $this->hasMany(Motor::class);
    }
}
