<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Postable extends Model
{
    protected $fillable = ['pos_imei', 'name', 'mda_id','station_id','pos_key','activation_code'];

	//reletionship with MDA
    public function mda()
    {
        return $this->belongsTo('App\Mda');
    }

    //relationship between pos and station
    public function station()
    {
    	return $this->belongsTo('App\Station');	
    }
}
