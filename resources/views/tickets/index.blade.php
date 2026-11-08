@extends('layouts.app')

@section('title', 'Tickets')

@section('content')
    <h1 class="h3 mb-3">Tickets</h1>

    <div class="table-responsive bg-white shadow-sm rounded">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Categoría</th>
                    <th>Prioridad</th>
                    <th>Estado</th>
                    <th>Solicitante</th>
                    <th>Técnico</th>
                    <th>Creado</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td>
                            <a href="{{ route('tickets.show', $ticket) }}">{{ $ticket->titulo }}</a>
                        </td>
                        <td>{{ $ticket->categoria->nombre }}</td>
                        <td><span class="badge text-bg-{{ $ticket->prioridad_color }}">{{ ucfirst($ticket->prioridad) }}</span></td>
                        <td><span class="badge text-bg-{{ $ticket->estado_color }}">{{ ucfirst(str_replace('_', ' ', $ticket->estado)) }}</span></td>
                        <td>{{ $ticket->solicitante->name }}</td>
                        <td>{{ $ticket->tecnico?->name ?? 'Sin asignar' }}</td>
                        <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4">No hay tickets.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $tickets->links() }}
    </div>
@endsection