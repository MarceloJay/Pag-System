<!-- resources/views/pagamentos/edit.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editar Pagamento</h1>
        <div class="text-right mb-4">
            <a href="{{ URL::previous() }}" class="btn btn-outline-secondary">Voltar</a>
        </div>
        <form action="{{ route('pagamentos.update', $pagamento) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="customer">Cliente:</label>
                <select name="customer" class="form-control" required>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id }}" @if ($cliente->id == $pagamento->cliente_id) selected @endif>{{ $cliente->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="dueDate">Data de Vencimento:</label>
                <input type="date" name="dueDate" class="form-control" value="{{ $pagamento->dueDate }}" required>
            </div>

            <div class="form-group">
                <label for="value">Valor:</label>
                <input type="number" name="value" class="form-control" value="{{ $pagamento->value }}" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="description">Descrição:</label>
                <input type="text" name="description" class="form-control" value="{{ $pagamento->description }}" required>
            </div>

            <div class="form-group">
                <label for="externalReference">Referência Externa:</label>
                <input type="text" name="externalReference" class="form-control" value="{{ $pagamento->externalReference }}" required>
            </div>

            <input type="hidden" id="billingType" name="billingType" value="{{ $pagamento->billingType }}">
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
                <h3>Detalhes do Pagamento por Boleto</h3>
                <p><strong>Valor do Desconto:</strong> <input type="text" name="discountValue" value="{{ $pgtoBoleto->discountValue ?? '' }}"></p>
                <p><strong>Prazo de Vencimento:</strong> <input type="text" name="dueDateLimitDays" value="{{ $pgtoBoleto->dueDateLimitDays ?? '' }}"></p>
                <p><strong>Valor da Multa:</strong> <input type="text" name="fineValue" value="{{ $pgtoBoleto->fineValue ?? '' }}"></p>
                <p><strong>Valor dos Juros:</strong> <input type="text" name="interestValue" value="{{ $pgtoBoleto->interestValue ?? '' }}"></p>
                <p><strong>Serviço Postal:</strong> 
                    <select name="postalService">
                        <option value="1" {{ $pgtoBoleto->postalService = true ?  'selected' : '' }}>Sim</option>
                        <option value="0" {{ $pgtoBoleto->postalService = false ? 'selected' : '' }}>Não</option>
                    </select>
                </p>
            </div>
            <!-- Campos adicionais para pagamento por cartão de crédito -->
            <div id="cartaoFields" style="display:none;">
                <h3>Detalhes do Pagamento por Cartão de Crédito</h3>
                <p><strong>Nome do Titular do Cartão:</strong> <input type="text" name="creditCardHolderName" value="{{ $pgtoCartao->creditCardHolderName }}"></p>
                <p><strong>Número do Cartão:</strong> <input type="text" name="creditCardNumber" value="{{ $pgtoCartao->creditCardNumber }}"></p>
                <p><strong>Mês de Vencimento:</strong> <input type="text" name="creditCardExpiryMonth" value="{{ $pgtoCartao->creditCardExpiryMonth }}"></p>
                <p><strong>Ano de Vencimento:</strong> <input type="text" name="creditCardExpiryYear" value="{{ $pgtoCartao->creditCardExpiryYear }}"></p>
                <p><strong>CCV:</strong> <input type="text" name="creditCardCcv" value="{{ $pgtoCartao->creditCardCcv }}"></p>
                <p><strong>E-mail do Titular:</strong> <input type="text" name="creditCardHolderEmail" value="{{ $pgtoCartao->creditCardHolderEmail }}"></p>
                <p><strong>CPF/CNPJ do Titular:</strong> <input type="text" name="creditCardHolderCpfCnpj" value="{{ $pgtoCartao->creditCardHolderCpfCnpj }}"></p>
                <p><strong>CEP do Titular:</strong> <input type="text" name="creditCardHolderPostalCode" value="{{ $pgtoCartao->creditCardHolderPostalCode }}"></p>
                <p><strong>Número do Endereço do Titular:</strong> <input type="text" name="creditCardHolderAddressNumber" value="{{ $pgtoCartao->creditCardHolderAddressNumber }}"></p>
                <p><strong>Complemento do Endereço do Titular:</strong> <input type="text" name="creditCardHolderAddressComplement" value="{{ $pgtoCartao->creditCardHolderAddressComplement }}"></p>
                <p><strong>Telefone do Titular:</strong> <input type="text" name="creditCardHolderPhone" value="{{ $pgtoCartao->creditCardHolderPhone }}"></p>
                <p><strong>Celular do Titular:</strong> <input type="text" name="creditCardHolderMobilePhone" value="{{ $pgtoCartao->creditCardHolderMobilePhone }}"></p>
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
            paymentMethodSelect.value = billingTypeSelect.value;
            paymentMethodSelect.addEventListener('change', function() {
                setPaymentMethod(paymentMethodSelect.value);
            });
        });
    </script>
@endsection
