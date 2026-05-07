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
            ['brand' => 'Honda', 'model' => 'Vario 160', 'category' => 'Matic'],
            ['brand' => 'Honda', 'model' => 'Beat', 'category' => 'Matic'],
            ['brand' => 'Honda', 'model' => 'Scoopy', 'category' => 'Matic'],
            ['brand' => 'Honda', 'model' => 'PCX', 'category' => 'Matic'],
            ['brand' => 'Honda', 'model' => 'CRF150L', 'category' => 'Trail'],
            ['brand' => 'Honda', 'model' => 'CB150R', 'category' => 'Naked'],
            ['brand' => 'Honda', 'model' => 'CBR150', 'category' => 'Fairing'],
            ['brand' => 'Yamaha', 'model' => 'NMAX', 'category' => 'Matic'],
            ['brand' => 'Yamaha', 'model' => 'Aerox', 'category' => 'Matic'],
            ['brand' => 'Yamaha', 'model' => 'Fazzio', 'category' => 'Matic'],
            ['brand' => 'Yamaha', 'model' => 'R15', 'category' => 'Fairing'],
            ['brand' => 'Kawasaki', 'model' => 'Ninja 250', 'category' => 'Fairing'],
            ['brand' => 'Kawasaki', 'model' => 'Ninja KLX', 'category' => 'Trail'],
            ['brand' => 'Suzuki', 'model' => 'Satria F150', 'category' => 'Bebek'],
            ['brand' => 'Suzuki', 'model' => 'Nex', 'category' => 'Matic'],
        ];

        $units = $this->faker->randomElement($motors);

        $category = Category::where('name', $units['category'])->first();

        return [
            'category_id' => $category->id,
            'image' => 'motor-' . fake()->numberBetween(1, 15) . '.jpg',
            'brand' => $units['brand'],
            'model' => $units['model'],
            'plate_nomor' => fake()->randomElement(['B', 'D', 'AB', 'DK', 'L', 'K', 'AB']) . ' ' . fake()->numberBetween(1000, 9999) . ' ' . strToUpper(fake()->bothify('??')),
            'harga_per_hari' => $this->faker->numberBetween(100000, 1000000),
            'status' => $this->faker->randomElement(['tersedia', 'booking', 'dipesan']),
        ];
    }
}
