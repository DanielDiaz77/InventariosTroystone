<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    //
    protected $fillable = [
        'idcategoria', 'codigo', 'sku','nombre','terminado','largo','alto','metros_cuadrados','espesor','precio_venta','ubicacion',
        'contenedor','stock','descripcion', 'observacion','origen','fecha_llegada','file','condicion', 'comprometido','idusuario'
    ];

    public function categoria(){
        return $this->belongsTo('App\Categoria');
    }

    public function links(){
        return $this->morphMany(Link::class,'linkable');
    }
}
