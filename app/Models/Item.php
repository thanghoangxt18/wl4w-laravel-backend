<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public function groups(){
        return $this->belongsToMany('App\Models\Group','groups_items','item_id','group_id')->withPivot('id');
    }
}
