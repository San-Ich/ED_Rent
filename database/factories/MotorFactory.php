<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Motor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Motor>
 */
class MotorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $motors = [
            ['brand' => 'Honda', 'model' => 'Vario 160', 'category' => 'Matic', 'harga' => 125000],
            ['brand' => 'Honda', 'model' => 'Beat', 'category' => 'Matic', 'harga' => 100000],
            ['brand' => 'Honda', 'model' => 'Scoopy', 'category' => 'Matic', 'harga' => 110000],
            ['brand' => 'Honda', 'model' => 'PCX', 'category' => 'Matic', 'harga' => 130000],
            ['brand' => 'Honda', 'model' => 'CRF150L', 'category' => 'Trail', 'harga' => 150000],
            ['brand' => 'Honda', 'model' => 'CB150R', 'category' => 'Naked', 'harga' => 150000],
            ['brand' => 'Honda', 'model' => 'CBR150', 'category' => 'Fairing', 'harga' => 180000],
            ['brand' => 'Yamaha', 'model' => 'NMAX', 'category' => 'Matic', 'harga' => 140000],
            ['brand' => 'Yamaha', 'model' => 'Aerox', 'category' => 'Matic', 'harga' => 160000],
            ['brand' => 'Yamaha', 'model' => 'Fazzio', 'category' => 'Matic', 'harga' => 120000],
            ['brand' => 'Yamaha', 'model' => 'R15', 'category' => 'Fairing', 'harga' => 200000],
            ['brand' => 'Kawasaki', 'model' => 'Ninja 250', 'category' => 'Fairing', 'harga' => 300000],
            ['brand' => 'Kawasaki', 'model' => 'KLX', 'category' => 'Trail', 'harga' => 220000],
            ['brand' => 'Suzuki', 'model' => 'Satria F150', 'category' => 'Bebek', 'harga' => 110000],
            ['brand' => 'Suzuki', 'model' => 'Nex', 'category' => 'Matic', 'harga' => 110000],
        ];

        $units = $this->faker->randomElement($motors);

        $category = Category::where('name', $units['category'])->first();

        return [
            'category_id' => $category->id,
            'image' => 'motor-' . fake()->numberBetween(1, 15) . '.jpg',
            'brand' => $units['brand'],
            'model' => $units['model'],
            'plate_nomor' => fake()->randomElement(['B', 'D', 'AB', 'DK', 'L', 'K', 'AB']) . ' ' . fake()->numberBetween(1000, 9999) . ' ' . strToUpper(fake()->bothify('??')),
            'harga_per_hari' => $units['harga'],
            'status' => $this->faker->randomElement(['tersedia', 'dipesan']),
        ];
    }
}
