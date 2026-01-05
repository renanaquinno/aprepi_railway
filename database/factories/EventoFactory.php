<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EventoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'titulo' => $this->faker->sentence(3),
            'data_hora' => $this->faker->dateTimeBetween('+1 days', '+1 year'),
            'local' => $this->faker->city(),
            'valor_custo' => $this->faker->randomFloat(2, 100, 5000),
            'valor_arrecadado' => $this->faker->randomFloat(2, 100, 10000),
            'recorrente' => $this->faker->boolean(),
            'descricao' => $this->faker->paragraph(),
        ];
    }
}
