@extends('header')

@section('content')
<div class="container">
    <h2>Comidas disponibles según stock</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Comida</th>
                <th>Max Platos</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($disponiblesPorComida as $item)
                @php
                    $max = $item['max_platos'];
                    if ($max <= 4) {
                        $color = 'table-danger';  // rojo
                    } elseif ($max > 15) {
                        $color = 'table-primary'; // azul
                    } else {
                        $color = 'table-warning'; // amarillo/naranja
                    }
                @endphp
                <tr class="{{ $color }}">
                    <td>{{ $item['comida'] }}</td>
                    <td>{{ $max }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="/" class="btn btn-secondary">Volver</a>
</div>
@endsection
