<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('customer')->index();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('mobilePhone');
            $table->string('cpfCnpj');
            $table->string('postalCode');
            $table->string('address');
            $table->string('addressNumber');
            $table->string('complement')->nullable();
            $table->string('province');
            $table->string('externalReference');
            $table->boolean('notificationDisabled')->default(false);
            $table->string('additionalEmails')->nullable();
            $table->string('municipalInscription');
            $table->string('stateInscription');
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}

