@extends('header')

@section('content')
<div class="container">

    <a href="/" class="btn btn-secondary mb-3">← Volver a crear plato</a>

    <h2>Agregar nuevo ingrediente</h2>

    <form action="/ingredientes/store" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del ingrediente</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="unidad" class="form-label">Unidad</label>
            <select name="unidad" class="form-select" required>
                <option value="kg">Kilogramo</option>
                <option value="unidad">Unidad</option>
                <option value="litro">Litro</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="costo_unitario" class="form-label">Costo por unidad</label>
            <input type="number" name="costo_unitario" class="form-control" step="0.01" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar ingrediente</button>
    </form>
</div>
@endsection
