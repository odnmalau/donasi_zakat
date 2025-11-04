<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Donation>
 */
class DonationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = $this->faker->boolean(70) ? User::factory()->create() : null;

        return [
            'campaign_id' => Campaign::factory(),
            'user_id' => $user?->id,
            'donor_name' => $user?->name ?? $this->faker->name(),
            'donor_email' => $user?->email ?? $this->faker->unique()->safeEmail(),
            'donor_phone' => $user?->phone ?? $this->faker->phoneNumber(),
            'amount' => $this->faker->randomElement([100000, 250000, 500000, 1000000, 2500000, 5000000]),
            'payment_proof_path' => null,
            'notes' => $this->faker->boolean(50) ? $this->faker->sentence() : null,
            'status' => $this->faker->randomElement(['pending', 'verified', 'rejected']),
            'verified_by' => null,
            'verified_at' => null,
            'rejection_reason' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'pending',
            'verified_by' => null,
            'verified_at' => null,
            'rejection_reason' => null,
        ]);
    }

    public function verified(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'verified',
            'verified_by' => User::factory(),
            'verified_at' => now(),
            'rejection_reason' => null,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'rejected',
            'verified_by' => User::factory(),
            'verified_at' => now(),
            'rejection_reason' => $this->faker->sentence(),
        ]);
    }
}
