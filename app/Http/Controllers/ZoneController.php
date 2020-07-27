<?php

namespace App\Http\Controllers;

use App\Http\Resources\Course\ShortCourseResource;
use App\Http\Resources\Group\GroupCollection;
use App\Http\Resources\Group\GroupResource;
use App\Http\Resources\Item\ItemResource;
use App\Http\Resources\Zone\ZoneCollection;
use App\Models\Course;
use App\Models\Group;
use App\Models\Item;
use App\Models\Zone;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        $this->validate($request, [
            'zone_id' => 'required'
        ]);
        $zoneId = $request->input('zone_id');
        $course = Course::where('zone_id', '=', $zoneId)->first();
        $course->items;
        foreach ($course->items as $item) {
            foreach ($item->groups as $group) {
                $group->exercise;
            }
        }
        $result = new ShortCourseResource($course);
        return $this->successResponse($result, 'success');
    }

    //api3: get-course-item
    public function getCourseItem(Request $request)
    {
        $this->validate($request, [
            'item_id' => 'required'
        ]);
        $itemId = $request->input('item_id');
        $item = Item::findOrFail($itemId);
        $item->groups;
        foreach ($item->groups as $group) {
            $group->exercise;
        }
        $result = new ItemResource($item);
        return $this->successResponse($result, 'success');
    }

    //api4: get-group-exercise
    public function getDetailGroupWorkout(Request $request)
    {
        $this->validate($request, [
            'group_id' => 'required'
        ]);
        $groupId = $request->input('group_id');
        $group = Group::findOrFail($groupId);
        $group->exercise;
        $result = new GroupResource($group);
        return $this->successResponse($result, 'success');
    }

    public function createNewZone(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'banner' => 'required|file',
            'listExerciseId' => 'required|array'
        ]);
        $zone = new Zone();
        $zone->name = $request->name;
        $bannerPath = Storage::put('images/zone', $request->banner);
        $zone->banner = $bannerPath;
        $zone->save();
        $listExerciseId = $request->listExerciseId;
        try {
            foreach ($listExerciseId as $exerciseId) {
                DB::table('exercise_zones')
                    ->insert(
                        ['exercise_id' => $exerciseId, 'zone_id' => $zone->id]
                    );
            };
            return $this->successResponse(
                ['message' => 'Successfully'], 'Success'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => 'Create failed']
            );
        }
    }

    public function getZoneByKeyword(Request $request)
    {
        $this->validate($request, [
            'keyword' => 'required|string'
        ]);

        $zoneListSearched = Zone::query()
            ->where('name', 'LIKE', '%' . $request->keyword . "%")
            ->paginate(10);


        $result = new ZoneCollection($zoneListSearched);
        return $this->successResponse($result, 'Success', 200);
    }

    public function updateZone(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|string',
            'name' => 'required|string',
            'banner' => 'required|file',
            'listExerciseId' => 'required|array'
        ]);

        $zone = Zone::find($request->id);
        $zone->name = $request->name;
        $bannerPath = Storage::put('images/zone', $request->banner);
        $zone->banner = $bannerPath;
        $zone->save();
        $listExerciseId = $request->listExerciseId;
        try {
            DB::table('exercise_zones')->where('zone_id', '=', $zone->id)->delete();
            foreach ($listExerciseId as $exerciseId) {
                DB::table('exercise_zones')
                    ->insert(
                        ['exercise_id' => $exerciseId, 'zone_id' => $zone->id]
                    );
            };
            return $this->successResponse(
                ['message' => 'Successfully'], 'Success'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => 'Create failed']
            );
        }
    }

    public function deleteZone(Request $request)
    {
        $this->validate($request, [
            'zone_id' => 'string|required'
        ]);
        try {
            DB::table('exercise_zones')->where('zone_id', '=', $request->zone_id);
            Zone::where('id', '=', $request->zone_id)->delete();
            return $this->successResponse(
                ['message' => 'Successfully'], 'Success'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => 'Create failed']
            );
        }
    }


}
