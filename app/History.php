<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class History extends Model
{
    use SoftDeletes;
    public $timestamps = true;

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'created_by');
    }

    public function vehicle(){
        return $this->hasOne("App\Vehicle",'id','id_vehicle');
    }
}
