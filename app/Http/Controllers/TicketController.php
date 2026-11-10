<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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

        return view('tickets.show', [
            'ticket' => $ticket,
            'tecnicos' => User::where('rol', 'tecnico')->orderBy('name')->get(),
            'usuarios' => User::orderBy('name')->get(),
        ]);
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

    public function asignarTecnico(Request $request, Ticket $ticket)
    {
        if ($ticket->estado === 'cerrado') {
            return back()->withErrors(['tecnico_id' => 'No se puede reasignar un ticket cerrado.']);
        }
    
        $data = $request->validate([
            'tecnico_id' => ['required', Rule::exists('users', 'id')->where('rol', 'tecnico')],
        ], [
            'tecnico_id.required' => 'Selecciona un técnico.',
            'tecnico_id.exists' => 'El técnico seleccionado no es válido.',
        ]);
    
        $ticket->update($data);
    
        return redirect()
            ->route('tickets.show', $ticket)
            ->with('exito', 'Técnico asignado.');
    }
    
    public function cambiarEstado(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'estado' => ['required', Rule::in($ticket->transicionesPermitidas())],
            'actor_id' => ['required', 'exists:users,id'],
        ], [
            'estado.required' => 'Selecciona el nuevo estado.',
            'estado.in' => 'Ese cambio de estado no está permitido.',
            'actor_id.required' => 'Selecciona quién realiza el cambio.',
            'actor_id.exists' => 'El usuario seleccionado no existe.',
        ]);
    
        if ($data['estado'] === 'en_proceso' && ! $ticket->tecnico_id) {
            return back()->withErrors(['estado' => 'Asigna un técnico antes de iniciar el trabajo.']);
        }
    
        DB::transaction(function () use ($ticket, $data) {
            HistorialEstado::create([
                'ticket_id' => $ticket->id,
                'user_id' => $data['actor_id'],
                'estado_anterior' => $ticket->estado,
                'estado_nuevo' => $data['estado'],
            ]);
    
            $ticket->update([
                'estado' => $data['estado'],
                'cerrado_en' => $data['estado'] === 'cerrado' ? now() : null,
            ]);
        });
    
        return redirect()
            ->route('tickets.show', $ticket)
            ->with('exito', 'Estado actualizado.');
    }
}
