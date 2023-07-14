<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePgtoBoletosTable extends Migration
{
    public function up()
    {
        Schema::create('pgto_boletos', function (Blueprint $table) {
            $table->id();
            $table->string('customer')->index();
            $table->unsignedBigInteger('payment');
            $table->string('billingType');
            $table->date('dueDate');
            $table->decimal('value', 10, 2);
            $table->string('description');
            $table->string('externalReference');
            $table->decimal('discountValue', 10, 2);
            $table->integer('dueDateLimitDays');
            $table->decimal('fineValue', 10, 2);
            $table->decimal('interestValue', 10, 2);
            $table->boolean('postalService');
            $table->timestamps();

            $table->foreign('customer')->references('customer')->on('clientes');
            $table->foreign('payment')->references('id')->on('pagamentos');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pgto_boletos');
    }
}

