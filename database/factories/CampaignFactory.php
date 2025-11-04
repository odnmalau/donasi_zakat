<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campaign>
 */
class CampaignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->randomElement([
            'Bantuan Pendidikan Anak Yatim',
            'Program Kesehatan Masyarakat',
            'Renovasi Mushola Desa',
            'Beasiswa Hafiz Quran',
            'Bantuan Modal Usaha UMKM',
            'Program Makanan Bergizi Anak',
            'Sumur Bor untuk Desa Terpencil',
            'Pemeriksaan Kesehatan Gratis',
        ]);

        $startDate = $this->faker->dateTimeBetween('now', '+30 days');
        $endDate = $this->faker->dateTimeBetween($startDate, '+90 days');

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . $this->faker->unique()->randomNumber(5),
            'description' => $this->faker->paragraphs(3, true),
            'image' => null,
            'target_amount' => $this->faker->randomElement([1000000, 2500000, 5000000, 10000000, 25000000]),
            'collected_amount' => 0,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $this->faker->randomElement(['draft', 'active', 'completed']),
            'created_by' => User::factory(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'completed',
            'collected_amount' => $attributes['target_amount'],
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'draft',
        ]);
    }
}
