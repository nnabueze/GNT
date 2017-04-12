<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Percentage extends Model
{
    //
    protected $fillable = ['collection_id','subhead_id','mda_id','agency_amount','gov_amount'];
}
