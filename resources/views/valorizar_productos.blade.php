@extends('header')

@section('content')
<div class="container mt-4">
    <h2>Valorización de Productos (Comidas y Bebidas)</h2>

    @if(session('error'))
        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    @if(session('resumen'))
        @php($r = session('resumen'))
        <div class="card my-3">
            <div class="card-body">
                <h5 class="mb-3">Resumen de actualización</h5>

                <div class="row">
                    <div class="col-md-6">
                        <h6>Comidas</h6>
                        <p class="mb-1"><strong>Actualizados:</strong></p>
                        <ul class="mb-2">
                            @forelse($r['comidas']['actualizados'] as $i)
                                <li>{{ $i['nombre'] }}: ${{ number_format($i['de'],2) }} → ${{ number_format($i['a'],2) }}</li>
                            @empty
                                <li class="text-muted">—</li>
                            @endforelse
                        </ul>
                        <p class="mb-1"><strong>Omitidos:</strong></p>
                        <ul>
                            @forelse($r['comidas']['omitidos'] as $i)
                                <li>{{ $i['nombre'] }} — {{ $i['motivo'] }}</li>
                            @empty
                                <li class="text-muted">—</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="col-md-6">
                        <h6>Bebidas</h6>
                        <p class="mb-1"><strong>Actualizados:</strong></p>
                        <ul class="mb-2">
                            @forelse($r['bebidas']['actualizados'] as $i)
                                <li>{{ $i['nombre'] }}: ${{ number_format($i['de'],2) }} → ${{ number_format($i['a'],2) }}</li>
                            @empty
                                <li class="text-muted">—</li>
                            @endforelse
                        </ul>
                        <p class="mb-1"><strong>Omitidos:</strong></p>
                        <ul>
                            @forelse($r['bebidas']['omitidos'] as $i)
                                <li>{{ $i['nombre'] }} — {{ $i['motivo'] }}</li>
                            @empty
                                <li class="text-muted">—</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Filtro: Porcentaje + Base de cálculo (GET refresca la vista) --}}
    <form method="GET" action="{{ url('/productos/valorizar') }}" class="row g-3 align-items-end mt-3">
        <div class="col-auto">
            <label class="form-label">Porcentaje a aplicar</label>
            <div class="input-group">
                <input type="number" step="0.01" min="0" name="pct" class="form-control" value="{{ $pct }}">
                <span class="input-group-text">%</span>
            </div>
        </div>

        <div class="col-auto">
            <label class="form-label d-block">Base de cálculo</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio"
                       name="modo" value="costo" id="modo_costo"
                       {{ ($modo ?? 'costo') === 'costo' ? 'checked' : '' }}>
                <label class="form-check-label" for="modo_costo">Sobre costo</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio"
                       name="modo" value="precio" id="modo_precio"
                       {{ ($modo ?? 'costo') === 'precio' ? 'checked' : '' }}>
                <label class="form-check-label" for="modo_precio">Sobre precio actual</label>
            </div>
        </div>

        <div class="col-auto">
            <button class="btn btn-outline-primary">Actualizar vista</button>
        </div>
        <div class="col-auto">
            <a href="{{ url('/') }}" class="btn btn-outline-secondary">Volver</a>
        </div>
    </form>

    {{-- Aplicación (POST) --}}
    <form method="POST" action="{{ url('/productos/valorizar') }}" class="mt-4">
        @csrf
        <input type="hidden" name="pct"  value="{{ $pct }}">
        <input type="hidden" name="modo" value="{{ $modo ?? 'costo' }}">

        {{-- ======= COMIDAS ======= --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        Comidas
                        <small class="text-muted">
                            (Base: {{ ($modo ?? 'costo') === 'precio' ? 'precio actual' : 'costo de insumos' }})
                        </small>
                    </h4>
                    <div class="form-check">
                        <input type="checkbox" id="checkAllComidas" class="form-check-input">
                        <label for="checkAllComidas" class="form-check-label">Seleccionar todo</label>
                    </div>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-sm table-striped align-middle">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Comida</th>
                                <th>Costo Insumos/kg</th>
                                <th>Precio Actual/kg</th>
                                <th>Precio Sugerido ({{ $pct }}%)</th>
                                <th>Ganancia (vs costo)</th>
                                <th>Δ vs actual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($comidasCalculadas as $c)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="fila-check-comida" name="seleccion_comidas[]" value="{{ $c->id }}">
                                    </td>
                                    <td>{{ $c->nombre }}</td>
                                    <td>${{ number_format($c->calc['costo'], 2) }}</td>
                                    <td>${{ number_format($c->calc['precio_actual'], 2) }}</td>
                                    <td><strong>${{ number_format($c->calc['sugerido'], 2) }}</strong></td>
                                    <td class="@if($c->calc['ganancia']<=0) text-danger @else text-success @endif">
                                        ${{ number_format($c->calc['ganancia'], 2) }}
                                    </td>
                                    <td class="@if($c->calc['delta']<0) text-danger @elseif($c->calc['delta']>0) text-success @endif">
                                        ${{ number_format($c->calc['delta'], 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">No hay comidas cargadas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ======= BEBIDAS ======= --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Bebidas</h4>
                    <div class="form-check">
                        <input type="checkbox" id="checkAllBebidas" class="form-check-input">
                        <label for="checkAllBebidas" class="form-check-label">Seleccionar todo</label>
                    </div>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-sm table-striped align-middle">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Bebida</th>
                                <th>Costo (si existe)</th>
                                <th>Precio Actual</th>
                                <th>Precio Sugerido ({{ $pct }}%)</th>
                                <th>Ganancia (vs costo)</th>
                                <th>Δ vs actual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bebidasCalculadas as $b)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="fila-check-bebida" name="seleccion_bebidas[]" value="{{ $b->id }}">
                                    </td>
                                    <td>{{ $b->nombre }}</td>
                                    <td>
                                        @if(!is_null($b->calc['costo']))
                                            ${{ number_format($b->calc['costo'], 2) }}
                                        @else
                                            <span class="text-muted">N/D</span>
                                        @endif
                                    </td>
                                    <td>${{ number_format($b->calc['precio_actual'], 2) }}</td>
                                    <td><strong>${{ number_format($b->calc['sugerido'], 2) }}</strong></td>
                                    <td class="@if(!is_null($b->calc['ganancia']) && $b->calc['ganancia']<=0) text-danger @else text-success @endif">
                                        @if(!is_null($b->calc['ganancia']))
                                            ${{ number_format($b->calc['ganancia'], 2) }}
                                        @else
                                            <span class="text-muted">N/D</span>
                                        @endif
                                    </td>
                                    <td class="@if($b->calc['delta']<0) text-danger @elseif($b->calc['delta']>0) text-success @endif">
                                        ${{ number_format($b->calc['delta'], 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">No hay bebidas cargadas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Aplicar {{ $pct }}% a seleccionados</button>
            <a href="{{ url('/') }}" class="btn btn-secondary">Volver</a>
        </div>
    </form>
</div>

<script>
    // Seleccionar todo - Comidas
    document.getElementById('checkAllComidas')?.addEventListener('change', (e) => {
        document.querySelectorAll('.fila-check-comida').forEach(ch => ch.checked = e.target.checked);
    });
    // Seleccionar todo - Bebidas
    document.getElementById('checkAllBebidas')?.addEventListener('change', (e) => {
        document.querySelectorAll('.fila-check-bebida').forEach(ch => ch.checked = e.target.checked);
    });
</script>
@endsection
