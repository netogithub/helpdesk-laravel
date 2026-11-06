<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\Categoria;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => fake()->sentence(6),
            'descripcion' => fake()->paragraph(),
            'estado' => 'abierto',
            'prioridad' => fake()->randomElement(['baja', 'media', 'alta', 'urgente']),
            'categoria_id' => Categoria::factory(),
            'solicitante_id' => User::factory(),
            'tecnico_id' => null,
            'cerrado_en' => null,
        ];
    }
}
