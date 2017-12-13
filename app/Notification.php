<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'SessionID', 
        'PayerPhoneNumber', 
        'PayerName',
        'ReferenceCode',
        'amount',
        'TransactionDate',
        'paymentType',
    ];
}
