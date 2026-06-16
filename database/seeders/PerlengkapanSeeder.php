<?php

namespace Database\Seeders;

use App\Models\Perlengkapan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PerlengkapanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Perlengkapan::insert(
            [
                ['id' => 1, 'nama_perlengkapan' => 'Sarung Tangan Alpinestars', 'harga_per_hari' => '20000', 'stok' => '30'],
                ['id' => 2, 'nama_perlengkapan' => 'Action Camera GoPro Hero', 'harga_per_hari' => '50000', 'stok' => '30'],
            ]
        );
    }
}
