<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Igr extends Model
{
    //

    protected $fillable = ['igr_key', 'state_name', "igr_code",'igr_abbre',"logo"];

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
