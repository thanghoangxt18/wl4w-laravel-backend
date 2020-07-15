<?php

namespace App\Http\Controllers;

use App\Http\Resources\DiscoverCourse\DiscoverCourseResource;
use App\Http\Resources\Group\ShortGroupResource;
use App\Models\Course;
use App\Models\Group;
use App\Models\GroupItem;
use App\Models\Item;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    use ApiResponse;

    public function getListDiscoverCourse()
    {
        $courses = Course::where('zone_id', 0)->paginate(5);
        return $this->successResponse(DiscoverCourseResource::collection($courses), 'success');
    }

    public function searchDiscover(Request $request)
    {
        $this->validate($request, [
            'keyword' => 'required',
        ]);
        $keyword = $request->input('keyword');
        $limit = $request->input('limit', 5);
        $discoverCourseIds = Course::where('zone_id', 0)->pluck('id')->toArray();
        $itemIds = Item::whereIn('course_id', $discoverCourseIds)->pluck('id')->toArray();
        $groupIds = GroupItem::whereIn('item_id', $itemIds)->pluck('group_id')->toArray();

        $courses = Course::query()
            ->where('zone_id', 0)
            ->where('name', 'LIKE', '%' . $keyword . "%")
            ->get();

        $result = [];

        if (count($courses) > 0) {
            foreach ($courses as $course) {
                $course->items;
                foreach ($course->items as $item) {
                    foreach ($item->groups as $group) {
                        $group->exercise;
                        $groupIdArray = $this->listGroupIdInResult($result);
                        if (!in_array($group->id, $groupIdArray)) $result[] = $group;
                    }
                }
            }
        } else {
            $result = Group::query()
                ->where('name', 'LIKE', '%' . $keyword . "%")
                ->get();
        }
        return $this->successResponse(ShortGroupResource::collection($result), 'success');
    }

    public function listGroupIdInResult($result)
    {
        $groupIdArray = [];
        foreach ($result as $item)
            $groupIdArray[] = $item->id;
        return $groupIdArray;
    }
}
