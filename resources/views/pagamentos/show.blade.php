@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Detalhes do Pagamento</h1>

        <p><strong>ID:</strong> {{ $pagamento->id }}</p>
        <p><strong>Cliente:</strong> {{ $pagamento->cliente->name }}</p>
        <p><strong>Valor:</strong> {{ $pagamento->value }}</p>
        <p><strong>Data:</strong> {{ $pagamento->dueDate }}</p>

        @switch($pagamento->billingType)
            @case('BOLETO')
                <h3>Detalhes do Pagamento por Boleto</h3>
                <p><strong>Valor do Desconto:</strong> {{ $pgtoBoleto->discountValue }}</p>
                <p><strong>Prazo de Vencimento:</strong> {{ $pgtoBoleto->dueDateLimitDays }}</p>
                <p><strong>Valor da Multa:</strong> {{ $pgtoBoleto->fineValue }}</p>
                <p><strong>Valor dos Juros:</strong> {{ $pgtoBoleto->interestValue }}</p>
                <p><strong>Serviço Postal:</strong> {{ $pgtoBoleto->postalService ? 'Sim' : 'Não' }}</p>
                <h3>Linha Digitável</h3>
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" id="payload" class="form-control" value="{{ $response->identificationField }}" disabled>
                        <button class="btn btn-primary" onclick="copyToClipboard()">Copiar</button>
                    </div>
                </div>
                @break
            @case('CREDIT_CARD')
                <h3>Detalhes do Pagamento por Cartão de Crédito</h3>
                <p><strong>Nome do Titular do Cartão:</strong> {{ $pgtoCartao->creditCardHolderName }}</p>
                <p><strong>Número do Cartão:</strong> {{ $pgtoCartao->creditCardNumber }}</p>
                <p><strong>Mês de Vencimento:</strong> {{ $pgtoCartao->creditCardExpiryMonth }}</p>
                <p><strong>Ano de Vencimento:</strong> {{ $pgtoCartao->creditCardExpiryYear }}</p>
                <p><strong>CCV:</strong> {{ $pgtoCartao->creditCardCcv }}</p>
                <p><strong>E-mail do Titular:</strong> {{ $pgtoCartao->creditCardHolderEmail }}</p>
                <p><strong>CPF/CNPJ do Titular:</strong> {{ $pgtoCartao->creditCardHolderCpfCnpj }}</p>
                <p><strong>CEP do Titular:</strong> {{ $pgtoCartao->creditCardHolderPostalCode }}</p>
                <p><strong>Número do Endereço do Titular:</strong> {{ $pgtoCartao->creditCardHolderAddressNumber }}</p>
                <p><strong>Complemento do Endereço do Titular:</strong> {{ $pgtoCartao->creditCardHolderAddressComplement }}</p>
                <p><strong>Telefone do Titular:</strong> {{ $pgtoCartao->creditCardHolderPhone }}</p>
                <p><strong>Celular do Titular:</strong> {{ $pgtoCartao->creditCardHolderMobilePhone }}</p>
                <form action="{{ route('pagamentos.executar', $pagamento) }}" method="POST" style="display: inline">
                    @csrf
                    <button type="submit" class="btn btn-success">Confirmar pagamento</button>
                </form>
                @break
            @case('PIX')
                <script>
                    function copyToClipboard() {
                        var payloadInput = document.getElementById('payload');
                        var valueToCopy = payloadInput.value;

                        navigator.clipboard.writeText(valueToCopy)
                            .then(function() {
                                alert('Conteúdo copiado para a área de transferência');
                            })
                            .catch(function(error) {
                                console.error('Erro ao copiar para a área de transferência:', error);
                            });
                    }
                </script>
                <h3>Detalhes do Pagamento por PIX</h3>
                <img src="data:image/png;base64,{{ $response->encodedImage }}" alt="QR Code de Pagamento PIX">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" id="payload" class="form-control" value="{{ $response->payload }}" disabled>
                        <button class="btn btn-primary" onclick="copyToClipboard()">Copiar</button>
                    </div>
                </div>
                <p><strong>Data de expiração:</strong> {{ $response->expirationDate }}</p>
                @break
            @default
                <p>Nenhum detalhe adicional disponível.</p>
        @endswitch

        <a href="{{ route('pagamentos.edit', $pagamento) }}" class="btn btn-primary">Editar</a>
        <form action="{{ route('pagamentos.destroy', $pagamento) }}" method="POST" style="display: inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza?')">Excluir</button>
        </form>  
    </div>
@endsection

