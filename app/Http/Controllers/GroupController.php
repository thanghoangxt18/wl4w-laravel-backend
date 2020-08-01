<?php

namespace App\Http\Controllers;

use App\Http\Resources\Group\GroupCollection;
use App\Models\Group;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    use ApiResponse;

    public function createNewGroup(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'banner' => 'required|file',
            'description' => 'required|string',
        ]);
        $group = new Group();
        $group->name = $request->name;
        $bannerPath = Storage::put('images/group', $request->banner);
        $group->banner = $bannerPath;
        $group->description = $request->description;
        try {
            $group->save();
            return $this->successResponse([], 'Success');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => 'Create failed']
            );
        }
    }

    public function getGroupByKeyword(Request $request)
    {
        $this->validate($request, [
            'keyword' => 'required|string'
        ]);

        $groupListSearched = Group::query()
            ->where('name', 'LIKE', '%' . $request->keyword . "%")
            ->paginate(10);


        $result = new GroupCollection($groupListSearched);
        return $this->successResponse($result, 'Success', 200);
    }

    public function updateGroup(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|int',
            'name' => 'required|string',
            'banner' => 'required|file',
            'description' => 'required|string',
        ]);

        $group_id = $request->id;
        $group = Group::find($group_id);
        $group->name = $request->name;
        $bannerPath = Storage::put('images/group', $request->banner);
        $group->banner = $bannerPath;
        $group->description = $request->description;
        try {
            $group->save();
            return $this->successResponse([], 'Success');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => 'Create failed']
            );
        }
    }

    public function addNewExerciseToGroup(Request $request)
    {
        $this->validate($request, [
            'exercise_id' => 'required|int',
            'group_id' => 'required|int',
            'order' => 'required|int'
        ]);
        try {
            DB::table('exercise_groups')->insert(
                ['exercise_id' => $request->exercise_id, 'group_id' => $request->group_id, 'order' => $request->order]);
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
            'group_id' => 'required|int',
            'exercise_id' => 'required|int'
        ]);
        try {
            DB::table('exercise_groups')
                ->where('exercise_id', '=', $request->exercise_id)
                ->where('group_id', '=', $request->group_id)
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
            DB::table('exercise_group')->where('group_id', '=', $request->group_id);
            Group::where('id', '=', $request->group_id)->delete();
            return $this->successResponse([], 'Success');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed', 402, ['message' => 'Create failed']
            );
        }
    }
}
