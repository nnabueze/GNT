<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tin extends Model
{
    //
    protected $fillable = ['name', 'email', "address",'phone','igr_id','temporary_tin',"tin_key","nationality","identification","bussiness_type","bussiness_name","bussiness_address","bussiness_no","reg_bus_name","Commencement_date"];
}
