<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perlengkapan extends Model
{
    
    protected $fillable = ['nama_perlengkapan', 'harga_per_hari', 'stok'];
}
