<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produto>
 */
class ProdutoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->words(2, true),
            'descricao' => $this->faker->sentence(10),
            'codigo' => strtoupper($this->faker->unique()->bothify('PROD-#####')),
            'preco' => $this->faker->randomFloat(2, 10, 5000)
        ];
    }
}
