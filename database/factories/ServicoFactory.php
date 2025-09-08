<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Servico>
 */
class ServicoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => ucfirst($this->faker->words(2, true)),
            'descricao' => $this->faker->sentence(12),
            'preco' => $this->faker->randomFloat(2, 50, 2000),
            'ativo' => $this->faker->boolean(80),
        ];
    }
}
