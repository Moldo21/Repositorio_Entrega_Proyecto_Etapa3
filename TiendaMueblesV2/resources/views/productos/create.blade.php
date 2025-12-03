@extends('cabecera')

@section('contenido')
@php
    $tema = request()->cookie('tema', 'claro');
@endphp

<style>
    body { background-color: {{ $tema == 'oscuro' ? '#121212' : '#fff' }}; color: {{ $tema == 'oscuro' ? '#e0e0e0' : '#000' }}; }
    .form-control, .form-select { @if($tema == 'oscuro') background-color: #2c2c2c; color: #e0e0e0; border-color: #444; @endif }
    label, .form-label { @if($tema == 'oscuro') color: #e0e0e0; @endif }
    .alert { @if($tema == 'oscuro') background-color: #2a2a2a; color: #e0e0e0; border-color: #555; @endif }
    .text-muted { @if($tema == 'oscuro') color: #aaa !important; @endif }
</style>

    <h2 class="mb-4">Crear nuevo producto</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Errores:</strong><br>
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('productos.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Categoría *</label>
            <select name="categoria_id" class="form-select" required>
                <option value="">Seleccione una categoría</option>
                @foreach ($categorias as $c)
                    <option value="{{ $c->id }}" {{ old('categoria_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Nombre *</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" maxlength="80" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción *</label>
            <textarea name="descripcion" class="form-control" rows="3" maxlength="255" required>{{ old('descripcion') }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Precio (€) *</label>
                <input type="number" name="precio" step="0.01" min="0" class="form-control"
                    value="{{ old('precio') }}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Stock *</label>
                <input type="number" name="stock" min="0" class="form-control"
                    value="{{ old('stock', 0) }}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Color principal *</label>
                <input type="text" name="color_principal" class="form-control"
                    value="{{ old('color_principal') }}" maxlength="50" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Materiales *</label>
            <input type="text" name="materiales" class="form-control"
                value="{{ old('materiales') }}" maxlength="255" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Dimensiones *</label>
            <input type="text" name="dimensiones" class="form-control"
                value="{{ old('dimensiones') }}" maxlength="255"
                placeholder="Ej: 200cm x 100cm x 80cm" required>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="destacado" value="1"
                {{ old('destacado') ? 'checked' : '' }}>
            <label class="form-check-label"><i class="bi bi-star-fill"></i> Producto destacado</label>
        </div>

        <div class="mb-3">
            <label class="form-label">Imagen principal *</label>
            <input type="file" name="imagen" class="form-control"
                accept="image/jpeg,image/png,image/jpg" required>
            <small class="text-muted">Formatos: JPG, JPEG, PNG. Máximo 2MB</small>
        </div>

        <button class="btn btn-success">Crear producto</button>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection
