<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remittance extends Model
{
    //
    protected $fillable =[
        "remittance_key",
          "worker_id",
          "mda_id",
          "amount",
          "remittance_status"
    ];

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

	//relationship between remittance and collection
	public function collections()
	{
	  return $this->hasMany('App\Collection');
	}
}
