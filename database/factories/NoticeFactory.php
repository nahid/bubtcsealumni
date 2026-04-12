<?php

namespace Database\Factories;

use App\Models\Notice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notice>
 */
class NoticeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->admin(),
            'title' => fake()->sentence(4),
            'body' => fake()->paragraphs(2, true),
            'type' => 'notice',
            'event_date' => null,
            'is_published' => true,
        ];
    }

    /**
     * Create an event-type notice.
     */
    public function event(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'event',
            'event_date' => fake()->dateTimeBetween('+1 week', '+3 months'),
        ]);
    }

    /**
     * Create an unpublished notice.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
        ]);
    }
}
