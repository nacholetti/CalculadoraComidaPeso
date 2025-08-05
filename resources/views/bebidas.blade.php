@extends('header')

@section('content')
<form method="POST" action="{{ url('/bebidas/store') }}">
    @csrf
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="precio_venta" class="form-label">Precio de Venta</label>
        <input type="number" step="0.01" name="precio_venta" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="volumen_litros" class="form-label">Volumen (litros)</label>
        <input type="number" step="0.01" name="volumen_litros" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success">Guardar Bebida</button>
</form>

@section('content')
<div class="container">
    <h2>Stock de Bebidas</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($bebidas->count())
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio de Venta</th>
                    <th>Volumen (Litros)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bebidas as $bebida)
                    <tr>
                        <td>{{ $bebida->nombre }}</td>
                        <td>${{ number_format($bebida->precio_venta, 2) }}</td>
                        <td>{{ $bebida->volumen_litros }} L</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No hay bebidas registradas.</p>
    @endif
</div>
@endsection

@endsection
