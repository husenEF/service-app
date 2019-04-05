<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tire extends Model
{

    protected $dates = ['deleted_at'];

    public function getUser(){
        return $this->hasOne('App\User','id','created_by');
    }
}
