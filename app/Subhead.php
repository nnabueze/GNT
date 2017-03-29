<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subhead extends Model
{
    protected $fillable = ['subhead_key','revenuehead_id', 'subhead_code', 'mda_id','subhead_name','amount','taxiable','gov','agency'];

    
	//each subheads belong to revenue head
    public function revenuehead()
    {
      return $this->belongsTo('App\Revenuehead');
    }
}
