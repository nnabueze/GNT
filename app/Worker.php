<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    protected $fillable = ['worker_key','worker_name','phone', 'user_limit', 'mda_id','worker_key','category','email','pin'];


	//relationship between user and mda's
    public function mda()
    {
        return $this->belongsTo('App\Mda');
    }

    //relationship between worker and subheads
    public function subheads()
    {
        return $this->belongsToMany('App\Subhead');
    }
}
