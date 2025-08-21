@extends('header')

@section('content')
{{-- Si tu layout ya incluye el meta csrf, podés borrar esta línea --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container my-4">
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Elegí tus platos y bebidas</h2>
        <a href="{{ url('/productos/valorizar') }}" class="btn btn-warning">Valorizar productos</a>
      </div>

      {{-- Carrusel de Comidas --}}
      <h4 class="mb-2">Platos</h4>
      <div class="carousel-hscroll mb-4">
        @forelse($comidas as $c)
          <div class="item-card card shadow-sm">
            <div class="card-body">
              <h5 class="card-title mb-1">{{ $c->nombre }}</h5>
              <div class="small text-muted mb-2">Por Kg</div>
              <div class="mb-2">
                <div><strong>Precio:</strong> ${{ number_format($c->calc['precio'],2,',','.') }}</div>
                <div class="text-muted small"><strong>Costo:</strong> ${{ number_format($c->calc['costo'],2,',','.') }}</div>
                <div class="text-success small"><strong>Ganancia:</strong> ${{ number_format($c->calc['ganancia'],2,',','.') }}</div>
              </div>
              <div class="d-flex gap-2">
<button
  type="button"
  class="btn btn-sm btn-primary add-to-cart"
  data-id="comida-{{ $c->id }}"
  data-tipo="comida"
  data-producto-id="{{ $c->id }}"
  data-nombre="{{ e($c->nombre) }}"
  data-precio="{{ $c->calc['precio'] }}"
  data-costo="{{ $c->calc['costo'] }}"
>
  Agregar
</button>


              </div>
            </div>
          </div>
        @empty
          <div class="text-muted">No hay platos cargados.</div>
        @endforelse
      </div>

      {{-- Carrusel de Bebidas --}}
      <h4 class="mb-2">Bebidas</h4>
      <div class="carousel-hscroll mb-4">
        @forelse($bebidas as $b)
          <div class="item-card card shadow-sm">
            <div class="card-body">
              <h5 class="card-title mb-1">{{ $b->nombre }}</h5>
              <div class="mb-2">
                <div><strong>Precio:</strong> ${{ number_format($b->calc['precio'],2,',','.') }}</div>
                <div class="text-muted small"><strong>Costo:</strong> ${{ number_format($b->calc['costo'],2,',','.') }}</div>
                <div class="text-success small"><strong>Ganancia:</strong> ${{ number_format($b->calc['ganancia'],2,',','.') }}</div>
              </div>
              <div class="d-flex gap-2">
<button
  type="button"
  class="btn btn-sm btn-primary add-to-cart"
  data-id="bebida-{{ $b->id }}"
  data-tipo="bebida"
  data-producto-id="{{ $b->id }}"
  data-nombre="{{ e($b->nombre) }}"
  data-precio="{{ $b->calc['precio'] }}"
  data-costo="{{ $b->calc['costo'] }}"
>
  Agregar
</button>



              </div>
            </div>
          </div>
        @empty
          <div class="text-muted">No hay bebidas cargadas.</div>
        @endforelse
      </div>
    </div>
  
    {{-- Panel lateral: Totales y carrito --}}
    <div class="col-lg-4">
      <div class="card shadow-sm mb-3">
        <div class="card-body">
          <h5 class="mb-3">Resumen</h5>
          <div class="d-flex justify-content-between mb-1">
            <span>Ítems en carrito</span>
            <strong id="carrito-items">0</strong>
          </div>
          <div class="d-flex justify-content-between mb-1">
            <span>Total carrito</span>
            <strong>$ <span id="carrito-total">0.00</span></strong>
          </div>
          <div class="d-flex justify-content-between text-success">
            <span>Ganancia estimada del carrito</span>
            <strong>$ <span id="carrito-ganancia">0.00</span></strong>
          </div>
          <hr>
          <div class="d-flex justify-content-between">
            <span class="text-muted">Ganancia total del catálogo (1 unidad c/u)</span>
            <strong class="text-muted">$ {{ number_format($gananciaTotalCatalogo,2,',','.') }}</strong>
          </div>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="mb-3">Carrito</h5>
          <div id="carrito-lista" class="vstack gap-2 small">
            <div class="text-muted" id="carrito-vacio">No agregaste productos.</div>
          </div>
          <div class="mt-3 d-grid">
            <button class="btn btn-success" onclick="finalizarCompra()" disabled id="btn-finalizar">
              Finalizar compra
            </button>
          </div>
        </div>
      </div>

    </div>
  </div>
              <a href="{{ url('/') }}" class="btn btn-secondary">Volver</a>

</div>

<style>
  .carousel-hscroll {
    display: flex;
    gap: 12px;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    padding-bottom: 4px;
  }
  .item-card {
    min-width: 240px;
    scroll-snap-align: start;
    border-radius: 12px;
  }
  .qty-btn {
    width: 28px; height: 28px; line-height: 1;
    padding: 0; text-align: center;
  }


</style>

<script>
  window.checkoutUrl        = "{{ route('tienda.checkout') }}";     // POST
  window.checkoutResumenUrl = "{{ route('tienda.checkout.resumen') }}";   // GET → genera /checkout/resumen
  window.csrfToken          = "{{ csrf_token() }}";
</script>
<script src="{{ asset('js/tienda.js') }}" defer></script>


@endsection
