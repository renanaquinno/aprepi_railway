<?php

namespace Database\Factories;

use App\Models\Doacao;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoacaoFactory extends Factory
{
    protected $model = Doacao::class;

    public function definition(): array
    {
        return [
            'data_doacao'     => $this->faker->dateTimeBetween('-2 years', 'now'),
            'valor'           => $this->faker->randomFloat(2, 20, 5000),
            'forma_pagamento' => $this->faker->randomElement(['pix', 'boleto', 'transferência']),
            'observacoes'     => $this->faker->optional()->sentence(),
            'status'          => $this->faker->randomElement(['realizada', 'pendente', 'cancelada']),
            'created_at'      => now(),
            'updated_at'      => now(),
        ];
    }
}
