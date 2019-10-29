<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalle_ventas';

    protected $fillable = [
        'idventa','idarticulo','cantidad','precio','descuento',
        'por_entregar','entregadas','pendientes','completado'
    ];

    public $timestamps = false;
}
