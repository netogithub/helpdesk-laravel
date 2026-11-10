<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Categoria;
use App\Models\HistorialEstado;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['categoria', 'solicitante', 'tecnico'])
            ->latest('id')
            ->paginate(10);

        return view('tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['categoria', 'solicitante', 'tecnico', 'comentarios.autor', 'historial.autor']);

        return view('tickets.show', compact('ticket'));
    }

    public function create()
    {
        return view('tickets.create', [
            'categorias' => Categoria::orderBy('nombre')->get(),
            'usuarios' => User::orderBy('name')->get(),
        ]);
    }
    
    public function store(StoreTicketRequest $request)
    {
        $ticket = DB::transaction(function () use ($request) {
            $ticket = Ticket::create($request->validated() + ['estado' => 'abierto']);
    
            HistorialEstado::create([
                'ticket_id' => $ticket->id,
                'user_id' => $ticket->solicitante_id,
                'estado_anterior' => null,
                'estado_nuevo' => 'abierto',
            ]);
    
            return $ticket;
        });
    
        return redirect()
            ->route('tickets.show', $ticket)
            ->with('exito', 'Ticket creado correctamente.');
    }
    
    public function edit(Ticket $ticket)
    {
        return view('tickets.edit', [
            'ticket' => $ticket,
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }
    
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $ticket->update($request->validated());
    
        return redirect()
            ->route('tickets.show', $ticket)
            ->with('exito', 'Ticket actualizado correctamente.');
    }
}
