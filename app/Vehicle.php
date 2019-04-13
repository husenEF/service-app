<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    public $timestamps = true;

    public function tires()
    {
        return $this->hasMany('App\Tire', 'id_vehicle', 'id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'created_by');
    }
    public function userUpdate()
    {
        return $this->hasOne('App\User', 'id', 'update_by');
    }
}
