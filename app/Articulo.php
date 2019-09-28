<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    //
    protected $fillable = [
        'idcategoria', 'codigo', 'sku','nombre','terminado','largo','ancho','metros_cuadrados','espesor','ubicacion','stock',
        'descripcion', 'observacion','origen','fecha_llegada','file','condicion'
    ];

    public function categoria(){
        return $this->belongsTo('App\Categoria');
    }

}
