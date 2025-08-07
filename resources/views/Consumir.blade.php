@extends('header')

@section('content')
<div class="container mt-4">
    <h2>Consumir Platos y Bebidas</h2>

    {{-- Mensajes de feedback --}}
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @elseif(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ url('/consumir') }}" method="POST">
        @csrf

        <!-- Sección Platos -->
        <h4 class="mt-4">Platos</h4>
        <div id="platos-container">
            <div class="row mb-3 item-row align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Seleccionar Plato</label>
                    <select name="platos[0][id]" class="form-select" required>
                        <option value="" disabled selected>-- Elige un plato --</option>
                        @foreach($comidas as $comida)
                            <option value="{{ $comida->id }}">{{ $comida->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Porciones</label>
                    <input type="number" name="platos[0][cantidad]" class="form-control" value="1" min="1" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Eliminar</button>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-secondary mb-4" onclick="addPlato()">+ Agregar Plato</button>

        <!-- Sección Bebidas -->
        <h4 class="mt-4">Bebidas</h4>
        <div id="bebidas-container">
            <div class="row mb-3 item-row align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Seleccionar Bebida</label>
                    <select name="bebidas[0][id]" class="form-select" required>
                        <option value="" disabled selected>-- Elige una bebida --</option>
                        @foreach($bebidas as $bebida)
                            <option value="{{ $bebida->id }}">{{ $bebida->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cantidad</label>
                    <input type="number" name="bebidas[0][cantidad]" class="form-control" value="1" min="1" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Eliminar</button>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-secondary mb-4" onclick="addBebida()">+ Agregar Bebida</button>

        <button type="submit" class="btn btn-primary w-100">Consumir</button>
        <a href="/" class="btn btn-secondary w-100 mt-2">Volver</a>
    </form>
</div>

<script>
    let platoIndex = 1;
    let bebidaIndex = 1;

    function addPlato() {
        const container = document.getElementById('platos-container');
        const template = container.querySelector('.item-row');
        const row = template.cloneNode(true);
        row.querySelector('select').name = `platos[${platoIndex}][id]`;
        const input = row.querySelector('input');
        input.name = `platos[${platoIndex}][cantidad]`;
        input.value = 1;
        container.appendChild(row);
        platoIndex++;
    }

    function addBebida() {
        const container = document.getElementById('bebidas-container');
        const template = container.querySelector('.item-row');
        const row = template.cloneNode(true);
        row.querySelector('select').name = `bebidas[${bebidaIndex}][id]`;
        const input = row.querySelector('input');
        input.name = `bebidas[${bebidaIndex}][cantidad]`;
        input.value = 1;
        container.appendChild(row);
        bebidaIndex++;
    }

    function removeRow(btn) {
        const row = btn.closest('.item-row');
        const container = row.parentNode;
        if (container.childElementCount > 1) {
            container.removeChild(row);
        }
    }
</script>
@endsection
