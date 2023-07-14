<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePgtoCartoesTable extends Migration
{
    public function up()
    {
        Schema::create('pgto_cartoes', function (Blueprint $table) {
            $table->id();
            $table->string('customer')->index();
            $table->unsignedBigInteger('payment');
            $table->string('billingType');
            $table->date('dueDate');
            $table->decimal('value', 10, 2);
            $table->string('description');
            $table->string('externalReference');
            $table->string('creditCardHolderName');
            $table->string('creditCardNumber');
            $table->string('creditCardExpiryMonth');
            $table->string('creditCardExpiryYear');
            $table->string('creditCardCcv');
            $table->string('creditCardToken')->nullable();
            $table->string('creditCardHolderEmail');
            $table->string('creditCardHolderCpfCnpj');
            $table->string('creditCardHolderPostalCode');
            $table->string('creditCardHolderAddressNumber');
            $table->string('creditCardHolderAddressComplement')->nullable();
            $table->string('creditCardHolderPhone');
            $table->string('creditCardHolderMobilePhone');
            $table->timestamps();

            $table->foreign('customer')->references('customer')->on('clientes');
            $table->foreign('payment')->references('id')->on('pagamentos');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pgto_cartoes');
    }
}

