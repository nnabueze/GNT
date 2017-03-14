<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ebillcollection extends Model
{
    protected $fillable = ['collection_key', 'Tin', 'collection_type','igr_id','mda_id','subhead_id','name','phone','mda','subhead','amount',
    'start_date','end_date','SessionID','SourceBankCode','DestinationBankCode','tax'];
}
