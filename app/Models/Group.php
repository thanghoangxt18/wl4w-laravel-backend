<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $connection = 'mysql';
    protected $table = 'groups';

    public function exercise()
    {
        //  return $this->belongsToMany('App\Models\Exercise','exercise_groups','group_id','exercise_id');
        return $this->belongsToMany('App\Models\Exercise', 'exercise_groups')->withPivot('id');
    }

}
