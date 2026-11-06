<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categoria;
use App\Models\Comentario;
use App\Models\HistorialEstado;
use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->administrador()->create([
            'name' => 'Administrador',
            'email' => 'admin@helpdesk.test',
        ]);

        $tecnicos = User::factory()->tecnico()->count(3)->create();
        $usuarios = User::factory()->count(8)->create();

        $categorias = collect([
            'Hardware', 'Software', 'Red', 'Cuentas y accesos', 'Impresoras',
        ])->map(fn ($nombre) => Categoria::create(['nombre' => $nombre]));

        $flujo = ['abierto', 'en_proceso', 'resuelto', 'cerrado'];

        for ($i = 0; $i < 30; $i++) {
            $estado = fake()->randomElement($flujo);
            $hasta = array_search($estado, $flujo);
            $tecnico = $hasta > 0 ? $tecnicos->random() : null;

            $ticket = Ticket::factory()->create([
                'estado' => $estado,
                'categoria_id' => $categorias->random()->id,
                'solicitante_id' => $usuarios->random()->id,
                'tecnico_id' => $tecnico?->id,
                'cerrado_en' => $estado === 'cerrado' ? now() : null,
            ]);

            // Historial: un registro por cada estado recorrido
            $anterior = null;
            for ($j = 0; $j <= $hasta; $j++) {
                HistorialEstado::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $j === 0 ? $ticket->solicitante_id : $tecnico->id,
                    'estado_anterior' => $anterior,
                    'estado_nuevo' => $flujo[$j],
                ]);
                $anterior = $flujo[$j];
            }

            // Entre 0 y 4 comentarios, del solicitante o del técnico
            $autores = array_values(array_filter([$ticket->solicitante_id, $ticket->tecnico_id]));
            for ($k = 0; $k < fake()->numberBetween(0, 4); $k++) {
                Comentario::factory()->create([
                    'ticket_id' => $ticket->id,
                    'user_id' => fake()->randomElement($autores),
                ]);
            }
        }
    }
}
