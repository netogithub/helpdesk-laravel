<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

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
}
