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

            $table->integer('num_cheque')->nullable()->after('tipo_cambio');
            $table->string('banco',50)->nullable()->after('num_cheque');
            $table->string('tipo_facturacion',50)->nullable()->after('banco');
            $table->boolean('pagado')->default(0)->after('tipo_facturacion');
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
            $table->dropColumn('num_cheque');
            $table->dropColumn('banco');
            $table->dropColumn('tipo_facturacion');
            $table->dropColumn('pagado');
        });
    }
}
