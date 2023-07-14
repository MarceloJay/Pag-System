<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PgtoBoleto extends Pagamento
{
    use HasFactory;

    protected $table = 'pgto_boletos'; // Nome da tabela para o model PgtoBoleto

    protected $fillable = [
        'customer',
        'payment',
        'billingType',
        'dueDate',
        'value',
        'description',
        'externalReference',
        'discountValue',
        'dueDateLimitDays',
        'fineValue',
        'interestValue',
        'postalService',
    ];

    // Relação com o Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'customer');
    }
}
