<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'idcliente','idusuario','tipo_comprobante','num_comprobante',
        'fecha_hora','impuesto','total','forma_pago','tiempo_entrega',
        'lugar_entrega','entregado','estado','moneda','tipo_cambio',
        'observacion','num_cheque','banco','tipo_facturacion','pagado',
        'entrega_parcial','file', 'observacionpriv','facturado',
        'factura_env'
    ];

    public function usuario(){
        return $this->belongsTo('App\User');
    }

    public function cliente(){
        return $this->belongsTo('App\Persona');
    }
}
