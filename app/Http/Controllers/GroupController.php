<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function getAllExerciseOfAGroupByGroupId(){
        $id = 1;
        //You may access the group's exercise using the exercise dynamic property.
        $ex = Group::find(1)->exercise;
        return $ex;
    }

    //Good
    public function getAllGroupAndItsExercise()
    {
        $groups = Group::all();
        foreach ($groups as $group) {
            $group->exercise;
        }
        return $groups;
    }
}
