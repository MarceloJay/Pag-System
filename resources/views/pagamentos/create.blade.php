<!-- resources/views/pagamentos/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Novo Pagamento</h1>
        @if (session('errorMessage'))
            <div class="alert alert-danger">
                {{ session('errorMessage') }}
            </div>
        @endif

        <form action="{{route('pagamentos.store')}}" method="POST">
            @csrf

            @if(auth()->check() && auth()->user()->roles()->first()->name == 'admin')
                <div class="form-group">
                    <label for="customer">Cliente:</label>
                    <select name="customer" class="form-control" required>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->name }}</option>
                        @endforeach
                    </select>
                </div>
            @else
                <div class="form-group">
                <label for="customer">Cliente:</label>
                <label name="customer">{{ $clienteLog->name }}</div>
                <input type="hidden" name="customer" value="{{ $clienteLog->id }}">
            @endif
            
                       

            <div class="form-group">
                <label for="dueDate">Data de Vencimento:</label>
                <input type="date" name="dueDate" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="value">Valor:</label>
                <input type="number" name="value" class="form-control" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="description">Descrição:</label>
                <input type="text" name="description" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="externalReference">Referência Externa:</label>
                <input type="text" name="externalReference" class="form-control" required>
            </div>

            <input type="hidden" id="billingType" name="billingType" value="CREDIT_CARD">
            <div class="form-group">
                <label for="paymentMethod">Método de Pagamento:</label>
                <select id="paymentMethod" name="paymentMethod" class="form-control" required>
                    <option value="CREDIT_CARD">Cartão de Crédito</option>
                    <option value="BOLETO">Boleto Bancário</option>
                    <option value="PIX">PIX</option>
                </select>
            </div>

            <!-- Campos adicionais para pagamento por boleto -->
            <div id="boletoFields" style="display: none;">
                <div class="form-group">
                    <label for="discountValue">Valor do Desconto:</label>
                    <input type="number" name="discountValue" class="form-control" step="0.01">
                </div>

                <div class="form-group">
                    <label for="dueDateLimitDays">Prazo de Vencimento Após o Desconto (em dias):</label>
                    <input type="number" name="dueDateLimitDays" class="form-control">
                </div>

                <div class="form-group">
                    <label for="fineValue">Valor da Multa:</label>
                    <input type="number" name="fineValue" class="form-control" step="0.01">
                </div>

                <div class="form-group">
                    <label for="interestValue">Valor dos Juros:</label>
                    <input type="number" name="interestValue" class="form-control" step="0.01">
                </div>
                <div class="form-group">
                <div class="form-check">
                    <input type="hidden" name="notificationDisabled" value="0">
                    <input type="checkbox" name="notificationDisabled" class="form-check-input" value="1">
                    <label class="form-check-label" for="notificationDisabled">Serviço Postal:</label>
                </div>
            </div>
            </div>

            <!-- Campos adicionais para pagamento por cartão de crédito -->
            <div id="cartaoFields" style="display: none;">
                <div class="form-group">
                    <label for="creditCardHolderName">Nome do Titular do Cartão:</label>
                    <input type="text" name="creditCardHolderName" class="form-control">
                </div>

                <div class="form-group">
                    <label for="creditCardNumber">Número do Cartão de Crédito:</label>
                    <input type="text" name="creditCardNumber" class="form-control">
                </div>

                <div class="form-group">
                    <label for="creditCardExpiryMonth">Mês de Expiração do Cartão:</label>
                    <input type="text" name="creditCardExpiryMonth" class="form-control">
                </div>

                <div class="form-group">
                    <label for="creditCardExpiryYear">Ano de Expiração do Cartão:</label>
                    <input type="text" name="creditCardExpiryYear" class="form-control">
                </div>

                <div class="form-group">
                    <label for="creditCardCcv">CCV do Cartão:</label>
                    <input type="text" name="creditCardCcv" class="form-control">
                </div>

                <div class="form-group">
                    <label for="creditCardHolderEmail">Email do Titular do Cartão:</label>
                    <input type="email" name="creditCardHolderEmail" class="form-control">
                </div>

                <div class="form-group">
                    <label for="creditCardHolderCpfCnpj">CPF/CNPJ do Titular do Cartão:</label>
                    <input type="text" name="creditCardHolderCpfCnpj" class="form-control">
                </div>

                <div class="form-group">
                    <label for="creditCardHolderPostalCode">CEP do Titular do Cartão:</label>
                    <input type="text" name="creditCardHolderPostalCode" class="form-control">
                </div>

                <div class="form-group">
                    <label for="creditCardHolderAddressNumber">Número do Endereço do Titular do Cartão:</label>
                    <input type="text" name="creditCardHolderAddressNumber" class="form-control">
                </div>

                <div class="form-group">
                    <label for="creditCardHolderAddressComplement">Complemento do Endereço do Titular do Cartão:</label>
                    <input type="text" name="creditCardHolderAddressComplement" class="form-control">
                </div>

                <div class="form-group">
                    <label for="creditCardHolderPhone">Telefone do Titular do Cartão:</label>
                    <input type="text" name="creditCardHolderPhone" class="form-control">
                </div>

                <div class="form-group">
                    <label for="creditCardHolderMobilePhone">Telefone Celular do Titular do Cartão:</label>
                    <input type="text" name="creditCardHolderMobilePhone" class="form-control">
                </div>
            </div>

            

            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function setPaymentMethod(value){
                console.log('setPaymentMethod', value);
                var boletoFields = document.getElementById('boletoFields');
                var cartaoFields = document.getElementById('cartaoFields');
                var billingTypeSelect = document.getElementById('billingType');
                switch (value) {
                    case 'CREDIT_CARD':
                        console.log('setPaymentMethod', value);
                        billingTypeSelect.value = 'CREDIT_CARD';
                        boletoFields.style.display = 'none';
                        cartaoFields.style.display = 'block';
                        break;
                
                    case 'BOLETO':
                        console.log('setPaymentMethod', value);
                        billingTypeSelect.value = 'BOLETO';
                        boletoFields.style.display = 'block';
                        cartaoFields.style.display = 'none';
                        break;
                    case 'PIX':
                        console.log('setPaymentMethod', value);
                        billingTypeSelect.value = 'PIX';
                        boletoFields.style.display = 'none';
                        cartaoFields.style.display = 'none';
                        break;
                }
            }

            var billingTypeSelect = document.getElementById('billingType');
            setPaymentMethod(billingTypeSelect.value);

            var paymentMethodSelect = document.getElementById('paymentMethod');
            paymentMethodSelect.addEventListener('change', function() {
                setPaymentMethod(paymentMethodSelect.value);
            });
        });
    </script>
@endsection

