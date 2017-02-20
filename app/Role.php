<?php

namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $fillable = [
        'name', 'display_name', 'description'
    ];

    //capitalizing the Name attribute
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    //making the display name capitalizing
    public function setDisplayNameAttribute($value)
    {
        $this->attributes['display_name'] = ucwords($value);
    }

    //capitalizing the description attribute
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = ucfirst($value);
    }
}
