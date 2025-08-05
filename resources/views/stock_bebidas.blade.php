@extends('header')

@section('content')
<div class="container mt-4">
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

    <a href="{{ url('/bebidas') }}" class="btn btn-primary mt-3">Agregar nueva bebida</a>
</div>
@endsection
