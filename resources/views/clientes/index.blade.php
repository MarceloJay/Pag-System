<!-- resources/views/clientes/index.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Lista de Clientes</h1>

    <a href="{{ route('clientes.create') }}" class="btn btn-primary mb-4">Novo Cliente</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th class="justify-content text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->id }}</td>
                    <td>{{ $cliente->name }}</td>
                    <td>{{ $cliente->email }}</td>
                    <td class="justify-content flex-end text-right">
                        <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-info">Ver</a>
                        <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-primary">Editar</a>
                        <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" style="display: inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination-container full-right">
        {{ $clientes->links('layouts.pagination') }}
    </div>
    <style>
        .pagination-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .pagination {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .pagination li {
            margin-right: 5px;
        }

        .pagination li a {
            display: inline-block;
            padding: 5px 10px;
            background-color: #f0f0f0;
            color: #333;
            text-decoration: none;
            border-radius: 3px;
        }

        .pagination li a:hover {
            background-color: #ccc;
        }
    </style>
@endsection
