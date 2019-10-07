<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('idcliente');
            $table->foreign('idcliente')->references('id')->on('personas');
            $table->unsignedBigInteger('idusuario');
            $table->foreign('idusuario')->references('id')->on('users');
            $table->string('tipo_comprobante',20);
            $table->string('num_comprobante',20);
            $table->dateTime('fecha_hora');
            $table->decimal('impuesto',4,2);
            $table->decimal('total',11,2);
            $table->string('estado',20);
            $table->string('moneda',20);
            $table->decimal('tipo_cambio',11,4)->nullable();
            $table->string('observacion',256)->nullable();
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
        Schema::dropIfExists('ventas');
    }
}
