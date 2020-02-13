<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $fillable = ['venta_id','total','fecha_hora'];

    public function depositable(){
        return $this->morphTo();
    }

    public function venta(){
        return $this->belongsTo(Venta::class);
    }
}
