@extends('header')

@section('content')
<div class="container">
    <h2>Cargar nueva comida</h2>

    <form action="/comidas/store" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del plato</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="precio_venta_kg" class="form-label">Precio de venta por Kg</label>
            <input type="number" name="precio_venta_kg" class="form-control" step="0.01" required>
        </div>

        <h4>Ingredientes</h4>

        <div id="ingredientes">
            <div class="row mb-2 ingrediente-row">
                <div class="col">
                    <select name="ingredientes[0][id]" class="form-select">
                        @foreach ($ingredientes as $ingrediente)
                            <option value="{{ $ingrediente->id }}">{{ $ingrediente->nombre }} ({{ $ingrediente->unidad }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <input type="number" step="0.01" name="ingredientes[0][cantidad]" class="form-control" placeholder="Cantidad usada por Kg">
                </div>
                <div class="col-1">
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarIngrediente(this)">x</button>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-secondary mb-3" onclick="agregarIngrediente()">+ Agregar ingrediente</button>
        <a href="/ingredientes/create" class="btn btn-info mb-3">Crear nuevo ingrediente</a>
        <a href="/stock" class="btn btn-info mb-3">Ver stock</a>
        
        <button type="submit" class="btn btn-primary">Guardar comida</button>
    </form>
</div>

<script>
let contador = 1;

function agregarIngrediente() {
    const container = document.getElementById('ingredientes');

    const nuevaFila = `
    <div class="row mb-2 ingrediente-row">
        <div class="col">
            <select name="ingredientes[${contador}][id]" class="form-select">
                @foreach ($ingredientes as $ingrediente)
                    <option value="{{ $ingrediente->id }}">{{ $ingrediente->nombre }} ({{ $ingrediente->unidad }})</option>
                @endforeach
            </select>
        </div>
        <div class="col">
            <input type="number" step="0.01" name="ingredientes[${contador}][cantidad]" class="form-control" placeholder="Cantidad usada por Kg">
        </div>
        <div class="col-1">
            <button type="button" class="btn btn-danger btn-sm" onclick="eliminarIngrediente(this)">x</button>
        </div>
    </div>`;

    container.insertAdjacentHTML('beforeend', nuevaFila);
    contador++;
}

function eliminarIngrediente(btn) {
    btn.closest('.ingrediente-row').remove();
}

</script>
@endsection
