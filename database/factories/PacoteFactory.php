<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pacote>
 */
class PacoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Obter os IDs existentes de Warehouse e Cliente
        $warehouseId = \App\Models\Warehouse::pluck('id')->random();
        $clienteId = \App\Models\Cliente::pluck('id')->random();
        return [
            'rastreio' => $this->faker->unique()->text(10),
            'qtd' => $this->faker->boolean(20) ? 1 : $this->faker->randomNumber(1, 1),
            'peso_aprox' => $this->faker->randomFloat(1, 1, 10),
            'peso' => 0,
            'warehouse_id' => $warehouseId,
            'cliente_id' => $clienteId,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
