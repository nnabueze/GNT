<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remittance extends Model
{
    //

	//relationship between mda and remittance
	public function mda()
	{
		return $this->belongsTo('App\Mda');
	}

    	//relationship between worker and remittance
	public function worker()
	{
		return $this->belongsTo('App\Worker');
	}
}
