<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = ['name','email','bank_name','account_number','account_name','state','lga','phone_no','status'];
}
