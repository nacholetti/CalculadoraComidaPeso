@extends('header')

@section('content')
<div class="container my-4">
  <h2 class="mb-3">Resumen de tu compra @if(($order['order_id'] ?? 0)>0) #{{ $order['order_id'] }} @endif</h2>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>Producto</th>
              <th class="text-center">Cant.</th>
              <th class="text-end">Precio</th>
              <th class="text-end">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            @foreach($order['items'] as $it)
              <tr>
                <td>{{ $it['nombre'] }}</td>
                <td class="text-center">{{ $it['cantidad'] }}</td>
                <td class="text-end">${{ number_format($it['precio_unitario'],2,',','.') }}</td>
                <td class="text-end">${{ number_format($it['subtotal'],2,',','.') }}</td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th colspan="3" class="text-end">Total</th>
              <th class="text-end">${{ number_format($order['total'],2,',','.') }}</th>
            </tr>
            @if(isset($order['total_ganancia']))
            <tr>
              <th colspan="3" class="text-end text-success">Ganancia estimada</th>
              <th class="text-end text-success">${{ number_format($order['total_ganancia'],2,',','.') }}</th>
            </tr>
            @endif
          </tfoot>
        </table>
      </div>

      <div class="d-flex gap-2 mt-3">
        <a href="{{ url('/tienda') }}" class="btn btn-outline-secondary">Seguir comprando</a>
        <a href="javascript:history.back()" class="btn btn-outline-primary">Volver</a>
      </div>
    </div>
  </div>
</div>
@endsection
