<?php

namespace Database\Factories;

use App\Models\JobPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobPost>
 */
class JobPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->jobTitle(),
            'external_link' => fake()->url(),
            'salary' => fake()->optional()->randomElement(['50k-80k BDT', '80k-120k BDT', '120k+ BDT']),
            'expiry_date' => fake()->dateTimeBetween('+1 week', '+3 months'),
            'status' => 'open',
            'is_approved' => true,
        ];
    }

    /**
     * Mark the job as closed.
     */
    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'closed',
        ]);
    }

    /**
     * Mark the job as expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expiry_date' => fake()->dateTimeBetween('-1 month', '-1 day'),
        ]);
    }

    /**
     * Mark the job as pending approval.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => false,
        ]);
    }
}
