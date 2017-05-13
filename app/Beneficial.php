<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Beneficial extends Model
{
    //
    protected $fillable = ['igr_id','mda_id','beneficial_key','account_no','bank_code','bank_name','notification_no'];

    //relationship between beneficail and mda
    public function mda()
    {
      return $this->belongsTo('App\Mda');
    }

    //relationship between igr and beneficial
    public function igr()
    {
      return $this->belongsTo('App\Igr');
    }

    public function setBankNameAttribute($value)
    {
        $this->attributes['bank_name'] = ucwords($value);
    }
}
