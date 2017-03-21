<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remittancenotification extends Model
{
    protected $fillable = ['remittance_key', 'igr_id', 'mda_id','refcode','name','phone','mda','amount','SessionID','SourceBankCode','DestinationBankCode'];
}
