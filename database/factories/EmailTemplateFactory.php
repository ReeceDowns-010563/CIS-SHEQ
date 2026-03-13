<?php

namespace Database\Factories;

use App\Models\EmailTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailTemplateFactory extends Factory
{
    protected $model = EmailTemplate::class;

    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->slug(2),
            'name' => $this->faker->words(3, true),
            'subject' => $this->faker->sentence(),
            'body_html' => '<p>' . $this->faker->paragraph() . '</p>',
            'body_text' => $this->faker->paragraph(),
            'variables' => ['name', 'email', 'company'],
            'sample_data' => [
                'name' => $this->faker->name(),
                'email' => $this->faker->email(),
                'company' => $this->faker->company(),
            ],
            'is_active' => $this->faker->boolean(90),
            'category' => $this->faker->randomElement(['user', 'admin', 'notification', 'marketing']),
            'description' => $this->faker->optional()->sentence(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function withCategory(string $category): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => $category,
        ]);
    }
}
