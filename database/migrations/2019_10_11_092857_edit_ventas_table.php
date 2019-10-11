<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ventas', function(Blueprint $table){

            $table->string('forma_pago',50)->nullable()->after('total');
            $table->string('tiempo_entrega',50)->nullable()->after('forma_pago');
            $table->string('lugar_entrega',50)->nullable()->after('tiempo_entrega');
            $table->boolean('entregado')->default(0)->after('lugar_entrega');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn('forma_pago');
            $table->dropColumn('tiempo_entrega');
            $table->dropColumn('lugar_entrega');
            $table->dropColumn('entregado');
        });
    }
}
