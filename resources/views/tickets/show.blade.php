@extends('layouts.app')

@section('title', 'Ticket #' . $ticket->id)

@section('content')
    <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-outline-secondary mb-3">&larr; Volver</a>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <h1 class="h4">#{{ $ticket->id }} · {{ $ticket->titulo }}</h1>
                <div>
                    <span class="badge text-bg-{{ $ticket->prioridad_color }}">{{ ucfirst($ticket->prioridad) }}</span>
                    <span class="badge text-bg-{{ $ticket->estado_color }}">{{ ucfirst(str_replace('_', ' ', $ticket->estado)) }}</span>
                </div>
            </div>
            <p class="mt-3">{{ $ticket->descripcion }}</p>
            <hr>
            <dl class="row mb-0">
                <dt class="col-sm-3">Categoría</dt>
                <dd class="col-sm-9">{{ $ticket->categoria->nombre }}</dd>
                <dt class="col-sm-3">Solicitante</dt>
                <dd class="col-sm-9">{{ $ticket->solicitante->name }}</dd>
                <dt class="col-sm-3">Técnico</dt>
                <dd class="col-sm-9">{{ $ticket->tecnico?->name ?? 'Sin asignar' }}</dd>
                <dt class="col-sm-3">Creado</dt>
                <dd class="col-sm-9">{{ $ticket->created_at->format('d/m/Y H:i') }}</dd>
                @if ($ticket->cerrado_en)
                    <dt class="col-sm-3">Cerrado</dt>
                    <dd class="col-sm-9">{{ $ticket->cerrado_en->format('d/m/Y H:i') }}</dd>
                @endif
            </dl>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <h2 class="h5">Comentarios ({{ $ticket->comentarios->count() }})</h2>
            @forelse ($ticket->comentarios as $comentario)
                <div class="card mb-2">
                    <div class="card-body py-2">
                        <strong>{{ $comentario->autor->name }}</strong>
                        <small class="text-muted">· {{ $comentario->created_at->format('d/m/Y H:i') }}</small>
                        <p class="mb-0">{{ $comentario->contenido }}</p>
                    </div>
                </div>
            @empty
                <p class="text-muted">Sin comentarios.</p>
            @endforelse
        </div>

        <div class="col-lg-5">
            <h2 class="h5">Historial de estados</h2>
            <ul class="list-group">
                @foreach ($ticket->historial as $cambio)
                    <li class="list-group-item">
                        {{ $cambio->estado_anterior ? ucfirst(str_replace('_', ' ', $cambio->estado_anterior)) . ' → ' : '' }}<strong>{{ ucfirst(str_replace('_', ' ', $cambio->estado_nuevo)) }}</strong>
                        <br>
                        <small class="text-muted">{{ $cambio->autor->name }} · {{ $cambio->created_at->format('d/m/Y H:i') }}</small>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection