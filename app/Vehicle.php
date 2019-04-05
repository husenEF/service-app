<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{

    protected $dates = ['deleted_at'];

    public function tires(){
        return $this->hasMany('App\Tire','id_vehicle','id');
    }

    public function user(){
        return $this->hasOne('App\User','id','created_by');
    }
    public function userUpdate(){
        return $this->hasOne('App\User','id','update_by');
    }
}
