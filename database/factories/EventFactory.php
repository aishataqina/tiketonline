<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    public function definition(): array
    {
        $quota = $this->faker->numberBetween(50, 200);
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'image' => $this->faker->imageUrl(640, 480, 'event', true),
            'event_date' => $this->faker->dateTimeBetween('+1 days', '+1 year'),
            'location' => $this->faker->city(),
            'price' => $this->faker->numberBetween(50000, 200000),
            'quota' => $quota,
            'remaining_quota' => $quota,
            'status' => 'active',
        ];
    }
}
