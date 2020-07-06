<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    public function groups(){
        return $this->belongsToMany('App\Models\Group','exercise_groups','exercise_id','group_id');
    }
}
