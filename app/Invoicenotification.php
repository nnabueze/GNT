<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoicenotification extends Model
{
    protected $fillable = ['invoice_key', 'igr_id', 'mda_id','subhead_id','name','phone','mda','subhead','amount','SessionID','SourceBankCode',
    'DestinationBankCode','SourceBankName','BillerName','BillerID'];
}
