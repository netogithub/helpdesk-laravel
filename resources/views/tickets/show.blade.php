@extends('layouts.app')

@section('title', 'Ticket #' . $ticket->id)

@section('content')
    <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-outline-secondary mb-3">&larr; Volver</a>
    <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-sm btn-outline-primary mb-3">Editar</a>

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

    @if ($ticket->estado !== 'cerrado')
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <form method="POST" action="{{ route('tickets.tecnico', $ticket) }}" class="card card-body shadow-sm h-100">
                    @csrf
                    <h2 class="h6">Asignar técnico</h2>
                    <select name="tecnico_id" class="form-select mb-2 @error('tecnico_id') is-invalid @enderror">
                        <option value="">Selecciona...</option>
                        @foreach ($tecnicos as $tecnico)
                            <option value="{{ $tecnico->id }}" @selected(old('tecnico_id', $ticket->tecnico_id) == $tecnico->id)>
                                {{ $tecnico->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('tecnico_id') <div class="invalid-feedback mb-2">{{ $message }}</div> @enderror
                    <div><button type="submit" class="btn btn-outline-primary btn-sm">Guardar técnico</button></div>
                </form>
            </div>

            <div class="col-md-6">
                <form method="POST" action="{{ route('tickets.estado', $ticket) }}" class="card card-body shadow-sm h-100">
                    @csrf
                    <h2 class="h6">Cambiar estado</h2>
                    <select name="estado" class="form-select mb-2 @error('estado') is-invalid @enderror">
                        <option value="">Nuevo estado...</option>
                        @foreach ($ticket->transicionesPermitidas() as $estado)
                            <option value="{{ $estado }}" @selected(old('estado') === $estado)>
                                {{ ucfirst(str_replace('_', ' ', $estado)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('estado') <div class="invalid-feedback mb-2">{{ $message }}</div> @enderror

                    <select name="actor_id" class="form-select mb-2 @error('actor_id') is-invalid @enderror">
                        <option value="">Actuar como... (provisional)</option>
                        @foreach ($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" @selected(old('actor_id') == $usuario->id)>
                                {{ $usuario->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('actor_id') <div class="invalid-feedback mb-2">{{ $message }}</div> @enderror
                    <div><button type="submit" class="btn btn-outline-primary btn-sm">Cambiar estado</button></div>
                </form>
            </div>
        </div>
    @endif

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
            <form method="POST" action="{{ route('tickets.comentarios.store', $ticket) }}" class="card card-body shadow-sm mt-3">
                @csrf
                <h3 class="h6">Agregar comentario</h3>
            
                <textarea name="contenido" rows="3"
                          class="form-control mb-2 @error('contenido') is-invalid @enderror"
                          placeholder="Escribe un comentario...">{{ old('contenido') }}</textarea>
                @error('contenido') <div class="invalid-feedback mb-2">{{ $message }}</div> @enderror
            
                <select name="user_id" class="form-select mb-2 @error('user_id') is-invalid @enderror">
                    <option value="">Comentar como... (provisional)</option>
                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" @selected(old('user_id') == $usuario->id)>
                            {{ $usuario->name }}
                        </option>
                    @endforeach
                </select>
                @error('user_id') <div class="invalid-feedback mb-2">{{ $message }}</div> @enderror
            
                <div><button type="submit" class="btn btn-primary btn-sm">Comentar</button></div>
            </form>
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