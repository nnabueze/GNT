<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{

    protected $fillable = ['station_name','mda_id','station_key'];

    //capitalising the name attribute
    public function setStationNameAttribute($value)
    {
        $this->attributes['station_name'] = ucwords($value);
    }
}
