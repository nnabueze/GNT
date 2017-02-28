<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    //
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
    public function head()
    {
        return $this->belongsTo('App\Revenuehead');
    }
}
