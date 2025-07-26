@extends('header')

@section('content')
<div class="container">
    <h2>Comidas Disponibles</h2>

    @if($comidas->isEmpty())
        <p>No hay comidas disponibles.</p>
    @else
        <ul>
            @foreach($comidas as $comida)
                <li>
                    <strong>{{ $comida->nombre }}</strong> - Precio por Kg: ${{ $comida->precio_venta_kg }}
                    <br>
                    Ingredientes:
                    <ul>
                        @foreach($comida->ingredientes as $ingrediente)
                            <li>{{ $ingrediente->nombre }} - Cantidad usada: {{ $ingrediente->pivot->cantidad }} {{ $ingrediente->unidad }}</li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
