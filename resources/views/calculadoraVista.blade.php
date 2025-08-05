@extends('header')

@section('content')
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="mb-4">Cargar nueva comida</h2>

            <form action="/comidas/store" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del plato</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label for="precio_venta_kg" class="form-label">Precio de venta por Kg</label>
                    <input type="number" name="precio_venta_kg" class="form-control" step="0.01" required>
                </div>

                <h4 class="mb-3">Ingredientes</h4>

                <div id="ingredientes">
                    <div class="row mb-3 ingrediente-row align-items-end">
                        <div class="col-md-6">
                            <label class="form-label">Ingrediente</label>
                            <select name="ingredientes[0][id]" class="form-select">
                                @foreach ($ingredientes as $ingrediente)
                                    <option value="{{ $ingrediente->id }}">
                                        {{ $ingrediente->nombre }} ({{ $ingrediente->unidad }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cantidad usada por Kg</label>
                            <input type="number" step="0.01" name="ingredientes[0][cantidad]" class="form-control">
                        </div>
                        <div class="col-md-2 text-end">
                            <button type="button" class="btn btn-danger btn-sm mt-4" onclick="eliminarIngrediente(this)">Eliminar</button>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-secondary mb-4" onclick="agregarIngrediente()">+ Agregar ingrediente</button>

                <div class="d-flex flex-wrap gap-2 mb-4">
                    <a href="/ingredientes/create" class="btn btn-outline-info">Crear nuevo ingrediente</a>
                    <a href="/stock" class="btn btn-outline-info">Ver stock</a>
                    <a href="{{ url('/bebidas') }}" class="btn btn-outline-info">Agregar Bebida</a>
                    <a href="{{ url('/bebidas/stock') }}" class="btn btn-outline-info">Ver Stock de Bebidas</a>


                    <a href="/comidas/disponibles" class="btn btn-outline-success">Ver comidas disponibles</a>
                    <a href="/comidas/disponibles_con_stock" class="btn btn-outline-primary">Comidas con stock</a>
                </div>

                <button type="submit" class="btn btn-primary w-100">Guardar comida</button>
            </form>
        </div>
    </div>
</div>

<script>
    let contador = 1;

    function agregarIngrediente() {
        const container = document.getElementById('ingredientes');

        const nuevaFila = `
        <div class="row mb-3 ingrediente-row align-items-end">
            <div class="col-md-6">
                <label class="form-label">Ingrediente</label>
                <select name="ingredientes[${contador}][id]" class="form-select">
                    @foreach ($ingredientes as $ingrediente)
                        <option value="{{ $ingrediente->id }}">{{ $ingrediente->nombre }} ({{ $ingrediente->unidad }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Cantidad usada por Kg</label>
                <input type="number" step="0.01" name="ingredientes[${contador}][cantidad]" class="form-control">
            </div>
            <div class="col-md-2 text-end">
                <button type="button" class="btn btn-danger btn-sm mt-4" onclick="eliminarIngrediente(this)">Eliminar</button>
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
