<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function items(){
        return $this->hasMany('App\Models\Item','course_id','id');
    }
}
