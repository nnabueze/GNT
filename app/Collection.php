<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $appends = ['Station'];

    protected $fillable =[
        "collection_key",
          "start_date",
          "end_date",
          "amount",
          "payer_id",
          "mda_id",
          "revenuehead_id",
          "worker_id",
          "subhead_id",
          "collection_type",
          "email",
          "phone",
          "name",
          "postable_id",
          "tax"
    ];

    //relationship between worker and collection
    public function worker()
    {
      return $this->belongsTo('App\Worker');
    }

    //relationship between heads
    public function revenuehead()
    {
      return $this->belongsTo('App\Revenuehead');
    }

    //relationship between collection and subheads
    public function subhead()
    {
      return $this->belongsTo('App\Subhead');
    }

    //relationship between collection and mda
    public function mda()
    {
      return $this->belongsTo('App\Mda');
    }

    //reationship between collection and pos
    public function postable()
    {
      return $this->belongsTo('App\Postable');
    }

    public function getStationAttribute() {
        return $this->postable->station;
    }

    //relationship with percentage table
    public function percentage()
    {
        return $this->hasOne('App\Percentage','collection_id', 'id');
    }
}
