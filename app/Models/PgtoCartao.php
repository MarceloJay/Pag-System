<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PgtoCartao extends Pagamento
{
    use HasFactory;

    protected $table = 'pgto_cartoes'; // Nome da tabela para o model PgtoCartao

    protected $fillable = [
        'customer',
        'payment',
        'billingType',
        'dueDate',
        'value',
        'description',
        'externalReference',
        'creditCardHolderName',
        'creditCardNumber',
        'creditCardExpiryMonth',
        'creditCardExpiryYear',
        'creditCardCcv',
        'creditCardToken',
        'creditCardHolderEmail',
        'creditCardHolderCpfCnpj',
        'creditCardHolderPostalCode',
        'creditCardHolderAddressNumber',
        'creditCardHolderAddressComplement',
        'creditCardHolderPhone',
        'creditCardHolderMobilePhone',
    ];

    // Relação com o Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'customer');
    }
}

