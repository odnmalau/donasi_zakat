<?php

namespace Database\Factories;

use App\Models\Category;
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
        $prefix = $this->faker->randomElement([
            'Bantuan',
            'Program',
            'Renovasi',
            'Beasiswa',
            'Inisiatif',
            'Proyek',
            'Kegiatan',
            'Kampanye',
        ]);

        $subject = $this->faker->randomElement([
            'Pendidikan Anak Yatim',
            'Kesehatan Masyarakat',
            'Mushola Desa',
            'Hafiz Quran',
            'Modal Usaha UMKM',
            'Makanan Bergizi Anak',
            'Sumur Bor Desa Terpencil',
            'Pemeriksaan Kesehatan Gratis',
            'Pendidikan Anak Terlantar',
            'Sanitasi Desa',
        ]);

        $title = $prefix.' '.$subject.' #'.$this->faker->unique()->randomNumber(4);

        $startDate = $this->faker->dateTimeBetween('now', '+30 days');
        $endDate = $this->faker->dateTimeBetween($startDate, '+90 days');

        return [
            'category_id' => Category::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
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
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'collected_amount' => $attributes['target_amount'],
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }
}
