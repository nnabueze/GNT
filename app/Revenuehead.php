<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Revenuehead extends Model
{
    //
    protected $fillable = ['revenueheads_key', 'revenue_code', 'mda_id','revenue_name','amount','taxiable'];

    public function subheads()
    {
      return $this->hasMany('App\Subhead');
    }
}
