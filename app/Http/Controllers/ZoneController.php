<?php

namespace App\Http\Controllers;

use App\Http\Resources\Zone\ZoneCollection;
use App\Models\Course;
use App\Models\Zone;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    use ApiResponse;

    public function getZoneList()
    {
        $zones = Zone::paginate(2);
        //chi can co {{dât-link}} no tu phan trang luon
        $result = new ZoneCollection($zones);
        return $this->successResponse($result, 'success');
    }

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
        return $this->successResponse($course,'success');
    }
}
