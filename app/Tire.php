<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tire extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function getUser(){
        return $this->hasOne('App\User','id','created_by');
    }
}
