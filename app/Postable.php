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
}
