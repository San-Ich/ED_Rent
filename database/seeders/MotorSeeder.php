<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Motor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MotorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Motor::factory()->count(30)->create();
    }
}
