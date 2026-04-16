<?php

namespace Database\Factories;

use App\Models\EventRegistration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EventRegistration>
 */
class EventRegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'notice_id' => NoticeFactory::new()->event()->withForm(),
            'user_id' => UserFactory::new(),
            'form_data' => ['field_1' => ['label' => 'Full Name', 'type' => 'text', 'value' => fake()->name()]],
        ];
    }
}
