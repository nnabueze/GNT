<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Percentage extends Model
{
    //
    protected $fillable = ['collection_id','subhead_id','mda_id','agency_amount','gov_amount','amount','collected_at'];

    //relationship between collection and subheads
    public function subhead()
    {
      return $this->belongsTo('App\Subhead');
    }
}
