@extends('layouts.app')

@section('title', 'Editar ticket #' . $ticket->id)

@section('content')
    <h1 class="h3 mb-3">Editar ticket #{{ $ticket->id }}</h1>

    <form method="POST" action="{{ route('tickets.update', $ticket) }}" class="card card-body shadow-sm">
        @method('PUT')
        @include('tickets._form')

        <div>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
@endsection