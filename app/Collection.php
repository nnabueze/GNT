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
          "revenuehead_id",
          "subhead_id",
          "collection_type",
          "email",
          "phone",
          "name",
    ];
}
