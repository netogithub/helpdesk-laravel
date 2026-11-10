<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'contenido' => ['required', 'string', 'min:3', 'max:2000'],
            'user_id' => ['required', 'exists:users,id'],
        ], [
            'contenido.required' => 'Escribe un comentario.',
            'contenido.min' => 'El comentario debe tener al menos :min caracteres.',
            'contenido.max' => 'El comentario no puede superar :max caracteres.',
            'user_id.required' => 'Selecciona quién comenta.',
            'user_id.exists' => 'El usuario seleccionado no existe.',
        ]);

        $ticket->comentarios()->create($data);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('exito', 'Comentario agregado.');
    }
}
