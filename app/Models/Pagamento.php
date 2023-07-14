<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer',
        'billingType',
        'dueDate',
        'value',
        'description',
        'externalReference',
        'asaas_id',
    ];

    // Relação com o Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'customer');
    }
}

