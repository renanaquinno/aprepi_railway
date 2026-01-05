<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // senha padrão para todos
            'cpf' => $this->faker->unique()->cpf(false), // CPF sem máscara
            'tipo_usuario' => $this->faker->randomElement(['admin', 'voluntario_adm', 'voluntario_ext', 'socio', 'doador']),
            'data_nascimento' => $this->faker->date('Y-m-d', '2005-01-01'),
            'telefone' => $this->faker->phoneNumber(),
            'endereco' => $this->faker->streetAddress(),
            'cidade' => $this->faker->city(),
            'estado' => $this->faker->stateAbbr(),
            'cep' => $this->faker->postcode(),
            'observacoes' => $this->faker->sentence(),
            'ativo' => true,
            'remember_token' => Str::random(10),
        ];
    }
}
