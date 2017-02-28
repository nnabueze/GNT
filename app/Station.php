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

    //has many through relationship between collection,pos and station
    public function collections()
    {
        return $this->hasManyThrough('App\Collection', 'App\Postable');
    }
}
