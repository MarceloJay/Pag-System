<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagamentosTable extends Migration
{
    public function up()
    {
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->string('customer')->index();
            $table->string('billingType');
            $table->date('dueDate');
            $table->decimal('value', 10, 2);
            $table->string('description');
            $table->string('externalReference');
            $table->timestamps();

            $table->foreign('customer')->references('customer')->on('clientes');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagamentos');
    }
}
