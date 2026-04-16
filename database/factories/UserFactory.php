<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'mobile' => fake()->numerify('01#########'),
            'intake' => fake()->numberBetween(1, 60),
            'shift' => fake()->randomElement(['day', 'evening']),
            'reference_email_1' => null,
            'reference_email_2' => null,
            'status' => 'verified',
            'bio' => fake()->optional()->sentence(),
            'whatsapp_number' => fake()->optional()->numerify('01#########'),
            'facebook_url' => fake()->optional()->url(),
            'linkedin_url' => fake()->optional()->url(),
            'website_url' => fake()->optional()->url(),
            'role' => UserRole::Member,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate a pending (unverified) alumni account.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate an admin user.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::Admin,
            'status' => 'verified',
        ]);
    }

    /**
     * Indicate a manager user.
     */
    public function manager(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::Manager,
            'status' => 'verified',
        ]);
    }

    /**
     * Set specific reference emails.
     */
    public function withReference(string $email, ?string $email2 = null): static
    {
        return $this->state(fn (array $attributes) => [
            'reference_email_1' => $email,
            'reference_email_2' => $email2 ?? $email,
            'status' => 'pending',
        ]);
    }
}
