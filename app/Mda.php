<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mda extends Model
{
    //
    	//relationship between revenue head and Mda
        public function revenue()
        {
          return $this->hasMany('App\Revenuehead');
        }

        	//relationship between revenue head and Mda
            public function collections()
            {
              return $this->hasMany('App\Collection');
            }
}
