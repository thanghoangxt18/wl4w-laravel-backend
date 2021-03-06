<?php

namespace App\Http\Controllers;

use App\Http\Resources\Course\CourseCollection;
use App\Http\Resources\Course\ShortCourseResource;
use App\Http\Resources\DiscoverCourse\DiscoverCourseCollection;
use App\Http\Resources\DiscoverCourse\DiscoverCourseResource;
use App\Http\Resources\Group\GroupCollection;
use App\Http\Resources\Group\ShortGroupResource;
use App\Models\Course;
use App\Models\Group;
use App\Models\GroupItem;
use App\Models\Item;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    use ApiResponse;
    const STORAGE_PATH_COURSE = '/images/course';

    public function getCourseList(Request $request)
    {
        $perPage = $request->per_page ? (int)$request->per_page : 10;
        $courses = Course::paginate($perPage);
        $result = new CourseCollection($courses);
        return $this->successResponse($result, 'success');
    }

    public function getListDiscoverCourse(Request $request)
    {
        $perPage = $request->per_page ? (int)$request->per_page : 10;
        $courses = Course::where('zone_id', 0)->paginate($perPage);
        $result = new DiscoverCourseCollection($courses);
        return $this->successResponse($result, 'success');
    }

    public function searchDiscover(Request $request)
    {
        $perPage = $request->per_page ? (int)$request->per_page : 10;
        $keyword = $request->input('keyword');
        if ($keyword !== '') {
            $courses = Course::query()
                ->where('zone_id', 0)
                ->where('name', 'LIKE', '%' . $keyword . "%")
                ->get();
        } else {
            $courses = Course::query()
                ->where('zone_id', 0)->get();
        }
        $result = [];
        if (count($courses) > 0) {
            foreach ($courses as $course) {
                foreach ($course->items as $item) {
                    foreach ($item->groups as $group) {
                        $groupIdArray = $this->listGroupIdInResult($result);
                        if (!in_array($group->id, $groupIdArray)) $result[] = $group;
                    }
                }
            }
            $page = $request->input('page') ? (int)$request->input('page') : 1;
            $perPage = $request->input('per_page') ? (int)$request->input('per_page') : 10;
            $total = count($result);
            $total_pages = ceil($total / $perPage);
            $page = max($page, 1);
            $page = min($page, $total_pages);
            $offset = ($page - 1) * $perPage < 0 ? 0 : ($page - 1) * $perPage;
            $result1 = array_slice($result, $offset, $perPage);
            $output = new \stdClass();
            $output->groups = ShortGroupResource::collection($result1);
            $output->pagination = new \stdClass();
            $output->pagination->total = $total;
            $output->pagination->count = count($result1);
            $output->pagination->per_page = $perPage;
            $output->pagination->current_page = $page;
            $output->pagination->total_pages = $total_pages;
            return $this->successResponse($output, 'success');
        } else {
            $result = Group::query()->where('name', 'LIKE', '%' . $keyword . "%")->paginate($perPage);
            $output = new GroupCollection($result);
            return $this->successResponse($output, 'success');
        }
    }

    public function listGroupIdInResult($result)
    {
        $groupIdArray = [];
        foreach ($result as $item) {
            $groupIdArray[] = $item->id;
        }
        return $groupIdArray;
    }

    public function createNewCourse(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'required|string',
            'layout_type' => 'required|string',
            'zone_id' => 'required|int',
            'banner' => 'required'
        ]);

        $course = new Course();
        $course->name = $request->name;
        $course->description = $request->description;
        $course->layout_type = $request->layout_type;
        $course->zone_id = $request->zone_id;
        $banner = $request->banner;
        $bannerPath = Storage::put(self::STORAGE_PATH_COURSE, $banner);
        $ext = $banner->getClientOriginalExtension();
        $fileName = pathinfo($banner->getClientOriginalName(), PATHINFO_FILENAME);
        $mainFilename = $fileName . Str::random(6) . date('Y-m-d-h-i-s');
        $bannerPathNew = self::STORAGE_PATH_COURSE . '/' . $mainFilename . "." . $ext;
        Storage::move($bannerPath, $bannerPathNew);
        $bannerPathNew = 'storage' . $bannerPathNew;
        $course->banner = $bannerPathNew;
        try {
            $course->save();
            return $this->successResponse([], 'success');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed', 402);
        }
    }

    public function getBasicOfCourse(Request $request)
    {
        $this->validate($request, ['course_id' => 'required|int']);
        $course = Course::findOrFail($request->course_id);
        return $this->successResponse($course, 'success', '200');
    }

    public function updateBasicCourse(Request $request)
    {
        $this->validate($request, [
            'course_id' => 'required|int',
            'name' => 'required|string',
            'description' => 'required|string',
            'layout_type' => 'required|string',
            'zone_id' => 'required|int'
        ]);

        $course = Course::findOrFail($request->course_id);
        $course->name = $request->name;
        $course->description = $request->description;
        $course->layout_type = $request->layout_type;
        $course->zone_id = $request->zone_id;
        if ($request->banner) {
            $banner = $request->banner;
            $bannerPath = Storage::put(self::STORAGE_PATH_COURSE, $banner);
            $ext = $banner->getClientOriginalExtension();
            $fileName = pathinfo($banner->getClientOriginalName(), PATHINFO_FILENAME);
            $mainFilename = $fileName . Str::random(6) . date('Y-m-d-h-i-s');
            $bannerPathNew = self::STORAGE_PATH_COURSE . '/' . $mainFilename . "." . $ext;
            Storage::move($bannerPath, $bannerPathNew);
            $bannerPathNew = 'storage' . $bannerPathNew;
            $course->banner = $bannerPathNew ? $bannerPathNew : $course->banner;
        }
        try {
            $course->save();
            return $this->successResponse([], 'success');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed', 402);
        }
    }

    public function getItemsByCourse(Request $request)
    {
        $this->validate($request, [
            'course_id' => 'required|int'
        ]);

        $course = Course::findOrFail($request->course_id);
        $result = new ShortCourseResource($course);
        return $this->successResponse($result, 'success', 200);
    }

    public function createNewItem(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'course_id' => 'required|int'
        ]);
        $item = new Item();
        $item->name = $request->name;
        $item->course_id = $request->course_id;
        try {
            $item->save();
            return $this->successResponse([], 'success');
        } catch (\Exception $e) {
            return $this->errorResponse('failed', 402);
        }
    }

    public function updateBasicItem(Request $request)
    {
        $this->validate($request, [
            'item_id' => 'required|int',
            'name' => 'required|string'
        ]);
        $item = Item::findOrFail($request->item_id);
        $item->name = $request->name;
        try {
            $item->save();
            return $this->successResponse([], 'success');
        } catch (\Exception $e) {
            return $this->errorResponse('failed', 402);
        }
    }

    public function addNewGroupToItem(Request $request)
    {
        $this->validate($request, [
            'item_id' => 'required|int',
            'group_id' => 'required|int',
            'order' => 'int'
        ]);
        $order = $request->input('order') ? (int)$request->input('order') : 1;
        try {
            DB::table('groups_items')
                ->insert(['item_id' => $request->item_id, 'group_id' => $request->group_id, 'order' => $order]);
            return $this->successResponse([], 'success');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 402);
        }
    }


    public function deleteGroupOfItem(Request $request)
    {
        $this->validate($request, [
            'group_item_id' => 'required|int'
        ]);
        try {
            DB::table('groups_items')
                ->where('id', '=', $request->group_item_id)->delete();
            return $this->successResponse([], 'success');
        } catch (Exception $e) {
            return $this->errorResponse('fail', 402);
        }
    }

    public function deleteItem(Request $request)
    {
        $this->validate($request, [
            'item_id' => 'required|int'
        ]);
        $itemId = $request->item_id;
        try {
            DB::table('groups_items')->where('item_id', '=', $itemId)->delete();
            $item = Item::findOrFail($itemId);
            $item->delete();
            return $this->successResponse([], 'success');
        } catch (\Exception $e) {
            return $this->errorResponse('fail', 402);
        }
    }

    public function deleteCourse(Request $request)
    {
        $this->validate($request, [
            'course_id' => 'required|string'
        ]);
        try {

            $courseId = $request->course_id;
            $items = Item::where('course_id', '=', $courseId)->get();
            foreach ($items as $item) {
                $itemId = $item->id;
                DB::table('groups_items')->where('item_id', '=', $itemId)->delete();
            }
            Item::where('course_id', '=', $courseId)->delete();
            Course::where('id', '=', $courseId)->delete();
            return $this->successResponse([], 'Success');

        } catch (\Exception $exception) {
            return $this->errorResponse('Failed', 402);
        }
    }

    public function getCourseByKeyword(Request $request)
    {
        $perPage = $request->per_page ? (int)$request->per_page : 10;
        $keyword = $request->input('keyword');
        if ($keyword !== '') {
            $courses = Course::query()
                ->where('name', 'LIKE', '%' . $keyword . "%")
                ->where('zone_id', '!=', 0)
                ->paginate($perPage);
        } else {
            $courses = Course::where('zone_id', '!=', 0)->paginate($perPage);
        }
        $result = new CourseCollection($courses);
        return $this->successResponse($result, 'Success', 200);
    }
}
