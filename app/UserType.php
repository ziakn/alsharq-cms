<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    protected $guarded = [];

    public function admin()
    {
       return $this->belongsTo('App\User');
    } 
    
}
