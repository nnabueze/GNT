<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Uploadsweep extends Model
{
    //
    protected $fillable = ['agency', 'collected_amount', "agency_amount",'remitted_amount',"remitted_date","mda_id","payment_date"];
}
