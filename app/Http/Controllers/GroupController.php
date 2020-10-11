<?php

namespace App\Http\Controllers;

use App\Http\Resources\Group\GroupCollection;
use App\Http\Resources\Group\GroupResource;
use App\Models\Group;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class GroupController extends Controller
{
    use ApiResponse;

    const STORAGE_PATH_GROUP = '/images/group';

    public function getGroupList(Request $request)
    {
        $per_page = $request->per_page ? (int)$request->per_page : 5;
        $groups = Group::paginate($per_page);
        $result = new GroupCollection($groups);
        return $this->successResponse($result, 'success');
    }

    public function createNewGroup(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'banner' => 'required',
            'thumb' => 'required',
            'description' => 'required|string',
        ]);
        $group = new Group();
        $group->name = $request->name;
        $bannerUrl = $this->uploadImage($request->banner);
        $thumbUrl = $this->uploadImage($request->thumb);

        $group->banner = $bannerUrl;
        $group->thumb = $thumbUrl;
        $group->description = $request->description;
        try {
            $group->save();
            return $this->successResponse(new GroupResource($group), 'Success');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => $e->getMessage()]
            );
        }
    }

    public function getGroupByKeyword(Request $request)
    {
        $per_page = $request->per_page ? (int)$request->per_page : 5;
        if ($request->keyword !== '') {
            $groupListSearched = Group::query()
                ->where('name', 'LIKE', '%' . $request->keyword . "%")
                ->paginate($per_page);
        } else {
            $groupListSearched = Group::query()->paginate($per_page);
        }
        $result = new GroupCollection($groupListSearched);
        return $this->successResponse($result, 'Success', 200);
    }

    public function updateGroup(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|int',
            'name' => 'required|string',
            'description' => 'required|string',
        ]);

        $group_id = $request->id;
        $group = Group::find($group_id);
        $group->name = $request->name;
        $bannerUrl = $this->uploadImage($request->banner);
        $thumbUrl = $this->uploadImage($request->thumb);

        $group->banner = $bannerUrl ? $bannerUrl : $group->banner;
        $group->thumb = $thumbUrl ? $thumbUrl : $group->thumb;
        $group->description = $request->description;
        try {
            $group->save();
            return $this->successResponse(new GroupResource($group), 'Success');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => $e->getMessage()]
            );
        }
    }

    public function addNewExerciseToGroup(Request $request)
    {
        $this->validate($request, [
            'exercise_id' => 'required|int',
            'group_id' => 'required|int',
            'order' => 'int'
        ]);
        try {
            DB::table('exercise_groups')->insert(
                ['exercise_id' => $request->exercise_id,
                    'group_id' => $request->group_id,
                    'order' => $request->order ? (int)$request->order : 1]);
            return $this->successResponse([], 'Success');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => 'Create failed']
            );
        }
    }

    public function deleteExerciseOfGroup(Request $request)
    {
        $this->validate($request, [
            'exercise_group_id' => 'required|int'
        ]);
        try {
            DB::table('exercise_groups')
                ->where('id', '=', $request->exercise_group_id)
                ->delete();
            return $this->successResponse([], 'Success');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, []
            );
        }
    }

    public function deleteGroup(Request $request)
    {
        $this->validate($request, [
            'group_id' => 'int|required'
        ]);
        try {
            DB::table('exercise_groups')->where('group_id', '=', $request->group_id)->delete();
            Group::where('id', '=', $request->group_id)->delete();
            return $this->successResponse([], 'Success');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => 'Create failed']
            );
        }
    }

    public function updateOrderOfExercise(Request $request)
    {
        $this->validate($request, [
            'group_id' => 'int|required',
            'exercise_order' => 'array|required'
        ]);
        $groupId = (int)$request->input('group_id');
        $exerciseList = $request->input('exercise_order');
        DB::table('exercise_groups')
            ->where('group_id', '=', $groupId)
            ->delete();
        try {
            foreach ($exerciseList as $exercise) {
                $exerciseId = (int)$exercise['id'];
                $index = (int)$exercise['index'];
                DB::table('exercise_groups')
                    ->insert(['exercise_id' => $exerciseId, 'group_id' => $groupId, 'order' => $index]);
            }
            return $this->successResponse([], 'Success');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => $e->getMessage()]);
        }
    }

    public function uploadImage($file) {
        if ($file) {
            $path = Storage::put(self::STORAGE_PATH_GROUP, $file);
            $ext = $file->getClientOriginalExtension();
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $mainFilename = $fileName . Str::random(6) . date('Y-m-d-h-i-s');
            $url = self::STORAGE_PATH_GROUP . '/' . $mainFilename . "." . $ext;
            Storage::move($path, $url);
            $url = 'storage' . $url;
        }

        return $url ?? '';
    }
}
