<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'mobilePhone',
        'cpfCnpj',
        'postalCode',
        'address',
        'addressNumber',
        'complement',
        'province',
        'externalReference',
        'notificationDisabled',
        'additionalEmails',
        'municipalInscription',
        'stateInscription',
        'observations',
        'customer'
    ];

    protected $casts = [
        'notificationDisabled' => 'boolean',
    ];

    public function getFormattedPhone()
    {
        $phone = $this->attributes['phone'];
        // Implemente a lógica de formatação do número de telefone aqui
        // Por exemplo: formatar como (XX) XXXX-XXXX

        return $phone;
    }

    // Adicione outros métodos relevantes ao modelo aqui

}

