<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = ['nombre', 'tipo_documento', 'num_documento','ciudad','domicilio','telefono','email','rfc'];

    public function proveedor(){

        return $this->hasOne('App\Proveedor');
    }

    public function user(){

        return $this->hasOne('App\User');
    }

}
