<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    //
	//relationship between user and mda's
    public function mda()
    {
        return $this->belongsTo('App\Mda');
    }
}
