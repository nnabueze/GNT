<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    //


    //relationship between invoice and remittance
    public function remittance()
    {
        return $this->hasOne('App\Remittance');
    }
}
