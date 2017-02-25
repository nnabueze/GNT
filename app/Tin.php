<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tin extends Model
{
    //
    protected $fillable = ['name', 'email', "address",'phone','igr_id','temporary_tin',"tin_key"];
}
