@extends('cabecera')

@section('contenido')
@php
    $tema = request()->cookie('tema', 'claro');
@endphp

<style>
    body { background-color: {{ $tema == 'oscuro' ? '#121212' : '#fff' }}; color: {{ $tema == 'oscuro' ? '#e0e0e0' : '#000' }}; }
    .card { @if($tema == 'oscuro') background-color: #1f1f1f; color: #e0e0e0; border-color: #333; @endif }
    .form-control, .form-select { @if($tema == 'oscuro') background-color: #2c2c2c; color: #e0e0e0; border-color: #444; @endif }
    label, .form-label { @if($tema == 'oscuro') color: #e0e0e0; @endif }
    .alert { @if($tema == 'oscuro') background-color: #2a2a2a; color: #e0e0e0; border-color: #555; @endif }
</style>

<div class="container mt-4">
    <h2 class="text-center mb-4">Editar categoría</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Corrige los siguientes errores:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categorias.update', $categoria->id) }}" method="POST" class="card p-4 shadow">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la categoría</label>
            <input type="text" id="nombre" name="nombre" class="form-control"
                value="{{ old('nombre', $categoria->nombre) }}" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea id="descripcion" name="descripcion" class="form-control" rows="4" required>{{ old('descripcion', $categoria->descripcion) }}</textarea>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
                Cancelar
            </a>

            <button type="submit" class="btn btn-primary">
                Guardar cambios
            </button>
        </div>
    </form>
</div>
@endsection
