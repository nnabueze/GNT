<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    //

    protected $fillable =[
        "name",
          "email",
          "phone",
          "payer_id",
          "mda_id",
          "revenuehead_id",
          "subhead_id",
          "amount",
          "user_id",
          "start_date",
           "end_date",
           "invoice_key"
    ];
    //relationship between invoice and mda
    public function mda()
    {
        return $this->belongsTo('App\Mda');
    }

    ////relationship between invoice and revenue heads
    public function revenuehead()
    {
        return $this->belongsTo('App\Revenuehead');
    }

    //relationshp between invoice and subheads
    public function subhead()
    {
    	return $this->belongsTo('App\Subhead');
    }
}
