<!-- resources/views/clientes/edit.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Editar Cliente</h1>

    <form action="{{ route('clientes.update', $cliente) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nome:</label>
            <input type="text" name="name" class="form-control" value="{{ $cliente->name }}" required>
        </div>
        <!-- Adicione os campos restantes conforme necessário -->

        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
@endsection
