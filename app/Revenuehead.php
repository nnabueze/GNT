<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Revenuehead extends Model
{
    //

    public function subheads()
    {
      return $this->hasMany('App\Subhead');
    }
}
