<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(2, true);

        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name).'-'.$this->faker->unique()->randomNumber(5),
            'description' => $this->faker->sentence(),
            'icon' => $this->faker->randomElement(['❤️', '📚', '⚕️', '🤝', '🔗', '🗡️', '✈️', '👶']),
            'is_active' => true,
        ];
    }
}
