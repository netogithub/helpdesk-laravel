@csrf

<div class="mb-3">
    <label for="titulo" class="form-label">Título</label>
    <input type="text" id="titulo" name="titulo"
           class="form-control @error('titulo') is-invalid @enderror"
           value="{{ old('titulo', $ticket->titulo ?? '') }}">
    @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="descripcion" class="form-label">Descripción</label>
    <textarea id="descripcion" name="descripcion" rows="5"
              class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $ticket->descripcion ?? '') }}</textarea>
    @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="categoria_id" class="form-label">Categoría</label>
        <select id="categoria_id" name="categoria_id"
                class="form-select @error('categoria_id') is-invalid @enderror">
            <option value="">Selecciona...</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}"
                    @selected(old('categoria_id', $ticket->categoria_id ?? '') == $categoria->id)>
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select>
        @error('categoria_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="prioridad" class="form-label">Prioridad</label>
        <select id="prioridad" name="prioridad"
                class="form-select @error('prioridad') is-invalid @enderror">
            <option value="">Selecciona...</option>
            @foreach (\App\Models\Ticket::PRIORIDADES as $prioridad)
                <option value="{{ $prioridad }}"
                    @selected(old('prioridad', $ticket->prioridad ?? '') === $prioridad)>
                    {{ ucfirst($prioridad) }}
                </option>
            @endforeach
        </select>
        @error('prioridad') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>