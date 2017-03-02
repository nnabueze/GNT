<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mda extends Model
{
    
    protected $fillable = ['mda_key','mda_name','mda_category','igr_id'];

    //capitalising the name attribute
    public function setMdaNameAttribute($value)
    {
        $this->attributes['mda_name'] = ucwords($value);
    }

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

                //relationship between revenue head and Mda
    public function station()
    {
      return $this->hasMany('App\Station');
    }

    //relationship between igr and mda 
    public function igr()
    {
      return $this->belongsTo('App\Igr');
    }

    //has many through relationship between mda,revenue head and subhead
    public function subheads()
    {
        return $this->hasManyThrough('App\Subhead', 'App\Revenuehead');
    }
}
