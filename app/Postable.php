<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Postable extends Model
{
    //
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
