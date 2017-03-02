<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subhead extends Model
{
    //
	//each subheads belong to revenue head
    public function revenuehead()
    {
      return $this->belongsTo('App\Revenuehead');
    }
}
