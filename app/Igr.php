<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Igr extends Model
{
    //

	//relationship between igr and Mda
    public function mdas()
    {
      return $this->hasMany('App\Mda');
    }

    	//relationship between igr and Mda
        public function collections()
        {
          return $this->hasMany('App\Collection');
        }
}
