<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    //


    //relationship between invoice and mda
    public function mda()
    {
        return $this->belongsTo('App\Mda');
    }

    ////relationship between invoice and revenue heads
    public function revenuehead()
    {
        return $this->belongsTo('App\Revenuehead');
    }

    //relationshp between invoice and subheads
    public function subhead()
    {
    	return $this->belongsTo('App\Subhead');
    }
}
