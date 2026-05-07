<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert(
            [
                ['id' => 1, 'name' => 'Fairing', 'slug' => 'fairing'],
                ['id' => 2, 'name' => 'Matic', 'slug' => 'matic'],
                ['id' => 3, 'name' => 'Naked', 'slug' => 'naked'],
                ['id' => 4, 'name' => 'Trail', 'slug' => 'trail'],
                ['id' => 5, 'name' => 'Bebek', 'slug' => 'bebek'],
            ]
        );
    }
}
