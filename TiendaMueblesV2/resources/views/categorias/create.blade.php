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
</style>

<div class="container mt-4">
    <h1>Crear Nueva Categoría</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categorias.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="3" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Categoría</button>
        <a href="{{ route('categorias.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
