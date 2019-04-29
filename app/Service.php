<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public $timestamps = true;

    public function tire()
    {
        return $this->hasOne('App\Tire', 'id', 'tire_id');
    }

    public function getUser()
    {
        return $this->hasOne('App\User', 'id', 'user');
    }

    public function vehicle()
    {
        return $this->hasOne('App\Vehicle', 'id', 'kendaraan');
    }
}
