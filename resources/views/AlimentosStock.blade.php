@extends('header')

@section('content')
<div class="container">
    <h2>Stock de Ingredientes</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="/stock/update" method="POST">
        @csrf
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Ingrediente</th>
                    <th>Unidad</th>
                    <th>Costo Unitario</th>
                    <th>Stock Actual (modificable)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ingredientes as $ingrediente)
                    <tr>
                        <td>{{ $ingrediente->nombre }}</td>
                        <td>{{ $ingrediente->unidad }}</td>
                        <td>${{ number_format($ingrediente->costo_unitario, 2) }}</td>
                        <td>
                            <input 
                                type="number" 
                                name="stock[{{ $ingrediente->id }}]" 
                                value="{{ $ingrediente->stock ?? 0 }}" 
                                step="0.01" 
                                min="0" 
                                class="form-control" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No hay ingredientes cargados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <button type="submit" class="btn btn-success">Actualizar Stock</button>
        <a href="/" class="btn btn-primary">Volver al inicio</a>
    </form>
</div>
@endsection
