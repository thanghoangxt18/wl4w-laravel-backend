<?php

namespace App\Http\Controllers;

use App\Http\Resources\Course\ShortCourseResource;
use App\Http\Resources\Group\GroupResource;
use App\Http\Resources\Item\ItemResource;
use App\Http\Resources\Zone\ZoneCollection;
use App\Models\Course;
use App\Models\Group;
use App\Models\Item;
use App\Models\Zone;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    use ApiResponse;

    //api1:get-list-zone
    public function getZoneList()
    {
        $zones = Zone::paginate(3);
        //chi can co {{data-link}} no tu phan trang luon
        $result = new ZoneCollection($zones);
        return $this->successResponse($result, 'success');
    }

    //api2: get-course-by-zone
    public function getCourseByZone(Request $request)
    {
        $zoneId = $request->input('zone_id');
        $course = Course::where('zone_id','=',$zoneId)->first();
        $course->items;
        foreach ($course->items as $item) {
            foreach ($item->groups as $group) {
                $group->exercise;
            }
        }
        $result = new ShortCourseResource($course);
        return $this->successResponse($result,'success');
    }

    //api3: get-course-item
    public function getCourseItem(Request $request){
        $itemId = $request->input('item_id');
        $item = Item::findOrFail($itemId);
        $item->groups;
        foreach ($item->groups as $group){
            $group->exercise;
        }
        $result = new ItemResource($item);
        return $this->successResponse($result,'success');
}

    //api4: get-group-exercise
    public function getDetailGroupWorkout(Request $request){
        $groupId = $request->input('group_id');
        $group = Group::findOrFail($groupId);
        $group->exercise;
        $result = new GroupResource($group);
        return $this->successResponse($result,'success');
    }
}
