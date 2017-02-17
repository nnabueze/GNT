<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remittance extends Model
{
    //

	//relationship between invoice and remittance
    public function invoice()
    {
        return $this->belongsTo('App\Invoice');
    }
}
