<?php

namespace App\Http\Controllers;

use App\Http\Resources\Course\ShortCourseResource;
use App\Http\Resources\Group\GroupCollection;
use App\Http\Resources\Group\GroupResource;
use App\Http\Resources\Item\ItemResource;
use App\Http\Resources\Zone\ShortZoneCollection;
use App\Http\Resources\Zone\ShortZoneResource;
use App\Http\Resources\Zone\ZoneCollection;
use App\Http\Resources\Zone\ZoneResource;
use App\Models\Course;
use App\Models\Group;
use App\Models\Item;
use App\Models\Zone;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ZoneController extends Controller
{
    use ApiResponse;
    const STORAGE_PATH_ZONE = '/images/zone';


    //api1:get-list-zone
    public function getZoneListAndExercise(Request $request)
    {
        $per_page = $request->per_page ? (int)$request->per_page : 5;
        $zones = Zone::paginate($per_page);
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
            'banner' => 'required|file'
        ]);
        $zone = new Zone();
        $zone->name = $request->name;
        $banner = $request->banner;
        $bannerPath = Storage::put(self::STORAGE_PATH_ZONE, $banner);
        $ext = $banner->getClientOriginalExtension();
        $fileName = pathinfo($banner->getClientOriginalName(), PATHINFO_FILENAME);
        $mainFilename = $fileName . Str::random(6) . date('Y-m-d-h-i-s');
        $bannerPathNew = self::STORAGE_PATH_ZONE . '/' . $mainFilename . "." . $ext;
        Storage::move($bannerPath, $bannerPathNew);
        $bannerPathNew = 'storage' . $bannerPathNew;
        $zone-> banner = $bannerPathNew;
        try {
            $zone->save();
            return $this->successResponse(
                ['message' => 'Successfully'], 'Success'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => 'Create failed']
            );
        }
    }

    public function getExerciseOfZone(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|int'
        ]);
        $zone = Zone::findOrFail($request->id);
        $zone->exercise;
        $result = new ZoneResource($zone);
        return $this->successResponse($result, 'Success', 200);
    }

    public function getZoneByKeyword(Request $request)
    {
        $per_page = $request->per_page ? (int)$request->per_page : 5;
        $keyword = $request->input('keyword');
        if ($keyword !== '') {
            $zones = Zone::query()
                ->where('name', 'LIKE', '%' . $keyword . "%")
                ->paginate($per_page);
        } else {
            $zones = Zone::paginate($per_page);
        }
        if (count($zones) > 0) {
            foreach ($zones as $zone) $zone->exercise;
        }
        $result = new ZoneCollection($zones);
        return $this->successResponse($result, 'Success', 200);
    }

    public function updateZone(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|string',
            'name' => 'required|string'
        ]);

        $zone = Zone::find($request->id);
        $zone->name = $request->name;
        if ($request->banner) {
            $banner = $request->banner;
            $bannerPath = Storage::put(self::STORAGE_PATH_ZONE, $banner);
            $ext = $banner->getClientOriginalExtension();
            $fileName = pathinfo($banner->getClientOriginalName(), PATHINFO_FILENAME);
            $mainFilename = $fileName . Str::random(6) . date('Y-m-d-h-i-s');
            $bannerPathNew = self::STORAGE_PATH_ZONE . '/' . $mainFilename . "." . $ext;
            Storage::move($bannerPath, $bannerPathNew);
            $bannerPathNew = 'storage' . $bannerPathNew;
        }
        $zone->banner = $bannerPathNew ? $bannerPathNew : $zone->banner;
        try {
            $zone->save();
            return $this->successResponse(
                ['message' => 'Successfully'], 'Success'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => $e->getMessage()]
            );
        }
    }

    public function addNewExerciseToZone(Request $request)
    {
        $this->validate($request, [
            'exercise_id' => 'required|int',
            'zone_id' => 'required|int',
        ]);
        try {
            DB::table('exercise_zones')->insert(
                ['exercise_id' => $request->exercise_id, 'zone_id' => $request->zone_id]);
            return $this->successResponse([], 'Success');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => 'Create failed']
            );
        }
    }

    public function deleteExerciseOfZone(Request $request)
    {
        $this->validate($request, [
            'zone_id' => 'required|int',
            'exercise_id' => 'required|int'
        ]);
        try {
            DB::table('exercise_zones')
                ->where('exercise_id', '=', $request->exercise_id)
                ->where('zone_id', '=', $request->zone_id)
                ->delete();
            return $this->successResponse([], 'Success');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, []
            );
        }
    }

    public function deleteZone(Request $request)
    {
        $this->validate($request, [
            'zone_id' => 'integer|required'
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

    public function getZoneList(Request $request)
    {
        $per_page = $request->per_page ? (int)$request->per_page : 5;
        $zones = Zone::paginate($per_page);
        $result = new ShortZoneCollection($zones);
        return $this->successResponse($result, 'success');
    }
}
