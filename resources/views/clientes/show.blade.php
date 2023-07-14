<!-- resources/views/clientes/show.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Detalhes do Cliente</h1>

    <p><strong>ID:</strong> {{ $cliente->id }}</p>
    <p><strong>Nome:</strong> {{ $cliente->name }}</p>
    <p><strong>Email:</strong> {{ $cliente->email }}</p>
    <!-- Exiba os campos restantes conforme necessário -->

    <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-primary">Editar</a>
    <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" style="display: inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza?')">Excluir</button>
    </form>
@endsection
