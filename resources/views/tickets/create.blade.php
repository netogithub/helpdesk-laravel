@extends('layouts.app')

@section('title', 'Nuevo ticket')

@section('content')
    <h1 class="h3 mb-3">Nuevo ticket</h1>

    <form method="POST" action="{{ route('tickets.store') }}" class="card card-body shadow-sm">
        @include('tickets._form')

        <div class="mb-3">
            <label for="solicitante_id" class="form-label">Solicitante <small class="text-muted">(provisional hasta tener login)</small></label>
            <select id="solicitante_id" name="solicitante_id"
                    class="form-select @error('solicitante_id') is-invalid @enderror">
                <option value="">Selecciona...</option>
                @foreach ($usuarios as $usuario)
                    <option value="{{ $usuario->id }}" @selected(old('solicitante_id') == $usuario->id)>
                        {{ $usuario->name }}
                    </option>
                @endforeach
            </select>
            @error('solicitante_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div>
            <button type="submit" class="btn btn-primary">Crear ticket</button>
            <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
@endsection