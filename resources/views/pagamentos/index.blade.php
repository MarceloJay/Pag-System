<!-- resources/views/pagamentos/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Lista de Pagamentos</h1>
        
        <a href="{{ route('pagamentos.create') }}" class="btn btn-primary mb-4">Novo Pagamento</a>
        @if (session('errorMessage'))
            <div class="alert alert-danger">
                {{ session('errorMessage') }}
            </div>
        @endif

        @if ($pagamentos !== null && count($pagamentos) > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Valor</th>
                        <th>Data de Vencimento</th>
                        <th>Método</th>
                        <th class="justify-content text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pagamentos as $pagamento)
                        <tr>
                            <td>{{ $pagamento->id }}</td>
                            <td>{{ $pagamento->cliente->name }}</td>
                            <td>{{ $pagamento->value }}</td>
                            <td>{{ $pagamento->dueDate }}</td>
                            <td>{{ str_replace('_', ' ', $pagamento->billingType) }}</td>
                            <td class="justify-content flex-end text-right">
                                <a href="{{ route('pagamentos.show', $pagamento) }}" class="btn btn-info">Ver</a>
                                <a href="{{ route('pagamentos.destroy', $pagamento) }}" class="btn btn-primary">Editar</a>
                                <form action="{{ route('pagamentos.destroy', $pagamento) }}" method="POST" style="display: inline">
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
                {{ $pagamentos->links('layouts.pagination') }}
            </div>
        @else
            <p>Nenhum pagamento encontrado.</p>
        @endif
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
