<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{

    protected $dates = ['deleted_at'];

    public function tires(){
        return $this->hasMany('App\Tire','id_vehicle','id');
    }
}
