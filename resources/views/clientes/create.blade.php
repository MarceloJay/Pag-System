<!-- resources/views/clientes/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Novo Cliente</h1>

        <form action="{{ route('clientes.store') }}" method="POST">
            @csrf
            @if (session('errorMessage'))
                <div class="alert alert-danger">
                    {{ session('errorMessage') }}
                </div>
            @endif
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputName">Nome</label>
                    <input type="text" name="name" class="form-control" placeholder="Nome Completo" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="email">Email:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <!-- senha somente para cliente -->
                <div class="form-group col-md-6">
                    <label for="password">Senha:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="password_confirmation">Confirmação de Senha:</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="phone">Telefone:</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="mobilePhone">Celular:</label>
                    <input type="text" name="mobilePhone" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="cpfCnpj">CPF/CNPJ:</label>
                    <input type="text" name="cpfCnpj" class="form-control" required>
                </div>

                <div class="form-group col-md-6">
                    <label for="postalCode">CEP:</label>
                    <input type="text" name="postalCode" class="form-control" required>
                </div>
            
                <div class="form-group col-md-6">
                    <label for="address">Endereço:</label>
                    <input type="text" name="address" class="form-control" required>
                </div>

                <div class="form-group col-md-6">
                    <label for="addressNumber">Número:</label>
                    <input type="text" name="addressNumber" class="form-control" required>
                </div>

                <div class="form-group col-md-6">
                    <label for="complement">Complemento:</label>
                    <input type="text" name="complement" class="form-control">
                </div>

                <div class="form-group col-md-6">
                    <label for="province">Província:</label>
                    <input type="text" name="province" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="externalReference">Referência Externa:</label>
                <input type="text" name="externalReference" class="form-control" required>
            </div>
            
            <div class="form-group">
                <div class="form-check">
                    <input type="hidden" name="notificationDisabled" value="0">
                    <input type="checkbox" name="notificationDisabled" class="form-check-input" value="1">
                    <label class="form-check-label" for="notificationDisabled">Desativar Notificações</label>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="additionalEmails">Emails Adicionais:</label>
                    <input type="text" name="additionalEmails" class="form-control">
                </div>

                <div class="form-group col-md-4">
                    <label for="municipalInscription">Inscrição Municipal:</label>
                    <input type="text" name="municipalInscription" class="form-control" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="stateInscription">Inscrição Estadual:</label>
                    <input type="text" name="stateInscription" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="observations">Observações:</label>
                <textarea name="observations" class="form-control" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
@endsection


